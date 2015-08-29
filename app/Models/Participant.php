<?php

namespace LMK\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * LMK\Models\Participant
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|FitnessData[] $fitnessData
 * @property-read mixed $total_steps
 * @property-read mixed $day_count
 * @property integer $id
 * @property string $name
 * @property string $picture
 * @property string $refresh_token
 * @property string $access_token
 * @property integer $token_expire
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @method static \Illuminate\Database\Query\Builder|Participant whereId($value)
 * @method static \Illuminate\Database\Query\Builder|Participant whereName($value)
 * @method static \Illuminate\Database\Query\Builder|Participant wherePicture($value)
 * @method static \Illuminate\Database\Query\Builder|Participant whereRefreshToken($value)
 * @method static \Illuminate\Database\Query\Builder|Participant whereAccessToken($value)
 * @method static \Illuminate\Database\Query\Builder|Participant whereTokenExpire($value)
 * @method static \Illuminate\Database\Query\Builder|Participant whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Participant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Participant whereDeletedAt($value)
 */
class Participant extends Model
{
    use SoftDeletes;

    protected $table = 'participants';

    protected $fillable = ['name', 'picture', 'access_token', 'refresh_token', 'token_expire'];

    public function fitnessData()
    {
        return $this->hasMany('LMK\Models\FitnessData');
    }

    public function getTotalStepsAttribute()
    {
        $fitnessData = $this->fitnessData;
        $totalSteps = 0;
        foreach ($fitnessData as $data) {
            $totalSteps += $data->amount;
        }
        return $totalSteps;
    }

    public function getDayCountAttribute()
    {
        return count($this->fitnessData);
    }

    public function isExpiredToken()
    {
        return $this->token_expire < time();
    }

    /**
     * @param \stdClass $token
     */
    public function setAccessToken($token)
    {
        $this->access_token = $token->access_token;
        $this->token_expire = $token->expires_in + $token->created;
        $this->save();
    }
}
