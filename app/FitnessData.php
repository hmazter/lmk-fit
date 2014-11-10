<?php namespace LMK;

use Illuminate\Database\Eloquent\Model;
use LMK\Participant;

class FitnessData extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'fitness_data';

    protected $fillable = ['participant_id' , 'date', 'type', 'amount'];

    public function participant() {
        return $this->belongsTo('LMK\Participant');
    }
}
