<?php

namespace LMK\Http\Controllers;

use LMK\Models\Participant;
use LMK\Services\FitService;
use LMK\ValueObjects\TimespanNanos;

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
        if ($timeSpan == 'week') {
            $message = trans('participant.fetched_last_week_data', ['name' => $participant->name]);
        } elseif ($timeSpan == 'today') {
            $message = trans('participant.fetched_today_data', ['name' => $participant->name]);
        } else {
            $message = trans('participant.fetched_yesterday_data', ['name' => $participant->name]);
        }

        $timespanNanos = TimespanNanos::createFromStartString($timeSpan);

        $fitService->updateFitnessData($participant, $timespanNanos);

        return redirect('/participants')->with('message', $message);
    }
}
