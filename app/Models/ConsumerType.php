<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsumerType extends Model
{
  protected $table = 'consumer_type';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
  	'name','price'
  ];

  /**
   * The attributes excluded from the model's JSON form.
   *
   * @var array
   */
  protected $hidden = [''];
}
