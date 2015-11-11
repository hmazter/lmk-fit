<?php

namespace LMK\ValueObjects;

use LMK\Exceptions\InvalidNanoTimestamp;

class TimespanNanos
{
    /**
     * @var string
     */
    private $start;

    /**
     * @var string
     */
    private $end;

    /**
     * @var string
     */
    private $description;

    /**
     * Timespan constructor.
     *
     * @param string $start
     * @param string $end
     * @param string|null $description
     * @throws InvalidNanoTimestamp
     */
    public function __construct($start, $end, $description = null)
    {
        if (!is_int($start) || strlen($start) != 19) {
            throw new InvalidNanoTimestamp('Start is not a valid nano timestamp');
        }
        if (!is_int($end) || strlen($end) != 19) {
            throw new InvalidNanoTimestamp('End is not a valid nano timestamp');
        }

        $this->start = (string)$start;
        $this->end = (string)$end;
        $this->description = $description;
    }

    /**
     * Create a nanosecond timespan based on start string.
     *
     * @param string $start start string: today, yesterday, week or php date string
     * @return TimespanNanos
     */
    public static function createFromStartString($start)
    {
        $description = null;
        switch ($start) {
            case 'week':
                $start = strtotime('-8 day');
                $end = strtotime('-1 day');
                $description = 'week ending yesterday';
                break;

            case 'today':
                $end = $start = strtotime('today');
                $description = 'today';
                break;

            case 'yesterday':
                $end = $start = strtotime('-1 day');
                $description = 'yesterday';
                break;

            default:
                $end = $start = strtotime($start);
        }

        return new static(
            static::getNanoTimestamp($start, 'start'),
            static::getNanoTimestamp($end, 'end'),
            $description
        );
    }

    /**
     * Create a nano timestamp of a date timestamp
     *
     * @param int $timestamp unix timestamp of date
     * @param string $mode start or end of day
     * @return string
     */
    public static function getNanoTimestamp($timestamp, $mode)
    {
        if ($mode == 'start') {
            $time = '00:00:00';
        } else {
            $time = '23:59:59';
        }

        $startTime = new \DateTime(date('Y-m-d ' . $time, $timestamp), new \DateTimeZone('UTC'));
        return $startTime->format('U') * (1000 * 1000 * 1000);
    }

    /**
     * @return string
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @return string
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return bool
     */
    public function hasDescription()
    {
        return $this->description != null;
    }
}