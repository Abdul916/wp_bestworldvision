<?php
/**
 * @copyright © TMS-Plugins. All rights reserved.
 * @licence   See LICENCE.md for license details.
 */

namespace AmeliaBooking\Infrastructure\WP\EventListeners\Booking\Appointment;

use AmeliaBooking\Application\Commands\CommandResult;
use AmeliaBooking\Application\Services\Bookable\AbstractPackageApplicationService;
use AmeliaBooking\Application\Services\Booking\BookingApplicationService;
use AmeliaBooking\Application\Services\Booking\IcsApplicationService;
use AmeliaBooking\Application\Services\Helper\HelperService;
use AmeliaBooking\Application\Services\Notification\EmailNotificationService;
use AmeliaBooking\Application\Services\Notification\SMSNotificationService;
use AmeliaBooking\Application\Services\Notification\AbstractWhatsAppNotificationService;
use AmeliaBooking\Application\Services\Payment\PaymentApplicationService;
use AmeliaBooking\Application\Services\WebHook\AbstractWebHookApplicationService;
use AmeliaBooking\Domain\Common\Exceptions\InvalidArgumentException;
use AmeliaBooking\Domain\Entity\Bookable\Service\Package;
use AmeliaBooking\Domain\Entity\Booking\Appointment\Appointment;
use AmeliaBooking\Domain\Entity\Booking\Event\Event;
use AmeliaBooking\Domain\Entity\Entities;
use AmeliaBooking\Domain\Factory\Booking\Appointment\AppointmentFactory;
use AmeliaBooking\Domain\Factory\Booking\Event\EventFactory;
use AmeliaBooking\Domain\Services\Settings\SettingsService;
use AmeliaBooking\Domain\ValueObjects\BooleanValueObject;
use AmeliaBooking\Domain\ValueObjects\String\BookingStatus;
use AmeliaBooking\Infrastructure\Common\Container;
use AmeliaBooking\Infrastructure\Common\Exceptions\NotFoundException;
use AmeliaBooking\Infrastructure\Common\Exceptions\QueryExecutionException;
use AmeliaBooking\Infrastructure\Repository\Bookable\Service\PackageRepository;
use AmeliaBooking\Infrastructure\Repository\Booking\Appointment\CustomerBookingRepository;
use AmeliaBooking\Infrastructure\Repository\Coupon\CouponRepository;
use AmeliaBooking\Infrastructure\Services\Google\AbstractGoogleCalendarService;
use AmeliaBooking\Application\Services\Zoom\AbstractZoomApplicationService;
use AmeliaBooking\Infrastructure\Services\LessonSpace\AbstractLessonSpaceService;
use AmeliaBooking\Infrastructure\Services\Outlook\AbstractOutlookCalendarService;
use AmeliaBooking\Infrastructure\Repository\Payment\PaymentRepository;
use Exception;
use Interop\Container\Exception\ContainerException;
use Slim\Exception\ContainerValueNotFoundException;

/**
 * Class BookingAddedEventHandler
 *
 * @package AmeliaBooking\Infrastructure\WP\EventListeners\Booking\Appointment
 */
class BookingAddedEventHandler
{
    /** @var string */
    const BOOKING_ADDED = 'bookingAdded';

    /** @var string */
    const PACKAGE_PURCHASED = 'packagePurchased';

    /**
     * @param CommandResult $commandResult
     * @param Container     $container
     *
     * @throws NotFoundException
     * @throws InvalidArgumentException
     * @throws ContainerValueNotFoundException
     * @throws QueryExecutionException
     * @throws ContainerException
     * @throws Exception
     */
    public static function handle($commandResult, $container)
    {
        /** @var AbstractGoogleCalendarService $googleCalendarService */
        $googleCalendarService = $container->get('infrastructure.google.calendar.service');
        /** @var EmailNotificationService $emailNotificationService */
        $emailNotificationService = $container->get('application.emailNotification.service');
        /** @var SMSNotificationService $smsNotificationService */
        $smsNotificationService = $container->get('application.smsNotification.service');
        /** @var AbstractWhatsAppNotificationService $whatsAppNotificationService */
        $whatsAppNotificationService = $container->get('application.whatsAppNotification.service');
        /** @var SettingsService $settingsService */
        $settingsService = $container->get('domain.settings.service');
        /** @var AbstractWebHookApplicationService $webHookService */
        $webHookService = $container->get('application.webHook.service');
        /** @var AbstractZoomApplicationService $zoomService */
        $zoomService = $container->get('application.zoom.service');
        /** @var AbstractLessonSpaceService $lessonSpaceService */
        $lessonSpaceService = $container->get('infrastructure.lesson.space.service');
        /** @var BookingApplicationService $bookingApplicationService */
        $bookingApplicationService = $container->get('application.booking.booking.service');
        /** @var PaymentRepository $paymentRepository */
        $paymentRepository = $container->get('domain.payment.repository');
        /** @var CustomerBookingRepository $bookingRepository */
        $bookingRepository = $container->get('domain.booking.customerBooking.repository');
        /** @var AbstractOutlookCalendarService $outlookCalendarService */
        $outlookCalendarService = $container->get('infrastructure.outlook.calendar.service');
        /** @var PaymentApplicationService $paymentAS */
        $paymentAS = $container->get('application.payment.service');
        /** @var SettingsService $settingsDS */
        $settingsDS = $container->get('domain.settings.service');
        /** @var AbstractPackageApplicationService $packageApplicationService */
        $packageApplicationService = $container->get('application.bookable.package');

        $type = $commandResult->getData()['type'];

        $booking = $commandResult->getData()[Entities::BOOKING];
        $appointmentStatusChanged = $commandResult->getData()['appointmentStatusChanged'];

        $paymentId = $commandResult->getData()['paymentId'];

        if (!empty($booking['couponId']) && empty($booking['coupon'])) {
            /** @var CouponRepository $couponRepository */
            $couponRepository = $container->get('domain.coupon.repository');
            $coupon           = $couponRepository->getById($booking['couponId']);
            if ($coupon) {
                $booking['coupon'] = $coupon->toArray();
                unset($booking['coupon']['serviceList']);
                unset($booking['coupon']['eventList']);
                unset($booking['coupon']['packageList']);
            }
        }

        $recurringData = $commandResult->getData()['recurring'];

        if ($commandResult->getData()['packageId']) {
            /** @var PackageRepository $packageRepository */
            $packageRepository = $container->get('domain.bookable.package.repository');

            /** @var Package $package */
            $package = $packageRepository->getById($commandResult->getData()['packageId']);

            $packageReservation = array_merge(
                array_merge($package->toArray(), ['customer' => $commandResult->getData()['customer']]),
                [
                    'status'            => 'purchased',
                    'packageCustomerId' => !empty($commandResult->getData()['packageCustomerId']) ?
                        $commandResult->getData()['packageCustomerId'] : null,
                    'isRetry'           => !empty($commandResult->getData()['isRetry']) ?
                        $commandResult->getData()['isRetry'] : null,
                    'recurring'         => []
                ]
            );

            if (!empty($paymentId) && empty($commandResult->getData()['fromLink'])) {
                $data = $commandResult->getData();
                $data['booking'] = $booking;
                $data['type'] = Entities::PACKAGE;
                $data['package'] = $package->toArray();
                $data['bookable'] = $package->toArray();
                $data['packageReservations'] = $booking === null ? [] : array_merge([$data['appointment']], array_column($data['recurring'], 'appointment'));
                $packageReservation['paymentLinks'] = $paymentAS->createPaymentLink($data);
            }

            if ($booking === null) {
                $packageReservation['onlyOneEmployee'] = $packageApplicationService->getOnlyOneEmployee($package->toArray());
                $emailNotificationService->sendPackageNotifications($packageReservation, true);

                if ($settingsService->getSetting('notifications', 'smsSignedIn') === true) {
                    $smsNotificationService->sendPackageNotifications($packageReservation, true);
                }

                if ($whatsAppNotificationService->checkRequiredFields()) {
                    $whatsAppNotificationService->sendPackageNotifications($packageReservation, true);
                }

                /** @var HelperService $helperService */
                $helperService = $container->get('application.helper.service');

                $packageReservation['customer']['customerPanelUrl'] = $helperService->getCustomerCabinetUrl(
                    $packageReservation['customer']['email'],
                    'email',
                    null,
                    null,
                    ''
                );

                $webHookService->process(self::PACKAGE_PURCHASED, $packageReservation, null);


                if (!empty($paymentId)) {
                    $paymentRepository->updateFieldById($paymentId, 1, 'actionsCompleted');
                }

                return;
            }
        }

        $booking['isLastBooking'] = true;

        /** @var Appointment|Event $reservationObject */
        $reservationObject = null;

        if ($type === Entities::APPOINTMENT) {
            $reservationObject = AppointmentFactory::create($commandResult->getData()[$type]);
        }

        if ($type === Entities::EVENT) {
            $reservationObject = EventFactory::create($commandResult->getData()[$type]);
        }

        $reservation = $reservationObject->toArray();


        $reservation['isRetry'] = !empty($commandResult->getData()['isRetry']) ?
            $commandResult->getData()['isRetry'] : false;

        $data = $commandResult->getData();
        $data['booking'] = $booking;
        if ($type === Entities::APPOINTMENT) {
            $bookingApplicationService->setReservationEntities($reservationObject);
            $data['bookable'] = $reservationObject->getService()->toArray();
        } else {
            $data['bookable'] = $reservationObject->toArray();
        }

        $currentBookingIndex = 0;

        foreach ($reservation['bookings'] as $index => $reservationBooking) {
            if ($booking['id'] === $reservationBooking['id']) {
                $reservation['bookings'][$index]['isLastBooking'] = true;
                $reservationObject->getBookings()->getItem($index)->setLastBooking(new BooleanValueObject(true));

                $currentBookingIndex = $index;
                if (!empty($paymentId) && !$commandResult->getData()['packageId'] && empty($commandResult->getData()['fromLink'])) {
                    $reservation['bookings'][$index]['payments'][0]['paymentLinks'] = $paymentAS->createPaymentLink($data, $index);
                }
            }
        }


        if ($type === Entities::APPOINTMENT) {
            $reservation['provider'] = $reservationObject->getProvider()->toArray();

            if ($zoomService) {
                $zoomService->handleAppointmentMeeting($reservationObject, self::BOOKING_ADDED);

                if ($reservationObject->getZoomMeeting()) {
                    $reservation['zoomMeeting'] = $reservationObject->getZoomMeeting()->toArray();
                }
            }

            if ($lessonSpaceService) {
                $lessonSpaceService->handle($reservationObject, Entities::APPOINTMENT, null, $booking);
                if ($reservationObject->getLessonSpace()) {
                    $reservation['lessonSpace'] = $reservationObject->getLessonSpace();
                }
            }

            if ($googleCalendarService) {
                try {
                    $googleCalendarService->handleEvent($reservationObject, self::BOOKING_ADDED);
                } catch (Exception $e) {
                }
            }

            if ($reservationObject->getGoogleCalendarEventId() !== null) {
                $reservation['googleCalendarEventId'] = $reservationObject->getGoogleCalendarEventId()->getValue();
            }
            if ($reservationObject->getGoogleMeetUrl() !== null) {
                $reservation['googleMeetUrl'] = $reservationObject->getGoogleMeetUrl();
            }

            if ($outlookCalendarService) {
                try {
                    $outlookCalendarService->handleEvent($reservationObject, self::BOOKING_ADDED);
                } catch (Exception $e) {
                }
            }

            if ($reservationObject->getOutlookCalendarEventId() !== null) {
                $reservation['outlookCalendarEventId'] = $reservationObject->getOutlookCalendarEventId()->getValue();
            }
        }

        if ($type === Entities::EVENT) {
            if ($zoomService) {
                $zoomService->handleEventMeeting(
                    $reservationObject,
                    $reservationObject->getPeriods(),
                    self::BOOKING_ADDED
                );

                $reservation['periods'] = $reservationObject->getPeriods()->toArray();
            }

            if ($googleCalendarService) {
                try {
                    $googleCalendarService->handleEventPeriodsChange($reservationObject, self::BOOKING_ADDED, $reservationObject->getPeriods());
                } catch (Exception $e) {
                }
            }
            if ($outlookCalendarService) {
                try {
                    $outlookCalendarService->handleEventPeriod($reservationObject, self::BOOKING_ADDED, $reservationObject->getPeriods());
                } catch (Exception $e) {
                }
            }
        }

        foreach ($recurringData as $key => $recurringReservationData) {
            $recurringReservationObject = AppointmentFactory::create($recurringReservationData[$type]);

            $bookingApplicationService->setReservationEntities($recurringReservationObject);

            $recurringData[$key][$type]['provider'] = $recurringReservationObject->getProvider()->toArray();

            if ($zoomService) {
                $zoomService->handleAppointmentMeeting($recurringReservationObject, self::BOOKING_ADDED);

                if ($recurringReservationObject->getZoomMeeting()) {
                    $recurringData[$key][$type]['zoomMeeting'] =
                        $recurringReservationObject->getZoomMeeting()->toArray();
                }
            }

            if ($lessonSpaceService) {
                $lessonSpaceService->handle($recurringReservationObject, Entities::APPOINTMENT);
                if ($recurringReservationObject->getLessonSpace()) {
                    $recurringData[$key][Entities::APPOINTMENT]['lessonSpace'] = $recurringReservationObject->getLessonSpace();
                }
            }

            if ($googleCalendarService) {
                try {
                    $googleCalendarService->handleEvent($recurringReservationObject, self::BOOKING_ADDED);
                } catch (Exception $e) {
                }

                if ($recurringReservationObject->getGoogleCalendarEventId() !== null) {
                    $recurringData[$key][$type]['googleCalendarEventId'] =
                        $recurringReservationObject->getGoogleCalendarEventId()->getValue();
                }
                if ($recurringReservationObject->getGoogleMeetUrl() !== null) {
                    $recurringData[$key][$type]['googleMeetUrl'] =
                        $recurringReservationObject->getGoogleMeetUrl();
                }
            }

            if ($outlookCalendarService) {
                try {
                    $outlookCalendarService->handleEvent($recurringReservationObject, self::BOOKING_ADDED);
                } catch (Exception $e) {
                }

                if ($recurringReservationObject->getOutlookCalendarEventId() !== null) {
                    $recurringData[$key][$type]['outlookCalendarEventId'] =
                        $recurringReservationObject->getOutlookCalendarEventId()->getValue();
                }
            }

            $currentBookingIndex = 0;

            foreach ($recurringReservationData[$type]['bookings'] as $index => $reservationBooking) {
                $recurringData[$key][$type]['bookings'][$index]['isLastBooking'] = true;

                if ($recurringReservationData['booking']['id'] === $reservationBooking['id']) {
                    $currentBookingIndex = $index;
                }
            }

            if (!$commandResult->getData()['packageId'] && empty($commandResult->getData()['fromLink'])) {
                $dataRecurring = $recurringReservationData;
                $dataRecurring['bookable']  = $recurringReservationObject->toArray()['service'];
                $dataRecurring['customer']  = $data['customer'];
                $dataRecurring['recurring'] = array_column($recurringData, 'appointment');
                $recurringData[$key][$type]['bookings'][$currentBookingIndex]['payments'][0]['paymentLinks'] = $paymentAS->createPaymentLink($dataRecurring, $currentBookingIndex, $key);
            }
        }

        /** @var IcsApplicationService $icsService */
        $icsService = $container->get('application.ics.service');

        $recurringBookingIds = [];

        $icsFiles = [];

        foreach ($recurringData as $recurringReservation) {
            $recurringBookingIds[] = $recurringReservation[Entities::BOOKING]['id'];
        }

        foreach ($reservation['bookings'] as $index => $reservationBooking) {
            if ($reservationBooking['id'] === $booking['id'] &&
                ($booking['status'] === BookingStatus::APPROVED || $booking['status'] === BookingStatus::PENDING)) {
                $icsFiles = $icsService->getIcsData(
                    $type,
                    $booking['id'],
                    $recurringBookingIds,
                    true
                );

                $reservation['bookings'][$index]['icsFiles'] = $icsFiles;
            }
        }

        $reservation['recurring'] = $recurringData;

        if ($appointmentStatusChanged === true &&
            !$commandResult->getData()['packageId'] &&
            !$commandResult->getData()['isCart']
        ) {
            foreach ($reservation['bookings'] as $bookingKey => $bookingArray) {
                if ($bookingArray['id'] !== $booking['id'] &&
                    $bookingArray['status'] === BookingStatus::APPROVED &&
                    $reservation['status'] === BookingStatus::APPROVED
                ) {
                    $reservation['bookings'][$bookingKey]['isChangedStatus'] = true;
                }
            }
        }

        if ($appointmentStatusChanged === true &&
            !$commandResult->getData()['packageId'] &&
            !$commandResult->getData()['isCart']
        ) {
            $emailNotificationService->sendAppointmentStatusNotifications($reservation, empty($commandResult->getData()['fromLink']), true);

            if ($settingsService->getSetting('notifications', 'smsSignedIn') === true) {
                $smsNotificationService->sendAppointmentStatusNotifications($reservation, empty($commandResult->getData()['fromLink']), true);
            }

            if ($whatsAppNotificationService->checkRequiredFields()) {
                $whatsAppNotificationService->sendAppointmentStatusNotifications($reservation, empty($commandResult->getData()['fromLink']), true);
            }
        }

        if ($appointmentStatusChanged !== true &&
            !$commandResult->getData()['packageId'] &&
            !$commandResult->getData()['isCart']
        ) {
            $emailNotificationService->sendBookingAddedNotifications($reservation, $booking, true);

            if ($settingsService->getSetting('notifications', 'smsSignedIn') === true) {
                $smsNotificationService->sendBookingAddedNotifications($reservation, $booking, true);
            }

            if ($whatsAppNotificationService->checkRequiredFields()) {
                $whatsAppNotificationService->sendBookingAddedNotifications($reservation, $booking, true);
            }
        }

        if ($commandResult->getData()['packageId']) {
            $packageReservation = array_merge(
                $packageReservation,
                [
                    'icsFiles'          => $icsFiles,
                    'recurring' => array_merge(
                        [
                            [
                                'type'                     => Entities::APPOINTMENT,
                                Entities::APPOINTMENT      => $reservation,
                                Entities::BOOKING          => $booking,
                                'appointmentStatusChanged' => $appointmentStatusChanged,
                            ]
                        ],
                        $reservation['recurring']
                    )
                ]
            );

            $emailNotificationService->sendPackageNotifications($packageReservation, true);

            if ($settingsService->getSetting('notifications', 'smsSignedIn') === true) {
                $smsNotificationService->sendPackageNotifications($packageReservation, true);
            }

            if ($whatsAppNotificationService->checkRequiredFields()) {
                $whatsAppNotificationService->sendPackageNotifications($packageReservation, true);
            }
        }

        if ($commandResult->getData()['isCart']) {
            $cartReservation = [
                'type'              => Entities::APPOINTMENTS,
                'customer'          => $commandResult->getData()['customer'],
                'icsFiles'          => $icsFiles,
                'isRetry'           => !empty($commandResult->getData()['isRetry']) ?
                    $commandResult->getData()['isRetry'] : null,
                'status'            => 'purchase',
                'recurring' => array_merge(
                    [
                        [
                            'type'                     => Entities::APPOINTMENT,
                            Entities::APPOINTMENT      => $reservation,
                            Entities::BOOKING          => $booking,
                            'appointmentStatusChanged' => $appointmentStatusChanged,
                        ]
                    ],
                    $reservation['recurring']
                )
            ];

            $emailNotificationService->sendCartNotifications($cartReservation, true);

            if ($settingsService->getSetting('notifications', 'smsSignedIn') === true) {
                $smsNotificationService->sendCartNotifications($cartReservation, true);
            }

            if ($whatsAppNotificationService->checkRequiredFields()) {
                $whatsAppNotificationService->sendCartNotifications($cartReservation, true);
            }
        }

        foreach ($recurringData as $key => $recurringReservationData) {
            if ($recurringReservationData['appointmentStatusChanged'] === true) {
                foreach ($recurringReservationData[$type]['bookings'] as $bookingKey => $recurringReservationBooking) {
                    if ($recurringReservationBooking['customerId'] === $booking['customerId']) {
                        $recurringData[$key][$type]['bookings'][$bookingKey]['skipNotification'] = true;
                    }

                    if ($recurringReservationBooking['id'] !== $booking['id'] &&
                        $recurringReservationBooking['status'] === BookingStatus::APPROVED &&
                        $recurringData[$key][$type]['status'] === BookingStatus::APPROVED
                    ) {
                        $recurringData[$key][$type]['bookings'][$bookingKey]['isChangedStatus'] = true;
                    }
                }

                $emailNotificationService->sendAppointmentStatusNotifications(
                    $recurringData[$key][$type],
                    true,
                    true
                );

                if ($settingsService->getSetting('notifications', 'smsSignedIn') === true) {
                    $smsNotificationService->sendAppointmentStatusNotifications(
                        $recurringData[$key][$type],
                        true,
                        true
                    );
                }

                if ($whatsAppNotificationService->checkRequiredFields()) {
                    $whatsAppNotificationService->sendAppointmentStatusNotifications(
                        $recurringData[$key][$type],
                        true,
                        true
                    );
                }
            }
        }

        $webHookService->process(
            self::BOOKING_ADDED,
            $reservation,
            [
                array_merge(
                    $booking,
                    [
                        'isRecurringBooking' => $recurringData && !$commandResult->getData()['packageId'],
                        'isPackageBooking'   => !!$commandResult->getData()['packageId'],
                    ]
                )
            ]
        );

        foreach ($recurringData as $recurringReservationData) {
            $webHookService->process(
                self::BOOKING_ADDED,
                $recurringReservationData[$type],
                [
                    array_merge(
                        $recurringReservationData['booking'],
                        [
                            'isRecurringBooking' => !$commandResult->getData()['packageId'],
                            'isPackageBooking'   => !!$commandResult->getData()['packageId'],
                        ]
                    )
                ]
            );

            $bookingRepository->updateFieldById($recurringReservationData['booking']['id'], 1, 'actionsCompleted');
        }

        if (!empty($commandResult->getData()['paymentId'])) {
            $paymentRepository->updateFieldById($commandResult->getData()['paymentId'], 1, 'actionsCompleted');
        }

        if (!empty($booking['id'])) {
            $bookingRepository->updateFieldById($booking['id'], 1, 'actionsCompleted');
        }
    }
}
