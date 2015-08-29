<?php

namespace LMK\Services;

use JMS\Serializer\SerializerBuilder;
use GuzzleHttp\Client;
use LMK\Models\Participant;
use LMK\ValueObjects\FitRestApi\FitResponse;
use LMK\ValueObjects\FitRestApi\Point;

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
        $data = $response->getBody()->getContents();

        /** @var FitResponse $fitResponse */
        $fitResponse = $this->getSerializer()->deserialize($data, 'LMK\ValueObjects\FitRestApi\FitResponse', 'json');
        $steps = [];

        /** @var Point $point */
        foreach ($fitResponse->getPoints() as $point) {
            $date = $point->getStartDate()->format('Y-m-d');

            if (!isset($steps[$date])) {
                $steps[$date] = 0;
            }

            $steps[$date] += $point->getValueSum();
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

    /**
     * @return \JMS\Serializer\Serializer
     */
    private function getSerializer() {
        return SerializerBuilder::create()->build();
    }
}