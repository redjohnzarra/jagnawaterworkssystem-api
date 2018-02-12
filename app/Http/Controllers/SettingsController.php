<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class SettingsController extends Controller{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      // $this->middleware('auth');
  }

  public function getSettingsObj(){
    $settings = Settings::all();
    if($settings->isEmpty()){
      return null;
    }else{
      return $settings->first();
    }
  }

  public function getSettings(){
    $settings = $this->getSettingsObj();
    return response()->json($settings);
  }

  public function upsertSettings(Request $request){
    $userInput = $request->all();

    $settings = $this->getSettingsObj();
    if($settings){
      foreach($userInput as $key=>$item){
        if($key != 'userid') $settings[$key] = $item;
      }

    	$settings->save();
    }else{
      $settings = Settings::create($userInput);
    }

    return response()->json($settings);
  }
}
