<?php namespace LMK\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use LMK\FitnessData;
use LMK\Participant;

class HomeController extends Controller {

	public function index()
	{
        /*
         * Steps table
         */
        $data = [];
        $participants = [];
        $rows = FitnessData::with('participant')->where('type', '=', 'steps')->get();
        foreach ($rows as $fitnessData) {
            $data[$fitnessData->date][$fitnessData->participant_id] = $fitnessData->amount;
            $participants[$fitnessData->participant_id] = $fitnessData->participant;
        }
        krsort($data);

        /*
         * Week top
         */
        $weekTop = FitnessData::
            with('participant')
            ->selectRaw('fitness_data.*, sum(amount) as total_amount')
            ->where('type', '=', 'steps')
            ->where('date', '>=', date('Y-m-d', strtotime('-8 days')))
            ->where('date', '<', date('Y-m-d'))
            ->groupBy('participant_id')
            ->orderBy('total_amount', 'desc')
            ->get();
        $weekTopDates = date('Y-m-d', strtotime('-8 days')).' - '.date('Y-m-d', strtotime('-1 day'));

        /*
         * Yesterday top
         */
        $yesterdayTopDates = date('Y-m-d', strtotime('-1 day'));
        $yesterdayTop = FitnessData::
            with('participant')
            ->where('type', '=', 'steps')
            ->where('date', '=', $yesterdayTopDates)
            ->orderBy('amount', 'desc')
            ->get();

		return view('index')->with(array(
            'participants'  => $participants,
            'fitnessData'   => $data,
            'weekTop'       => $weekTop,
            'weekTopDates'  => $weekTopDates,
            'yesterdayTop'      => $yesterdayTop,
            'yesterdayTopDates' => $yesterdayTopDates,
            'last_reload'   => FitnessData::max('updated_at')
        ));
	}

    public function getAdd()
    {
        $serviceConfig = Config::get('services.fit');
        $url = 'https://accounts.google.com/o/oauth2/auth';
        $options = [
            'response_type' => 'code',
            'client_id'     => $serviceConfig['client_id'],
            'scope'         => 'https://www.googleapis.com/auth/fitness.activity.write https://www.googleapis.com/auth/userinfo.profile',
            'redirect_uri'  => 'http://lmk-fit.hmazter.com/code',
            'access_type'   => 'offline',
            'approval_prompt' => 'force'
        ];

        $url .= '?'.http_build_query($options);

        return redirect($url);
    }

    public function code()
    {
        // Get callback data
        $name = Input::get('state');
        $code = Input::get('code');
        $serviceConfig = Config::get('services.fit');

        // exchange code for access token
        $url = 'https://accounts.google.com/o/oauth2/token';
        $data = [
            'body' => [
                'code' => $code,
                'client_id' => $serviceConfig['client_id'],
                'client_secret' => $serviceConfig['client_secret'],
                'redirect_uri'  => 'http://lmk-fit.hmazter.com/code',
                'grant_type'    => 'authorization_code'
            ]
        ];
        $client = new Client();
        $response = $client->post($url, $data);
        $jsonData = $response->json();


        // get participant name
        $url = 'https://www.googleapis.com/oauth2/v1/userinfo?alt=json';
        $options = [
            'headers' => [
                'Authorization' => 'Bearer '.$jsonData['access_token']
            ]
        ];
        $response = $client->get($url, $options);
        $profileData = $response->json();
        $name = $profileData['name'];
        $picture = $profileData['picture'];

        // save as new participant
        Participant::create([
            'name'          => $name,
            'picture'       => $picture,
            'access_token'  => $jsonData['access_token'],
            'refresh_token' => $jsonData['refresh_token'],
            'token_expire'  => time() + $jsonData['expires_in']
        ]);

        // flash message
        return redirect('/')->with('message', 'added');
    }

    public function about() {
        return view('about');
    }

}
