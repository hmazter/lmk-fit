<?php

namespace LMK\ValueObjects\FitRestApi;


use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;

class DataType
{
    /**
     * @Type("string")
     * @SerializedName("name")
     * @var string
     */
    private $name;

    /**
     * @Type("array<LMK\ValueObjects\FitRestApi\Field>")
     * @SerializedName("field")
     * @var array
     */
    private $field;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getField()
    {
        return $this->field;
    }
}