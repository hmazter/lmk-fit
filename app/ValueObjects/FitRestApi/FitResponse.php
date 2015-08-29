<?php

namespace LMK\ValueObjects\FitRestApi;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;


class FitResponse
{

    /**
     * @Type("string")
     * @SerializedName("minStartTimeNs")
     * @var string
     */
    private $minStartTimeNs;

    /**
     * @Type("string")
     * @SerializedName("maxEndTimeNs")
     * @var string
     */
    private $maxEndTimeNs;

    /**
     * @Type("string")
     * @SerializedName("dataSourceId")
     * @var string
     */
    private $dataSourceId;

    /**
     * @Type("string")
     * @SerializedName("nextPageToken")
     * @var string
     */
    private $nextPageToken;

    /**
     * @Type("array<LMK\ValueObjects\FitRestApi\Point>")
     * @SerializedName("point")
     * @var array
     */
    private $points;

    /**
     * @return string
     */
    public function getMinStartTimeNs()
    {
        return $this->minStartTimeNs;
    }

    /**
     * @return string
     */
    public function getMaxEndTimeNs()
    {
        return $this->maxEndTimeNs;
    }

    /**
     * @return string
     */
    public function getDataSourceId()
    {
        return $this->dataSourceId;
    }

    /**
     * @return string
     */
    public function getNextPageToken()
    {
        return $this->nextPageToken;
    }

    /**
     * @return array
     */
    public function getPoints()
    {
        return $this->points;
    }
}