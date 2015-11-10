<?php

namespace LMK\Console\Commands;

use Illuminate\Console\Command;
use LMK\Models\Participant;
use LMK\Services\FitService;
use LMK\ValueObjects\FitRestApi\DataSource;

class ListDataSources extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lmk:list
                            {--p|participant= : Limit to participant id}
                            {--l|limit= : Limit length of row}';

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
        $participant = Participant::all()->first();
        if ($this->option('participant') > 0) {
            $participant = Participant::find($this->option('participant'));
        }

        $reponse = $this->fitService->listDataSources($participant);

        $rows = [];
        /** @var DataSource $source */
        foreach ($reponse->getDataSource() as $source) {
            $streamId = $this->option('limit') ?
                str_limit($source->getDataStreamId(), $this->option('limit')) :
                $source->getDataStreamId();

            $rows[] = [
                $source->getDataStreamName(),
                $streamId
            ];
        }

        $this->info('Datasources for '. $participant->name);
        $this->table(['Name', 'Id'], $rows);
    }
}
