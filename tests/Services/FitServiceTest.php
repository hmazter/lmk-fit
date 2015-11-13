<?php

use LMK\Services\FitService;

class FitServiceTest extends TestCase
{

    public function testInstantiate()
    {
        $fitService = $this->app->make(FitService::class);

        $this->assertInstanceOf(FitService::class, $fitService);
    }
}