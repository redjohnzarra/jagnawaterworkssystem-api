<?php

namespace App\Http\Controllers;

use App\Models\Reading;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class ReadingController extends Controller{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      // $this->middleware('auth');
  }

  public function getReadings(){

    	$readings  = Reading::all();

    	return response()->json($readings);
	}

  public function getReadingsByAccountNo($accountNo, Request $request){
    if(empty($request->input('startDate')) && empty($request->input('endDate'))){
      $readings = Reading::where('account_no', $accountNo)->get();
    }else{
      $readings = Reading::where('account_no', $accountNo)->whereBetween('reading_date', [$request->input('startDate'), $request->input('endDate')])->get();
    }

    return response()->json($readings);
  }

  public function createReading(Request $request){

    	$reading = Reading::create($request->all());

    	return response()->json($reading);
	}

  public function updateReading($id, Request $request){
      $userInput = $request->all();

    	$reading  = Reading::find($id);
      if(empty($reading)){
        return response()->json([
            'message' => "The reading with id: {$id} doesn't exist"
          ], 404);
      }else{
        foreach($userInput as $key=>$item){
          if($key != 'userid') $reading[$key] = $item;
        }

        // $reading->make = $request->input('make');
      	// $reading->model = $request->input('model');
      	// $reading->year = $request->input('year');
      	$reading->save();

      	return response()->json($reading);
      }
	}

	public function deleteReading($id){
    	$reading  = Reading::find($id);
      if(empty($reading)){
        return response()->json([
            'message' => "The reading with id: {$id} doesn't exist"
          ], 404);
      }else{
    	   $reading->delete();
      }

    	return response()->json('Removed successfully.');
	}
}
