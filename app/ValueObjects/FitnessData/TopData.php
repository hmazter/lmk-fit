<?php

namespace LMK\ValueObjects\FitnessData;


class TopData
{
    /**
     * The top data
     *
     * @var array
     */
    private $data;

    /**
     * The date string describing the data
     *
     * @var string
     */
    private $dateString;

    /**
     * Constructor
     *
     * @param array $data
     * @param string $dateString
     */
    public function __construct($data, $dateString)
    {
        $this->data = $data;
        $this->dateString = $dateString;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getDateString()
    {
        return $this->dateString;
    }
}