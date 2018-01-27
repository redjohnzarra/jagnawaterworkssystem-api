<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyBill extends Model
{
  protected $table = 'monthly_bill';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
  	'service_period_end','account_no','current_reading','previous_reading','consumption','cubic_meter_amt','charges','net_amount','billing_date','due_date','bill_no','meter_no','consumer_type','seniorcitizen_discount','paid','unpaid'
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

  public function payment()
  {
    return $this->hasOne('App\Models\Payment');
  }
}
