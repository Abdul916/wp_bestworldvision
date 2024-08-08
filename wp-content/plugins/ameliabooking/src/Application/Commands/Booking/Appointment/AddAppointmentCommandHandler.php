<?php

namespace AmeliaBooking\Application\Commands\Booking\Appointment;

use AmeliaBooking\Application\Commands\CommandHandler;
use AmeliaBooking\Application\Commands\CommandResult;
use AmeliaBooking\Application\Common\Exceptions\AccessDeniedException;
use AmeliaBooking\Application\Services\Bookable\BookableApplicationService;
use AmeliaBooking\Application\Services\Booking\AppointmentApplicationService;
use AmeliaBooking\Application\Services\Entity\EntityApplicationService;
use AmeliaBooking\Application\Services\User\UserApplicationService;
use AmeliaBooking\Domain\Collection\Collection;
use AmeliaBooking\Domain\Common\Exceptions\AuthorizationException;
use AmeliaBooking\Domain\Common\Exceptions\InvalidArgumentException;
use AmeliaBooking\Domain\Entity\Bookable\Service\Service;
use AmeliaBooking\Domain\Entity\Booking\Appointment\Appointment;
use AmeliaBooking\Domain\Entity\Booking\Appointment\CustomerBooking;
use AmeliaBooking\Domain\Entity\Entities;
use AmeliaBooking\Domain\Entity\User\AbstractUser;
use AmeliaBooking\Domain\Services\DateTime\DateTimeService;
use AmeliaBooking\Domain\Services\Reservation\ReservationServiceInterface;
use AmeliaBooking\Domain\Services\Settings\SettingsService;
use AmeliaBooking\Domain\ValueObjects\BooleanValueObject;
use AmeliaBooking\Domain\ValueObjects\PositiveDuration;
use AmeliaBooking\Domain\ValueObjects\String\BookingStatus;
use AmeliaBooking\Infrastructure\Common\Exceptions\NotFoundException;
use AmeliaBooking\Infrastructure\Common\Exceptions\QueryExecutionException;
use AmeliaBooking\Infrastructure\Repository\Booking\Appointment\AppointmentRepository;
use AmeliaBooking\Infrastructure\WP\Translations\FrontendStrings;
use Exception;
use Interop\Container\Exception\ContainerException;
use Slim\Exception\ContainerValueNotFoundException;

/**
 * Class AddAppointmentCommandHandler
 *
 * @package AmeliaBooking\Application\Commands\Booking\Appointment
 */
class AddAppointmentCommandHandler extends CommandHandler
{
    /**
     * @var array
     */
    public $mandatoryFields = [
        'bookings',
        'bookingStart',
        'notifyParticipants',
        'serviceId',
        'providerId'
    ];

    /**
     * @param AddAppointmentCommand $command
     *
     * @return CommandResult
     * @throws NotFoundException
     * @throws ContainerValueNotFoundException
     * @throws InvalidArgumentException
     * @throws QueryExecutionException
     * @throws ContainerException
     * @throws Exception
     */
    public function handle(AddAppointmentCommand $command)
    {
        $result = new CommandResult();

        $this->checkMandatoryFields($command);

        /** @var AppointmentRepository $appointmentRepo */
        $appointmentRepo = $this->container->get('domain.booking.appointment.repository');
        /** @var AppointmentApplicationService $appointmentAS */
        $appointmentAS = $this->container->get('application.booking.appointment.service');
        /** @var BookableApplicationService $bookableAS */
        $bookableAS = $this->container->get('application.bookable.service');
        /** @var UserApplicationService $userAS */
        $userAS = $this->getContainer()->get('application.user.service');
        /** @var SettingsService $settingsDS */
        $settingsDS = $this->container->get('domain.settings.service');
        /** @var EntityApplicationService $entityService */
        $entityService = $this->container->get('application.entity.service');
        /** @var ReservationServiceInterface $reservationService */
        $reservationService = $this->container->get('application.reservation.service')->get(Entities::APPOINTMENT);

        if ($missingEntity = $entityService->getMissingEntityForAppointment($command->getFields())) {
            return $entityService->getMissingEntityResponse($missingEntity);
        }

        try {
            /** @var AbstractUser $user */
            $user = $command->getUserApplicationService()->authorization(
                $command->getPage() === 'cabinet' ? $command->getToken() : null,
                $command->getCabinetType()
            );
        } catch (AuthorizationException $e) {
            $result->setResult(CommandResult::RESULT_ERROR);
            $result->setData(
                [
                    'reauthorize' => true
                ]
            );

            return $result;
        }

        if ($userAS->isCustomer($user)) {
            throw new AccessDeniedException('You are not allowed to update appointment');
        }

        if ($userAS->isProvider($user) && !$settingsDS->getSetting('roles', 'allowWriteAppointments')) {
            throw new AccessDeniedException('You are not allowed to add an appointment');
        }

        $appointmentData = $command->getFields();

        $paymentData = !empty($command->getField('payment')) ? array_merge($command->getField('payment'), ['isBackendBooking' => true]) :
            ['amount' => 0, 'gateway' => 'onSite', 'isBackendBooking' => true];

        /** @var Service $service */
        $service = $bookableAS->getAppointmentService($appointmentData['serviceId'], $appointmentData['providerId']);

        $appointmentData = apply_filters('amelia_before_appointment_added_filter', $appointmentData, $service ? $service->toArray() : null, $paymentData);

        do_action('amelia_before_appointment_added', $appointmentData, $service ? $service->toArray() : null, $paymentData);

        $maxDuration = 0;

        foreach ($appointmentData['bookings'] as $booking) {
            if ($booking['duration'] > $maxDuration && ($booking['status'] === BookingStatus::APPROVED || BookingStatus::PENDING)) {
                $maxDuration = $booking['duration'];
            }
        }

        if ($maxDuration) {
            $service->setDuration(new PositiveDuration($maxDuration));
        }

        $appointmentAS->convertTime($appointmentData);

        $reservationService->manageTaxes($appointmentData);

        /** @var Appointment $appointment */
        $appointment = $appointmentAS->build($appointmentData, $service);

        /** @var Appointment $existingAppointment */
        $existingAppointment = $appointmentAS->getFreeAlreadyBookedAppointment($appointmentData);

        /** @var CustomerBooking $booking */
        foreach ($appointment->getBookings()->getItems() as $booking) {
            if (!$appointmentAS->processPackageAppointmentBooking(
                $booking,
                new Collection(),
                $appointment->getServiceId()->getValue(),
                $paymentData
            )) {
                $result->setResult(CommandResult::RESULT_ERROR);
                $result->setMessage(FrontendStrings::getCommonStrings()['package_booking_unavailable']);
                $result->setData(
                    [
                        'packageBookingUnavailable' => true
                    ]
                );

                return $result;
            }
        }

        $appointmentRepo->beginTransaction();

        /** @var CustomerBooking $booking */
        foreach ($appointment->getBookings()->getItems() as $booking) {
            $booking->setChangedStatus(new BooleanValueObject(true));
        }

        $appointmentsDateTimes = $command->getField('recurring') ? DateTimeService::getSortedDateTimeStrings(
            array_merge(
                [$appointmentData['bookingStart']],
                array_column($command->getField('recurring'), 'bookingStart')
            )
        ) : [$appointmentData['bookingStart']];

        if (!$appointmentAS->canBeBooked(
            $appointment,
            false,
            DateTimeService::getCustomDateTimeObject($appointmentsDateTimes[0]),
            DateTimeService::getCustomDateTimeObject($appointmentsDateTimes[sizeof($appointmentsDateTimes) - 1])
        )) {
            $result->setResult(CommandResult::RESULT_ERROR);
            $result->setMessage(FrontendStrings::getCommonStrings()['package_booking_unavailable']);
            $result->setData(
                [
                    'timeSlotUnavailable' => true
                ]
            );

            return $result;
        }

        if ($existingAppointment === null) {
            $appointmentAS->add($appointment, $service, $paymentData, true);
        } else {
            $appointmentAS->updateExistingAppointment(
                $appointment,
                $existingAppointment,
                $service,
                $paymentData
            );
        }

        $recurringAppointments = [];

        foreach ($command->getField('recurring') as $recurringData) {
            $recurringAppointmentData = array_merge(
                $appointmentData,
                [
                    'bookingStart' => $recurringData['bookingStart'],
                    'locationId'   => $recurringData['locationId'],
                    'parentId'     => $appointment->getId()->getValue()
                ]
            );

            if ($appointment->getBookings() &&
                $appointment->getBookings()->keyExists(0) &&
                $appointment->getBookings()->getItem(0)->getPayments() &&
                $appointment->getBookings()->getItem(0)->getPayments()->keyExists(0)
            ) {
                $paymentData['parentId'] =
                    $appointment->getBookings()->getItem(0)->getPayments()->getItem(0)->getId()->getValue();
            }

            $appointmentAS->convertTime($recurringAppointmentData);

            /** @var Appointment $recurringAppointment */
            $recurringAppointment = $appointmentAS->build($recurringAppointmentData, $service);

            /** @var Appointment $existingRecurringAppointment */
            $existingRecurringAppointment = $appointmentAS->getFreeAlreadyBookedAppointment($recurringAppointmentData);

            /** @var CustomerBooking $booking */
            foreach ($recurringAppointment->getBookings()->getItems() as $booking) {
                $booking->setChangedStatus(new BooleanValueObject(true));
            }

            if (!$appointmentAS->canBeBooked(
                $recurringAppointment,
                false,
                DateTimeService::getCustomDateTimeObject($appointmentsDateTimes[0]),
                DateTimeService::getCustomDateTimeObject($appointmentsDateTimes[sizeof($appointmentsDateTimes) - 1])
            )) {
                $appointmentAS->delete($appointment);

                foreach ($recurringAppointments as $savedRecurringAppointment) {
                    $appointmentAS->delete(
                        $appointmentAS->build($savedRecurringAppointment[Entities::APPOINTMENT], $service)
                    );
                }

                $result->setResult(CommandResult::RESULT_ERROR);
                $result->setMessage(FrontendStrings::getCommonStrings()['time_slot_unavailable']);
                $result->setData(
                    [
                        'timeSlotUnavailable' => true
                    ]
                );

                return $result;
            }

            if ($existingRecurringAppointment === null) {
                $appointmentAS->add($recurringAppointment, $service, $paymentData, true);
            } else {
                $appointmentAS->updateExistingAppointment(
                    $recurringAppointment,
                    $existingRecurringAppointment,
                    $service,
                    $paymentData
                );
            }

            $recurringAppointments[] = [
                Entities::APPOINTMENT => $recurringAppointment->toArray()
            ];
        }

        $result->setResult(CommandResult::RESULT_SUCCESS);
        $result->setMessage('Successfully added new appointment');
        $result->setData(
            [
                Entities::APPOINTMENT => $appointment->toArray(),
                'recurring'           => $recurringAppointments
            ]
        );

        $appointmentRepo->commit();

        do_action('amelia_after_appointment_added', $appointment ? $appointment->toArray() : null, $service ? $service->toArray() : null, $paymentData);

        return $result;
    }
}
