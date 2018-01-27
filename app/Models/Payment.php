<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
  protected $table = 'payment';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
  	'transaction_no','account_no','bill_no','total_amount','payment_date','penalty','payment_type','or_no','teller','or_date'
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

  public function monthlyBill()
  {
    return $this->belongsTo('App\Models\MonthlyBill');
  }
}
