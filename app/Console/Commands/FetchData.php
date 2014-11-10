<?php namespace LMK\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use LMK\FitnessData;
use LMK\Participant;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class FetchData extends Command {


    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lmk:fetch-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch data from Google Fit REST API.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $timespan = $this->argument('timespan');
        $endDate = null;
        if ($timespan == 'week') {
            $date = strtotime('-8 day');
            $this->info('Getting data for a week ending yesterday');
        } elseif ($timespan == 'today') {
            $date = strtotime('today');
            $endDate = $date;
            $this->info('Getting data for today');
        } elseif ($timespan == 'yesterday') {
            $date = strtotime('-1 day');
            $this->info('Getting data for yesterday');
        } else {
            $date = strtotime($timespan);
            $this->info('Getting data for '.$timespan);
        }

        $participants = Participant::all();
        foreach ($participants as $participant) {
            /** @var Participant $participant */
            $this->info($participant->name);
            $structured = $participant->updateFitnessData($date, $endDate);

            $this->info(var_export($structured, true));
        }
    }

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
            array('timespan', InputArgument::OPTIONAL, 'The timespan to get data for', 'yesterday')
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [

		];
	}

}
