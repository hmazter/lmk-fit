<?php

namespace LMK\Http\Controllers;

use LMK\Models\FitnessData;
use LMK\Models\Participant;
use LMK\Repositories\FitnessDataRepository;

class HomeController extends Controller
{
    /**
     * Show the home page with steps table and top lists
     *
     * @param FitnessDataRepository $fitnessDataRepository
     * @param $type
     * @return \Illuminate\View\View
     */
    public function index(FitnessDataRepository $fitnessDataRepository, $type = FitnessData::TYPE_STEP)
    {
        $allowedTypes = [FitnessData::TYPE_TIME, FitnessData::TYPE_STEP];
        if (!in_array($type, $allowedTypes)) {
            abort(500, 'Invalid fitness type');
        }

        $weekTop = $fitnessDataRepository->getWeekTop($type);
        $yesterdayTop = $fitnessDataRepository->getYesterdayTop($type);

        return view('index')->with(array(
            'participants' => Participant::all(),
            'fitnessData' => $fitnessDataRepository->getStructuredFitnessData($type, 10),
            'weekTop' => $weekTop->getData(),
            'weekTopDates' => $weekTop->getDateString(),
            'yesterdayTop' => $yesterdayTop->getData(),
            'yesterdayTopDates' => $yesterdayTop->getDateString(),
            'last_reload' => FitnessData::max('updated_at'),
            'type' => $type,
        ));
    }

    /**
     * Show the about page
     *
     * @return \Illuminate\View\View
     */
    public function about()
    {
        return view('about');
    }
}
