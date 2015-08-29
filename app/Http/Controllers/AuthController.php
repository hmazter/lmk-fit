<?php

namespace LMK\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use LMK\Models\Participant;

class AuthController extends Controller
{
    public function auth()
    {
        $serviceConfig = config()->get('services.fit');
        $url = 'https://accounts.google.com/o/oauth2/auth';
        $options = [
            'response_type' => 'code',
            'client_id' => $serviceConfig['client_id'],
            'scope' => 'https://www.googleapis.com/auth/fitness.activity.write https://www.googleapis.com/auth/userinfo.profile',
            'redirect_uri' => 'http://lmk-fit.hmazter.com/code',
            'access_type' => 'offline',
            'approval_prompt' => 'force'
        ];

        $url .= '?' . http_build_query($options);

        return redirect($url);
    }

    public function code(Request $request)
    {
        if (!$request->has('code')) {
            return response('Missing parameter: code', 400);
        }

        // Get callback data
        $code = $request->get('code');
        $serviceConfig = config()->get('services.fit');

        // exchange code for access token
        $url = 'https://accounts.google.com/o/oauth2/token';
        $data = [
            'body' => [
                'code' => $code,
                'client_id' => $serviceConfig['client_id'],
                'client_secret' => $serviceConfig['client_secret'],
                'redirect_uri' => 'http://lmk-fit.hmazter.com/code',
                'grant_type' => 'authorization_code'
            ]
        ];
        $client = new Client();
        $response = $client->post($url, $data);
        $jsonData = $response->json();

        // get participant name
        $url = 'https://www.googleapis.com/oauth2/v1/userinfo?alt=json';
        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . $jsonData['access_token']
            ]
        ];
        $response = $client->get($url, $options);
        $profileData = $response->json();

        // save as new participant
        Participant::create([
            'name' => $profileData['name'],
            'picture' => $profileData['picture'],
            'access_token' => $jsonData['access_token'],
            'refresh_token' => $jsonData['refresh_token'],
            'token_expire' => time() + $jsonData['expires_in']
        ]);

        // flash message
        return redirect('/')->with('message', 'added');
    }
}
