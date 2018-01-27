<?php

namespace App\Http\Controllers;

use App\Models\Reading;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReadingController extends Controller{
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

  public function updateReading(Request $request, $id){

    	$reading  = Reading::find($id);
    	// $reading->make = $request->input('make');
    	// $reading->model = $request->input('model');
    	// $reading->year = $request->input('year');
    	$reading->save();

    	return response()->json($reading);
	}

	public function deleteReading($id){
    	$reading  = Reading::find($id);
    	$reading->delete();

    	return response()->json('Removed successfully.');
	}
}
