<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reading extends Model
{
  protected $table = 'reading';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
  	'service_period_end','account_no','reading_date','read_by','current_reading','previous_reading','meter_number'
  ];

  /**
   * The attributes excluded from the model's JSON form.
   *
   * @var array
   */
  protected $hidden = [''];

  public function consumer()
  {
    return $this->belongsTo('App\Models\Consumer');
  }
}
