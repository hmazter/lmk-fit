<?php

namespace LMK\Http\Controllers;

use LMK\Models\Participant;
use LMK\Services\FitService;

class ParticipantController extends Controller
{

    /**
     * List all participants
     * with average steps per day
     * and controls to reload data
     *
     * @return $this
     */
    public function index()
    {
        $participants = Participant::with('fitnessData')->get();

        return view('participants')->with(compact('participants'));
    }

    /**
     * Reload data from Google Fit server for a participant
     *
     * @param Participant $participant
     * @param FitService $fitService
     * @param string $timeSpan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reload(Participant $participant, FitService $fitService, $timeSpan = 'yesterday')
    {
        $endDate = null;
        if ($timeSpan == 'week') {
            $date = strtotime('-8 day');
            $message = trans('participant.fetched_last_week_data', ['name' => $participant->name]);
        } elseif ($timeSpan == 'today') {
            $date = strtotime('today');
            $endDate = $date;
            $message = trans('participant.fetched_today_data', ['name' => $participant->name]);
        } else {
            $date = strtotime('-1 day');
            $message = trans('participant.fetched_yesterday_data', ['name' => $participant->name]);
        }

        $fitService->updateFitnessData($participant, $date, $endDate);

        return redirect('/participants')->with('message', $message);
    }
}
