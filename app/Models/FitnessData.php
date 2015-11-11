<?php

namespace LMK\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * LMK\Models\FitnessData
 *
 * @property-read Participant $participant
 * @property integer $id
 * @property integer $participant_id
 * @property string $type
 * @property string $date
 * @property integer $amount
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|FitnessData whereId($value)
 * @method static \Illuminate\Database\Query\Builder|FitnessData whereParticipantId($value)
 * @method static \Illuminate\Database\Query\Builder|FitnessData whereType($value)
 * @method static \Illuminate\Database\Query\Builder|FitnessData whereDate($value)
 * @method static \Illuminate\Database\Query\Builder|FitnessData whereAmount($value)
 * @method static \Illuminate\Database\Query\Builder|FitnessData whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|FitnessData whereUpdatedAt($value)
 */
class FitnessData extends Model
{
    const TYPE_STEP = 'steps';

    const TYPE_TIME = 'time';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fitness_data';

    protected $fillable = ['participant_id', 'date', 'type', 'amount'];

    public function participant()
    {
        return $this->belongsTo('LMK\Models\Participant');
    }
}
