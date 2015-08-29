<?php

namespace LMK\Services;

use GuzzleHttp\Client;
use LMK\Models\Participant;

class FitService
{

    public function updateFitnessData(Participant $participant, $startTimestamp, $endTimestamp = null)
    {
        if ($endTimestamp == null) {
            $endTimestamp = strtotime('-1 days');
        }

        if ($participant->isExpiredToken()) {
            $this->refreshToken($participant);
        }

        $startTimeNano = $this->getNanoTimestamp($startTimestamp, 'start');
        $endTimeNano = $this->getNanoTimestamp($endTimestamp, 'end');

        $restClient = new Client();
        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . $participant->access_token
            ]
        ];

        $url = 'https://www.googleapis.com/fitness/v1/users/me/dataSources/derived:com.google.step_count.delta:com.google.android.gms:estimated_steps/'.
            'datasets/' . $startTimeNano . '-' . $endTimeNano;

        $response = $restClient->get($url, $options);
        $fitnessData = $response->json();
        $steps = [];
        if (isset($fitnessData['point'])) {
            foreach ($fitnessData['point'] as $point) {
                foreach ($point['value'] as $values) {
                    foreach ($values as $type => $value) {
                        $fitnessDate = date('Y-m-d', intval($point['startTimeNanos'] / (1000 * 1000 * 1000)));
                        if (!isset($steps[$fitnessDate])) {
                            $steps[$fitnessDate] = 0;
                        }
                        if ($type == 'intVal') {
                            $steps[$fitnessDate] += intval($value);
                        } elseif ($type == 'fpVal') {
                            $steps[$fitnessDate] += doubleval($value);
                        }
                    }
                }
            }
        } elseif (php_sapi_name() == 'cli') {
            return $fitnessData;
        }

        foreach ($steps as $fitnessDate => $amount) {
            $participant->fitnessData()->updateOrCreate([
                'date' => $fitnessDate,
                'type' => 'steps'
            ], [
                'amount' => $amount
            ]);
        }

        return $steps;
    }

    /**
     * Refresh an access token for a participant
     *
     * @param Participant $participant
     */
    public function refreshToken(Participant $participant)
    {
        $serviceData = config()->get('services.fit');
        $client = new \Google_Client();
        $client->setClientId($serviceData['client_id']);
        $client->setClientSecret($serviceData['client_secret']);
        $client->setAccessType('offline');
        $client->refreshToken($participant->refresh_token);
        $token = \GuzzleHttp\json_decode($client->getAccessToken());

        $participant->setAccessToken($token);
    }

    private function getNanoTimestamp($timestamp, $mode)
    {
        if ($mode == 'start') {
            $time = '00:00:00';
        } else {
            $time = '23:59:59';
        }

        $startTime = new \DateTime(date('Y-m-d '.$time, $timestamp), new \DateTimeZone('UTC'));
        return $startTime->format('U') * (1000 * 1000 * 1000);
    }
}