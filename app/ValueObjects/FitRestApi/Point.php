<?php

namespace LMK\ValueObjects\FitRestApi;

use Carbon\Carbon;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;


class Point
{

    /**
     * @Type("string")
     * @SerializedName("startTimeNanos")
     * @var string
     */
    private $startTimeNanos; //": "1440826265371236026",

    /**
     * @Type("string")
     * @SerializedName("endTimeNanos")
     * @var string
     */
    private $endTimeNanos; //": "1440826325371236026",

    /**
     * @Type("string")
     * @SerializedName("dataTypeName")
     * @var string
     */
    private $dataTypeName; //": "com.google.step_count.delta",

    /**
     * @Type("string")
     * @SerializedName("originDataSourceId")
     * @var string
     */
    private $originDataSourceId; //": "raw:com.google.step_count.cumulative:LGE:Nexus 5:c8f469a1:Step Counter",

    /**
     * @Type("string")
     * @SerializedName("modifiedTimeMillis")
     * @var string
     */
    private $modifiedTimeMillis; //": "1440827518609"

    /**
     * @Type("array<LMK\ValueObjects\FitRestApi\Value>")
     * @SerializedName("value")
     * @var array
     */
    private $value;

    /**
     * @return string
     */
    public function getStartTimeNanos()
    {
        return $this->startTimeNanos;
    }

    /**
     * Get the start time as a date object
     *
     * @return Carbon
     */
    public function getStartDate()
    {
        return Carbon::createFromTimestamp(intval($this->getStartTimeNanos() / (1000 * 1000 * 1000)));
    }

    /**
     * Get the end time as a date object
     *
     * @return Carbon
     */
    public function getEndDate()
    {
        return Carbon::createFromTimestamp(intval($this->getEndTimeNanos() / (1000 * 1000 * 1000)));
    }

    /**
     * Get the number of seconds between startTimeNano and endTimeNano
     *
     * @return int  number of seconds
     */
    public function getTimespanLength()
    {
        return $this->getStartDate()->diffInSeconds($this->getEndDate(), true);
    }

    /**
     * @return string
     */
    public function getEndTimeNanos()
    {
        return $this->endTimeNanos;
    }

    /**
     * @return string
     */
    public function getDataTypeName()
    {
        return $this->dataTypeName;
    }

    /**
     * @return string
     */
    public function getOriginDataSourceId()
    {
        return $this->originDataSourceId;
    }

    /**
     * @return string
     */
    public function getModifiedTimeMillis()
    {
        return $this->modifiedTimeMillis;
    }

    /**
     * @return Carbon
     */
    public function getModifiedDate()
    {
        return Carbon::createFromTimestamp(intval($this->getModifiedTimeMillis() / (1000)));
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->value;
    }

    /**
     * Get the calculated sum of all values for this data point
     *
     * @return int
     */
    public function getValueSum()
    {
        $sum = 0;
        /** @var Value $value */
        foreach ($this->getValues() as $value) {
            $sum += $value->getIntVal() + $value->getFpVal();
        }

        return $sum;
    }

    public function isActivityMoving()
    {
        $exclude = [
            0, // In vehicle
            3, // Still (not moving)
            45, // Meditation
            72, // Sleeping
            109, // Light sleep
            110, // Deep sleep
            111, // REM sleep
            112, // Awake (during sleep cycle)
        ];
        /** @var Value $value */
        foreach ($this->getValues() as $value) {
            if (in_array($value->getIntVal(), $exclude)) {
                return false;
            }
        }

        return true;
    }
}