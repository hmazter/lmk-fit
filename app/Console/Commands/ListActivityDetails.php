<?php

namespace LMK\Console\Commands;

use Carbon\CarbonInterval;
use Illuminate\Console\Command;
use LMK\Models\Participant;
use LMK\Services\FitService;
use LMK\ValueObjects\FitRestApi\ActivityTypes;
use LMK\ValueObjects\FitRestApi\DataSource;
use LMK\ValueObjects\FitRestApi\Point;
use LMK\ValueObjects\TimespanNanos;

class ListActivityDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lmk:details
                            {date : Date to get}
                            {--p|participant= : Limit to participant id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * @var FitService
     */
    private $fitService;

    /**
     * Create a new command instance.
     *
     * @param FitService $fitService
     */
    public function __construct(FitService $fitService)
    {
        parent::__construct();

        $this->fitService = $fitService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** @var Participant $participant */
        $participant = Participant::find($this->option('participant'));
        $timespan = TimespanNanos::createFromStartString($this->argument('date'));

        $points = $this->fitService->getFitnessDataPoints($participant, $timespan, 'time');

        $types = [];
        $rows = [];
        /** @var Point $point */
        foreach ($points as $point) {
            $type = $point->getValueSum();
            $rows[] = [
                'start' => $point->getStartDate()->format('Y-m-d H:i:s'),
                'end' => $point->getEndDate()->format('Y-m-d H:i:s'),
                'length' => $point->getTimespanLength(),
                'type' => ActivityTypes::getActivityType($type) . ' (' . $type . ')',
            ];

            if (!isset($types[$type])) {
                $types[$type] = [
                    'type' => ActivityTypes::getActivityType($type) . ' (' . $type . ')',
                    'sum' => 0
                ];
            }

            $types[$type]['sum'] += $point->getTimespanLength();
        }

        $toMinutes = function ($item) {
            $item['sum'] = number_format($item['sum'] / 60, 0, '.', ' ');
            return $item;
        };
        $types = array_map($toMinutes, $types);

        $this->info('Activities');
        $this->table(['Start date', 'End date', 'length (seconds)', 'type'], $rows);

        $this->info('Grouped per type');
        $this->table(['Type', 'Sum (minutes)'], $types);
    }
}
