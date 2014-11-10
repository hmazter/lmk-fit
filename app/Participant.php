<?php namespace LMK;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Participant extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'participants';

    protected $fillable = ['name', 'picture', 'access_token', 'refresh_token', 'token_expire'];


    public function setAccessToken($token)
    {
        $this->access_token = $token->access_token;
        $this->token_expire = $token->expires_in + $token->created;
        $this->save();
    }

    public function updateFitnessData($startTimestamp, $endTimestamp = null)
    {
        if ($endTimestamp == null) {
            $endTimestamp = strtotime('-1 days');
        }
        if ($this->token_expire < time()) {
            $this->refreshToken();
        }

        $startTime = new \DateTime(date('Y-m-d 00:00:00', $startTimestamp), new \DateTimeZone('UTC'));
        $startTimeNano = $startTime->format('U') * (1000*1000*1000);

        $endTime = new \DateTime(date('Y-m-d 23:59:59', $endTimestamp), new \DateTimeZone('UTC'));
        $endTimeNano = $endTime->format('U') * (1000*1000*1000);

        $restClient = new Client();
        $options = [
            'headers' => [
                'Authorization' => 'Bearer '.$this->access_token
            ]
        ];
        $url = 'https://www.googleapis.com/fitness/v1/users/me/dataSources/derived:com.google.step_count.delta:com.google.android.gms:estimated_steps/datasets/'.$startTimeNano.'-'.$endTimeNano;
        $response = $restClient->get($url, $options);
        $fitnessData = $response->json();
        $steps = [];
        foreach ($fitnessData['point'] as $point) {
            foreach ($point['value'] as $values) {
                foreach ($values as $type => $value) {
                    if($type == 'intVal') {
                        $fitnessDate = date('Y-m-d', intval($point['startTimeNanos'] / (1000*1000*1000)));
                        if (!isset($steps[$fitnessDate])) {
                            $steps[$fitnessDate] = 0;
                        }
                        $steps[$fitnessDate] += intval($value);
                    }
                }
            }
        }

        foreach ($steps as $fitnessDate => $amount) {
            FitnessData::updateOrCreate([
                'participant_id' => $this->id,
                'date' => $fitnessDate,
                'type' => 'steps'
            ], [
                'amount' => $amount
            ]);
        }

        return $steps;
    }

    public function refreshToken()
    {
        $serviceData = Config::get('services.fit');
        $client = new \Google_Client();
        $client->setClientId($serviceData['client_id']);
        $client->setClientSecret($serviceData['client_secret']);
        $client->setAccessType('offline');
        $client->refreshToken($this->refresh_token);
        $token = \GuzzleHttp\json_decode($client->getAccessToken());

        $this->setAccessToken($token);
    }

}
