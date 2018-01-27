<?php

namespace App\Http\Controllers;

use App\Models\Consumer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConsumerController extends Controller{

  public function getConsumers() {
    $objList = Consumer::orderBy('lname')->get();
    return response()->json($objList);

    // $consumers  = Consumer::all();
    //
    // return response()->json($consumers);
  }

  public function createConsumer(Request $request){

    	$consumer = Consumer::create($request->all());

    	return response()->json($consumer);
	}

  public function updateConsumer($accountNo, Request $request){
      $userInput = $request->all();
    	$consumer  = Consumer::find($accountNo);
      if(empty($consumer)){
        return response()->json('Consumer not found.');
      }else{
        foreach($userInput["items"] as $key=>$item){
          $consumer[$key] = $item;
        }

      	// $consumer->lname = $request->input('lname');
      	// $consumer->fname = $request->input('fname');
      	// $consumer->mname = $request->input('mname');
      	$consumer->save();

      	return response()->json($consumer);
      }
	}

	public function deleteConsumer($accountNo){
    	$consumer  = Consumer::find($accountNo);
    	$consumer->delete();

    	return response()->json('Removed successfully.');
	}
}
