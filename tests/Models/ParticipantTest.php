<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use LMK\Models\Participant;

class ParticipantTest extends TestCase
{

    use DatabaseTransactions;

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

    public function testFitnessData()
    {
        $participant = Participant::create([
            'name' => 'Test'
        ]);

        $participant->fitnessData()->create([
            'date' => new \Carbon\Carbon(),
            'type' => 'steps',
            'amount' => 100
        ]);

        $this->assertEquals(100, $participant->total_steps);
        $this->assertEquals(1, $participant->day_count);
    }
}
