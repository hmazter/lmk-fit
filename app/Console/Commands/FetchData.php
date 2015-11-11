<?php

namespace LMK\Console\Commands;

use Illuminate\Console\Command;
use LMK\Models\Participant;
use LMK\Services\FitService;
use LMK\ValueObjects\TimespanNanos;

class FetchData extends Command
{
    /**
     * The console command signature; name arguments and options.
     *
     * @var string
     */
    protected $signature = 'lmk:fetch-data
                            {timespan=yesterday : The timespan to get data for; today, yesterday, week or php dateformat}
                            {--p|participant= : Limit to participant id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch data from Google Fit REST API.';

    /**
     * @var FitService $fitService
     */
    protected $fitService;

    /**
     * Create a new command instance.
     *
     * @param FitService $fitService
     */
    public function __construct(FitService $fitService)
    {
        $this->fitService = $fitService;

        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function fire()
    {
        $timespan = TimespanNanos::createFromStartString($this->argument('timespan'));

        $timespanDescription = $timespan->hasDescription() ? $timespan->getDescription() : $this->argument('timespan');
        $this->info('Getting data for ' . $timespanDescription);

        $limit = $this->option('participant');
        if ($limit > 0) {
            $participants = [Participant::find($limit)];
        } else {
            $participants = Participant::all();
        }

        foreach ($participants as $participant) {
            /** @var Participant $participant */
            $this->info($participant->name);
            $sets = $this->fitService->updateFitnessData($participant, $timespan);

            foreach ($sets as $set => $rows) {
                $this->table(['Date', $set], $rows);
            }
        }
    }
}
