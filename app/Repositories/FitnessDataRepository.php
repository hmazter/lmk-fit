<?php

namespace LMK\Repositories;


use LMK\Models\FitnessData;
use LMK\ValueObjects\FitnessData\TopData;

class FitnessDataRepository
{

    /**
     * Get fitness data group by date and participant
     *
     * @param int $daysBack
     * @return array
     */
    public function getStructuredFitnessData($daysBack = 10) {
        $data = [];
        $rows = FitnessData::
        with('participant')
            ->where('type', '=', 'steps')
            ->where('date', '>=', date('Y-m-d', strtotime('-'.$daysBack.' days')))
            ->has('participant')
            ->get();
        foreach ($rows as $fitnessData) {
            $data[$fitnessData->date][$fitnessData->participant_id] = $fitnessData->amount;
        }
        krsort($data);

        return $data;
    }

    /**
     * Get this weeks top
     *
     * @return TopData
     */
    public function getWeekTop()
    {
        $data = FitnessData::
        with('participant')
            ->selectRaw('fitness_data.*, sum(amount) as total_amount')
            ->where('type', '=', 'steps')
            ->where('date', '>=', date('Y-m-d', strtotime('-7 days')))
            ->where('date', '<', date('Y-m-d'))
            ->has('participant')
            ->groupBy('participant_id')
            ->orderBy('total_amount', 'desc')
            ->get();
        $dates = date('Y-m-d', strtotime('-7 days')) . ' - ' . date('Y-m-d', strtotime('-1 day'));

        return new TopData($data, $dates);
    }

    /**
     * Get Yesterdays top
     *
     * @return TopData
     */
    public function getYesterdayTop()
    {
        $date = date('Y-m-d', strtotime('-1 day'));
        $data = FitnessData::
        with('participant')
            ->where('type', '=', 'steps')
            ->where('date', '=', $date)
            ->has('participant')
            ->orderBy('amount', 'desc')
            ->get();

        return new TopData($data, $date);
    }
}