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
}