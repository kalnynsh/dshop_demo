<?php

namespace shop\services;

class DateTimeService
{
    protected static $defaultTimezone = null;

    /**
     * @param string|float|int $max
     * @return int|false
     */
    protected static function getTimeStamp($value = 'now')
    {
        if (is_numeric($value)) {
            return (int) $value;
        }

        if ($value instanceof \DateTime) {
            return $value->getTimestamp();
        }

        return strtotime(empty($value) ? 'now' : $value);
    }

    /**
     * Get a timestamp Unix Time
     *
     * @param \DateTime|int|string $value numeric timestamp or \DateTime, default to "now"
     * @return int
     *
     * @example 1061306726
     */
    public static function unixTime($value = 'now')
    {
        return static::getTimeStamp($value);
    }

    /**
     * Get a datetime object for given $value, default now
     *
     * @param \DateTime|int|string $value timestamp, default to "now"
     * @param string $timezone time zone in which the date time should be set, default to DateTime::$defaultTimezone, if set, otherwise the result of `date_default_timezone_get`
     * @example DateTime('2005-08-16 20:39:21')
     * @return \DateTime
     * @see http://php.net/manual/en/timezones.php
     * @see http://php.net/manual/en/function.date-default-timezone-get.php
     */
    public static function dateTime($value = 'now', $timezone = null)
    {
        return static::setTimezone(
            new \DateTime('@' . static::unixTime($value)),
            $timezone
        );
    }

    /**
     * get a date string formatted with ISO8601
     *
     * @param \DateTime|int|string $value timestamp, default to "now"
     * @return string
     * @example '2003-10-21T16:05:52+0000'
     */
    public static function iso8601($value = 'now')
    {
        return static::date(\DateTime::ISO8601, $value);
    }

    /**
     * Get a date string between January 1, 1970 and now
     *
     * @param string               $format
     * @param \DateTime|int|string $value    value timestamp, default to "now"
     * @return string
     * @example '2008-11-27'
     */
    public static function date($format = 'Y-m-d', $value = 'now')
    {
        return static::dateTime($value)->format($format);
    }

    /**
     * Get a time string (24h format by default)
     *
     * @param string               $format
     * @param \DateTime|int|string $value    value timestamp, default to "now"
     * @return string
     * @example '15:02:34'
     */
    public static function time($format = 'H:i:s', $value = 'now')
    {
        return static::dateTime($value)->format($format);
    }

    /**
     * @return string
     * @example 'Europe/Moscow'
     */
    public static function setTimezoneMoscow()
    {
        return static::setDefaultTimezone('Europe/Moscow');
    }

    /**
     * Internal method to set the time zone on a DateTime.
     *
     * @param \DateTime $dt
     * @param string|null $timezone
     *
     * @return \DateTime
     */
    private static function setTimezone(\DateTime $dt, $timezone)
    {
        return $dt->setTimezone(new \DateTimeZone(static::resolveTimezone($timezone)));
    }

    /**
     * Sets default time zone.
     *
     * @param string $timezone
     *
     * @return void
     */
    public static function setDefaultTimezone($timezone = null)
    {
        static::$defaultTimezone = $timezone;
    }

    /**
     * Gets default time zone.
     *
     * @return string|null
     */
    public static function getDefaultTimezone()
    {
        return static::$defaultTimezone;
    }

    /**
     * @param string|null $timezone
     * @return null|string
     */
    private static function resolveTimezone($timezone)
    {
        return (
            (null === $timezone) ? (
                (null === static::$defaultTimezone) ? date_default_timezone_get() : static::$defaultTimezone)
                    : $timezone
        );
    }

    /**
     * Check if give date in range.
     *
     * @return bool
     */
    public static function checkDateInRange(
        $dateFrom,
        $dateTo,
        $date = 'now'
    ): bool {
        $unixFrom = static::getTimeStamp($dateFrom);
        $unixTo = static::getTimeStamp($dateTo);
        $unixDate = static::getTimeStamp($date);

        if ($unixTo < $unixFrom) {
            list($unixTo, $unixFrom) = [$unixFrom, $unixTo];
        }

        return $unixDate <= $unixTo && $unixDate >= $unixFrom;
    }
}
