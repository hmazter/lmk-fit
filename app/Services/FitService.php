<?php

namespace LMK\Services;

use JMS\Serializer\SerializerBuilder;
use GuzzleHttp\Client;
use LMK\Models\Participant;
use LMK\ValueObjects\FitRestApi\DataSourcesResponse;
use LMK\ValueObjects\FitRestApi\DataSetResponse;
use LMK\ValueObjects\FitRestApi\Point;
use LMK\ValueObjects\TimespanNanos;

class FitService
{
    private $baseUrl = 'https://www.googleapis.com/fitness/v1/users/me';
    private $stepSource = 'derived:com.google.step_count.delta:com.google.android.gms:estimated_steps';
    private $timeSource = 'derived:com.google.activity.segment:com.google.android.gms:merge_activity_segments';

    /**
     * Update fitness data for Participant in the given timespan
     *
     * @param Participant $participant
     * @param TimespanNanos $timespanNanos
     * @return array update response, [type => fitness data]
     */
    public function updateFitnessData(Participant $participant, TimespanNanos $timespanNanos)
    {
        return [
            'Steps' => $this->updateStepData($participant, $timespanNanos),
            'Activity' => $this->updateActivityData($participant, $timespanNanos)
        ];
    }

    /**
     * Update step data for Participant in the given timespan
     *
     * @param Participant $participant
     * @param TimespanNanos $timespanNanos
     * @return array    list of fitness data-pairs: [date, amount]
     */
    public function updateStepData(Participant $participant, TimespanNanos $timespanNanos)
    {
        $url = $this->baseUrl . '/dataSources/' . $this->stepSource . '/datasets/' . $timespanNanos->getStart() . '-' . $timespanNanos->getEnd();

        $points = $this->fetchFitnessData($participant, $url);
        $steps = $this->groupStepsDataPerDate($points);

        $rows = [];
        foreach ($steps as $date => $amount) {
            $rows[] = [$date, $amount];

            $participant->fitnessData()->updateOrCreate([
                'date' => $date,
                'type' => 'steps'
            ], [
                'amount' => $amount
            ]);
        }

        return $rows;
    }

    /**
     * Update time activity data for Participant in the given timespan
     *
     * @param Participant $participant
     * @param TimespanNanos $timespanNanos
     * @return array    list of fitness data-pairs: [date, amount]
     */
    public function updateActivityData(Participant $participant, TimespanNanos $timespanNanos)
    {
        $url = $this->baseUrl . '/dataSources/' . $this->timeSource . '/datasets/' . $timespanNanos->getStart() . '-' . $timespanNanos->getEnd();

        $points = $this->fetchFitnessData($participant, $url);
        $activity = $this->groupActivityDataPerDate($points);

        $rows = [];
        foreach ($activity as $date => $amount) {
            $rows[] = [$date, $amount];

            $participant->fitnessData()->updateOrCreate([
                'date' => $date,
                'type' => 'time'
            ], [
                'amount' => $amount
            ]);
        }

        return $rows;
    }

    public function getFitnessDataPoints(Participant $participant, TimespanNanos $timespanNanos, $type = 'steps')
    {
        if ($type == 'steps') {
            $source = $this->stepSource;
        } else {
            $source = $this->timeSource;
        }

        $url = $this->baseUrl . '/dataSources/' . $source . '/datasets/' . $timespanNanos->getStart() . '-' . $timespanNanos->getEnd();

        return $this->fetchFitnessData($participant, $url);
    }

    /**
     * Get fitness data from API from the specified url and Participant
     *
     * @param Participant $participant
     * @param string $url
     * @return array points     list of Point
     */
    private function fetchFitnessData(Participant $participant, $url)
    {
        $restClient = new Client();
        $response = $restClient->get($url, $this->getOauthHeader($participant));
        $data = $response->getBody()->getContents();

        /** @var DataSetResponse $fitResponse */
        $fitResponse = $this->getSerializer()->deserialize($data, \LMK\ValueObjects\FitRestApi\DataSetResponse::class, 'json');

        return $fitResponse->getPoints();
    }

    /**
     * Get a list of all data sources for a Participant
     *
     * @param Participant $participant
     * @return DataSourcesResponse
     */
    public function listDataSources(Participant $participant)
    {
        $restClient = new Client();
        $url = $this->baseUrl . '/dataSources';

        $response = $restClient->get($url, $this->getOauthHeader($participant));
        $parsed = $this->getSerializer()->deserialize($response->getBody()->getContents(), \LMK\ValueObjects\FitRestApi\DataSourcesResponse::class, 'json');

        return $parsed;
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
        $token = json_decode($client->getAccessToken());

        $participant->setAccessToken($token);
    }

    /**
     * Summarize step data from points array and group it by date
     *
     * @param array $points
     * @return array
     */
    private function groupStepsDataPerDate(array $points)
    {
        $data = [];

        /** @var Point $point */
        foreach ($points as $point) {
            $date = $point->getStartDate()->format('Y-m-d');

            if (!isset($data[$date])) {
                $data[$date] = 0;
            }

            $data[$date] += $point->getValueSum();
        }

        return $data;
    }

    /**
     * Summarize activity time per date
     *
     * @param array $points
     * @return array
     */
    private function groupActivityDataPerDate(array $points)
    {
        $data = [];

        /** @var Point $point */
        foreach ($points as $point) {
            if ($point->isActivityMoving()) {
                $date = $point->getStartDate()->format('Y-m-d');

                if (!isset($data[$date])) {
                    $data[$date] = 0;
                }

                $data[$date] += $point->getTimespanLength();
            }
        }

        return $data;
    }

    /**
     * Get the oAuth header for a participant
     * Refresh token if its expired
     *
     * @param Participant $participant
     * @return array
     */
    private function getOauthHeader(Participant $participant)
    {
        if ($participant->isExpiredToken()) {
            $this->refreshToken($participant);
        }

        return [
            'headers' => [
                'Authorization' => 'Bearer ' . $participant->access_token
            ]
        ];
    }

    /**
     * @return \JMS\Serializer\Serializer
     */
    private function getSerializer()
    {
        return SerializerBuilder::create()->build();
    }
}