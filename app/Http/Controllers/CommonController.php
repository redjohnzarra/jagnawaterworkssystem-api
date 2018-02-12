<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class CommonController extends Controller{

  public function generateRandomString($length = 10) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }

      return $randomString;
  }

  public function generateRandomNumbers($length = 10) {
      $characters = '0123456789';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }

      return $randomString;
  }

  public function billNoGenerator() {
    $monYear = date("Ym");
    return $monYear.$this->generateRandomNumbers();
  }

  public function orNoGenerator() {
    return $this->generateRandomNumbers(12);
  }
}
