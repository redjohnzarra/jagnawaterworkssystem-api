<?php

namespace App\Http\Controllers;

use App\Models\Consumer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class ConsumerController extends Controller{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      // $this->middleware('auth');
  }

  public function getConsumers() {
    $objList = Consumer::orderBy('lname')->get();
    return response()->json($objList);

    // $consumers  = Consumer::all();
    //
    // return response()->json($consumers);
  }

  public function getConsumer($accountNo) {
    $consumer = $this->getConsumerObj($accountNo);
    return response()->json($consumer);
  }

  public function getConsumerObj($accountNo){
    return Consumer::find($accountNo);
  }

  public function createConsumer(Request $request){

    	$consumer = Consumer::create($request->all());

    	return response()->json($consumer);
	}

  public function updateConsumer($accountNo, Request $request){
      $userInput = $request->all();
    	$consumer  = Consumer::find($accountNo);
      if(empty($consumer)){
        return response()->json([
            'message' => "The consumer with account no: {$accountNo} doesn't exist"
          ], 404);
      }else{
        foreach($userInput as $key=>$item){
          if($key != 'userid') $consumer[$key] = $item;
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
      if(empty($consumer)){
        return response()->json([
            'message' => "The consumer with account no: {$accountNo} doesn't exist"
          ], 404);
      }else{
    	   $consumer->delete();
      }

    	return response()->json('Removed successfully.');
	}
}
