<?php

namespace App\Http\Controllers;

use App\Models\ConsumerType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class ConsumerTypeController extends Controller{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      // $this->middleware('auth');
  }

  public function getConsumerTypes() {
    $objList = ConsumerType::orderBy('name')->get();
    return response()->json($objList);
  }

  public function createConsumerType(Request $request){

    	$consumerType = ConsumerType::create($request->all());

    	return response()->json($consumerType);
	}

  public function updateConsumerType($consumerTypeId, Request $request){
      $userInput = $request->all();
    	$consumerType  = ConsumerType::find($consumerTypeId);
      if(empty($consumerType)){
        return response()->json([
            'message' => "The consumer type with id: {$consumerTypeId} doesn't exist"
          ], 404);
      }else{
        foreach($userInput as $key=>$item){
          $consumerType[$key] = $item;
        }

      	$consumerType->save();

      	return response()->json($consumerType);
      }
	}

	public function deleteConsumerType($consumerTypeId){
    	$consumerType  = ConsumerType::find($consumerTypeId);
    	$consumerType->delete();

    	return response()->json('Removed successfully.');
	}
}
