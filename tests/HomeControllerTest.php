<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HomeControllerTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testFrontPage()
    {
        $this->visit('/')
            ->see('LMK Fitness');
    }

    public function testTableStepType()
    {
        $this->visit('/')
            ->see('steg');

        $this->visit('/?type=steps')
            ->see('steg');
    }

    public function testTableTimeType()
    {
        $this->visit('/?type=time')
            ->see('tid');
    }

    public function testTableNonExistingType()
    {
        $this->get('/?type=non-exist');
        $this->assertResponseStatus(500);
    }

    public function testAboutPage()
    {
        $this->visit('/about')
            ->see('Om LMK Fitness');
    }
}
