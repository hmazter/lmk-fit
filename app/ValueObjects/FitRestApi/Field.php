<?php

namespace LMK\ValueObjects\FitRestApi;


use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;

class Field
{
    /**
     * @Type("string")
     * @SerializedName("name")
     * @var string
     */
    private $name;

    /**
     * @Type("string")
     * @SerializedName("format")
     * @var string
     */
    private $format;

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
    public function getFormat()
    {
        return $this->format;
    }
}