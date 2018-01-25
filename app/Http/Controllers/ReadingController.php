<?php

namespace App\Http\Controllers;

use App\Models\Reading;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReadingController extends Controller{
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

	public function index(){

    	$readings  = Reading::all();

    	return response()->json($readings);
	}
}
