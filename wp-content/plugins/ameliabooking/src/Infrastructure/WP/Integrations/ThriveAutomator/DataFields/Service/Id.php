<?php

namespace AmeliaBooking\Infrastructure\WP\Integrations\ThriveAutomator\DataFields\Service;

use Thrive\Automator\Items\Data_Field;

class Id extends Data_Field
{
    public static function get_id()
    {
        return 'ameliabooking/service_id';
    }

    public static function get_supported_filters()
    {
        return ['number_comparison'];
    }

    public static function get_name()
    {
        return 'Service Id';
    }

    public static function get_description()
    {
        return 'Service Id';
    }

    public static function get_placeholder()
    {
        return 'service_id';
    }

    public static function get_field_value_type()
    {
        return self::TYPE_NUMBER;
    }
}
