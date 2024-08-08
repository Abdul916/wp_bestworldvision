<?php

namespace AmeliaBooking\Application\Services\Bookable;

use AmeliaBooking\Domain\Collection\Collection;
use AmeliaBooking\Domain\Common\Exceptions\InvalidArgumentException;
use AmeliaBooking\Domain\Entity\Bookable\Service\Package;
use AmeliaBooking\Infrastructure\Common\Container;
use AmeliaBooking\Infrastructure\Common\Exceptions\QueryExecutionException;
use Exception;
use Slim\Exception\ContainerValueNotFoundException;

/**
 * Class AbstractPackageApplicationService
 *
 * @package AmeliaBooking\Application\Services\Booking
 */
abstract class AbstractPackageApplicationService
{
    /** @var Container $container */
    public $container;

    /**
     * AbstractPackageApplicationService constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param Collection $packageCustomerServices
     *
     * @return boolean
     *
     * @throws ContainerValueNotFoundException
     */
    abstract public function deletePackageCustomer($packageCustomerServices);

    /**
     * @param Collection $appointments
     *
     * @return void
     */
    abstract public function setPackageBookingsForAppointments($appointments);

    /**
     * @param int  $packageCustomerServiceId
     * @param int  $customerId
     * @param bool $isCabinetBooking
     *
     * @return boolean
     */
    abstract public function isBookingAvailableForPurchasedPackage($packageCustomerServiceId, $customerId, $isCabinetBooking);

    /**
     * @param array $params
     *
     * @return array
     */
    abstract public function getPackageStatsData($params);

    /**
     * @param array      $packageDatesData
     * @param Collection $appointmentsPackageCustomerServices
     * @param int        $packageCustomerServiceId
     * @param string     $date
     * @param int        $occupiedDuration
     *
     * @return void
     */
    abstract public function updatePackageStatsData(
        &$packageDatesData,
        $appointmentsPackageCustomerServices,
        $packageCustomerServiceId,
        $date,
        $occupiedDuration
    );

    /**
     * @param Collection $appointments
     *
     * @return Collection
     *
     * @throws Exception
     */
    abstract public function getPackageCustomerServicesForAppointments($appointments);

    /**
     * @param Collection $appointments
     * @param array      $params
     *
     * @return array
     */
    abstract public function getPackageAvailability($appointments, $params);

    /**
     * @return array
     */
    abstract public function getPackagesArray();

    /**
     * @param array $paymentsData
     * @return void
     *
     * @throws InvalidArgumentException
     * @throws QueryExecutionException
     */
    abstract public function setPaymentData(&$paymentsData);

    /**
     * @param Collection $appointments
     * @param Collection $packageCustomerServices
     * @param array $packageData
     *
     * @return void
     */
    abstract protected function fixPurchase($appointments, $packageCustomerServices, $packageData);

    /**
     * @param Collection $packageCustomerServices
     * @param Collection $appointments
     *
     * @return array
     *
     * @throws ContainerValueNotFoundException
     */
    abstract public function getPackageUnusedBookingsCount($packageCustomerServices, $appointments);

    /**
     * @param  array $package
     *
     * @return array
     */
    abstract public function getOnlyOneEmployee($package);
}
