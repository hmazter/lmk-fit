<?php

namespace LMK\ValueObjects\FitRestApi;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;


class Value
{
    /**
     * @Type("integer")
     * @SerializedName("intVal")
     * @var int
     */
    private $intVal;

    /**
     * @Type("double")
     * @SerializedName("fpVal")
     * @var double
     */
    private $fpVal;

    /**
     * @return int
     */
    public function getIntVal()
    {
        return $this->intVal;
    }

    /**
     * @return double
     */
    public function getFpVal()
    {
        return $this->fpVal;
    }
}