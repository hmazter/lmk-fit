<?php

namespace LMK\ValueObjects\FitRestApi;

use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;

class DataSourcesResponse
{
    /**
     * @Type("array<LMK\ValueObjects\FitRestApi\DataSource>")
     * @SerializedName("dataSource")
     * @var array
     */
    private $dataSource = [];

    /**
     * @return array
     */
    public function getDataSource()
    {
        return $this->dataSource;
    }
}