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
     * @return \Illuminate\View\View
     */
    public function index(FitnessDataRepository $fitnessDataRepository)
    {
        $weekTop = $fitnessDataRepository->getWeekTop();
        $yesterdayTop = $fitnessDataRepository->getYesterdayTop();

        return view('index')->with(array(
            'participants' => Participant::all(),
            'fitnessData' => $fitnessDataRepository->getStructuredFitnessData(10),
            'weekTop' => $weekTop->getData(),
            'weekTopDates' => $weekTop->getDateString(),
            'yesterdayTop' => $yesterdayTop->getData(),
            'yesterdayTopDates' => $yesterdayTop->getDateString(),
            'last_reload' => FitnessData::max('updated_at')
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
