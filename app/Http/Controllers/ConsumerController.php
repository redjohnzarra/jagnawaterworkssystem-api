<?php

namespace App\Http\Controllers;

use App\Models\Consumer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConsumerController extends Controller{

  public function createConsumer(Request $request){

    	$consumer = Consumer::create($request->all());

    	return response()->json($consumer);
	}

  public function updateConsumer(Request $request, $id){

    	$consumer  = Consumer::find($id);
    	// $consumer->make = $request->input('make');
    	// $consumer->model = $request->input('model');
    	// $consumer->year = $request->input('year');
    	$consumer->save();

    	return response()->json($consumer);
	}

	public function deleteConsumer($id){
    	$consumer  = Consumer::find($id);
    	$consumer->delete();

    	return response()->json('Removed successfully.');
	}

	public function index(){

    	$consumers  = Consumer::all();

    	return response()->json($consumers);
	}
}
