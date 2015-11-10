<?php
/**
 * Created by PhpStorm.
 * User: kristoffer
 * Date: 2015-11-10
 * Time: 16:06
 */

namespace LMK\ValueObjects\FitRestApi;

use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;

class DataSource
{
    /**
     * @Type("string")
     * @SerializedName("dataStreamId")
     * @var string
     */
    private $dataStreamId;

    /**
     * @Type("string")
     * @SerializedName("name")
     * @var string
     */
    private $name;

    /**
     * @Type("string")
     * @SerializedName("dataStreamName")
     * @var string
     */
    private $dataStreamName;

    /**
     * @Type("string")
     * @SerializedName("type")
     * @var string
     */
    private $type;

    /**
     * @Type("LMK\ValueObjects\FitRestApi\DataType")
     * @SerializedName("dataType")
     * @var string
     */
    private $dataType;

    /**
     * @return string
     */
    public function getDataStreamId()
    {
        return $this->dataStreamId;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDataStreamName()
    {
        return $this->dataStreamName;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getDataType()
    {
        return $this->dataType;
    }
}