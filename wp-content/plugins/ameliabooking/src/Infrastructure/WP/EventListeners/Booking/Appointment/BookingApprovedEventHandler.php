<?php
/**
 * @copyright © TMS-Plugins. All rights reserved.
 * @licence   See LICENCE.md for license details.
 */

namespace AmeliaBooking\Infrastructure\WP\EventListeners\Booking\Appointment;

use AmeliaBooking\Application\Commands\CommandResult;
use AmeliaBooking\Application\Services\Booking\BookingApplicationService;
use AmeliaBooking\Application\Services\Notification\EmailNotificationService;
use AmeliaBooking\Application\Services\Notification\SMSNotificationService;
use AmeliaBooking\Application\Services\Notification\AbstractWhatsAppNotificationService;
use AmeliaBooking\Application\Services\WebHook\AbstractWebHookApplicationService;
use AmeliaBooking\Domain\Common\Exceptions\InvalidArgumentException;
use AmeliaBooking\Domain\Entity\Entities;
use AmeliaBooking\Domain\Factory\Booking\Appointment\AppointmentFactory;
use AmeliaBooking\Domain\Services\Settings\SettingsService;
use AmeliaBooking\Infrastructure\Common\Container;
use AmeliaBooking\Infrastructure\Common\Exceptions\NotFoundException;
use AmeliaBooking\Infrastructure\Common\Exceptions\QueryExecutionException;
use AmeliaBooking\Infrastructure\Services\Google\AbstractGoogleCalendarService;
use AmeliaBooking\Application\Services\Zoom\AbstractZoomApplicationService;
use AmeliaBooking\Infrastructure\Services\Outlook\AbstractOutlookCalendarService;
use Exception;
use Interop\Container\Exception\ContainerException;
use Slim\Exception\ContainerValueNotFoundException;

/**
 * Class BookingApprovedEventHandler
 *
 * @package AmeliaBooking\Infrastructure\WP\EventListeners\Booking\Appointment
 */
class BookingApprovedEventHandler
{
    /** @var string */
    const BOOKING_APPROVED = 'bookingApproved';

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
        /** @var AbstractOutlookCalendarService $outlookCalendarService */
        $outlookCalendarService = $container->get('infrastructure.outlook.calendar.service');
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
        /** @var BookingApplicationService $bookingApplicationService */
        $bookingApplicationService = $container->get('application.booking.booking.service');
        /** @var AbstractZoomApplicationService $zoomService */
        $zoomService = $container->get('application.zoom.service');

        $appointment = $commandResult->getData()[$commandResult->getData()['type']];

        if ($commandResult->getData()['type'] === Entities::APPOINTMENT) {
            $reservationObject = AppointmentFactory::create($appointment);

            $bookingApplicationService->setReservationEntities($reservationObject);

            if ($zoomService) {
                $zoomService->handleAppointmentMeeting($reservationObject, self::BOOKING_APPROVED);

                if ($reservationObject->getZoomMeeting()) {
                    $appointment['zoomMeeting'] = $reservationObject->getZoomMeeting()->toArray();
                }
            }

            try {
                $googleCalendarService->handleEvent($reservationObject, self::BOOKING_APPROVED);
            } catch (Exception $e) {
            }

            if ($reservationObject->getGoogleCalendarEventId() !== null) {
                $appointment['googleCalendarEventId'] = $reservationObject->getGoogleCalendarEventId()->getValue();
            }
            if ($reservationObject->getGoogleMeetUrl() !== null) {
                $appointment['googleMeetUrl'] = $reservationObject->getGoogleMeetUrl();
            }

            try {
                $outlookCalendarService->handleEvent($reservationObject, self::BOOKING_APPROVED);
            } catch (Exception $e) {
            }

            if ($reservationObject->getOutlookCalendarEventId() !== null) {
                $appointment['outlookCalendarEventId'] = $reservationObject->getOutlookCalendarEventId()->getValue();
            }
        }

        $booking = $commandResult->getData()[Entities::BOOKING];

        $payments = $appointment['bookings'][0]['payments'];
        if ($payments && count($payments)) {
            $booking['payments'] = $payments;
        }

        $emailNotificationService->sendCustomerBookingNotification($appointment, $booking);

        if ($settingsService->getSetting('notifications', 'smsSignedIn') === true) {
            $smsNotificationService->sendCustomerBookingNotification($appointment, $booking);
        }

        if ($whatsAppNotificationService->checkRequiredFields()) {
            $whatsAppNotificationService->sendCustomerBookingNotification($appointment, $booking);
        }

        $appStatusChanged = $commandResult->getData()['appointmentStatusChanged'];

        if ($appStatusChanged === true) {
            $bookingKey = array_search($booking['id'], array_column($appointment['bookings'], 'id'), true);

            $appointment['bookings'][$bookingKey]['isChangedStatus'] = true;

            $appointment['notifyParticipants'] = false;

            $emailNotificationService->sendAppointmentStatusNotifications($appointment, true, true);

            if ($settingsService->getSetting('notifications', 'smsSignedIn') === true) {
                $smsNotificationService->sendAppointmentStatusNotifications($appointment, true, true);
            }

            if ($whatsAppNotificationService->checkRequiredFields()) {
                $whatsAppNotificationService->sendAppointmentStatusNotifications($appointment, true, true);
            }
        }

        $webHookService->process(self::BOOKING_APPROVED, $appointment, [$booking]);
    }
}
