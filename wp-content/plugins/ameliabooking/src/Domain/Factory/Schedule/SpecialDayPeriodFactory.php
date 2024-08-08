<?php

namespace AmeliaBooking\Domain\Factory\Schedule;

use AmeliaBooking\Domain\Collection\Collection;
use AmeliaBooking\Domain\Common\Exceptions\InvalidArgumentException;
use AmeliaBooking\Domain\Entity\Schedule\SpecialDayPeriod;
use AmeliaBooking\Domain\ValueObjects\DateTime\DateTimeValue;
use AmeliaBooking\Domain\ValueObjects\Number\Integer\Id;
use AmeliaBooking\Infrastructure\Licence;

/**
 * Class SpecialDayPeriodFactory
 *
 * @package AmeliaBooking\Domain\Factory\Schedule
 */
class SpecialDayPeriodFactory
{
    /**
     * @param array $data
     *
     * @return SpecialDayPeriod
     * @throws InvalidArgumentException
     */
    public static function create($data)
    {
        Licence\DataModifier::periodFactory($data);

        $period = new SpecialDayPeriod(
            new DateTimeValue(\DateTime::createFromFormat('H:i:s', $data['startTime'])),
            new DateTimeValue(\DateTime::createFromFormat('H:i:s', $data['endTime'])),
            new Collection($data['periodServiceList']),
            new Collection($data['periodLocationList'])
        );

        if (isset($data['id'])) {
            $period->setId(new Id($data['id']));
        }

        if (isset($data['locationId'])) {
            $period->setLocationId(new Id($data['locationId']));
        }

        return $period;
    }
}
