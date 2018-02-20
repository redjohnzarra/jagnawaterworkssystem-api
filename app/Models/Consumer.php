<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consumer extends Model
{
  protected $table = 'consumer';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
  	'lname','fname','mname','address','birth_date','municipality','barangay','citizenship','status','sex','orno_appfee','application_date','appfee','signature_of_member','picture','consumer_type','connection_date','meter_number','contact_no'
  ];

  protected $primaryKey = 'account_no';

  /**
   * The attributes excluded from the model's JSON form.
   *
   * @var array
   */
  protected $hidden = ['picture','signature_of_member'];

  public function readings(){
    return $this->hasMany('App\Models\Reading');
  }

  public function monthlyBills(){
    return $this->hasMany('App\Models\MonthlyBill');
  }

  public function payments(){
    return $this->hasMany('App\Models\Payment');
  }

  public function consumerType()
  {
    return $this->belongsTo('App\Models\ConsumerType');
  }
}
