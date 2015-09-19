<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use LMK\Models\Participant;

class ParticipantTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testCreate()
    {
        Participant::create([
            'name' => 'Test'
        ]);

        $this->assertEquals('Test', Participant::first()->name);
    }
}
