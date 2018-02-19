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
      $userInput = $request->all();
      if(empty($userInput['application_date'])) $userInput['application_date'] = date("Y-m-d H:i:s");

    	$consumer = Consumer::create($userInput);

      // $hasChanges = false;
      // if($userInput['picture']){
      //   $picture = $this->imageForSaving($userInput['picture']);
      //   $consumer->picture = $picture;
      //   $hasChanges = true;
      // }
      //
      // if($userInput['signature_of_member']){
      //   $signature = $this->imageForSaving($userInput['signature_of_member']);
      //   $consumer->signature_of_member = $signature;
      //   $hasChanges = true;
      // }
      //
      // if($hasChanges) $consumer->save();

    	return response()->json($consumer);
	}

  public function imageForSaving($image){
    // Get the file from the request
    $file = $image;

    // Get the contents of the file
    $contents = $file->openFile()->fread($file->getSize());

    return $contents;
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
          // if($key == 'picture' || $key == 'signature_of_member'){
          //   $image = $this->imageForSaving($userInput[$key]);
          //   $consumer[$key] = $image;
          // }
        }

      	// $consumer->lname = $request->input('lname');
      	// $consumer->fname = $request->input('fname');
      	// $consumer->mname = $request->input('mname');
      	$consumer->save();

      	return response()->json($consumer);
      }
	}

  public function getConsumerPicture($accountNo, Request $request){
    $consumer = Consumer::find($accountNo);

    // Return the image in the response with the correct MIME type
    return response()->make($consumer->picture, 200, array(
        'Content-Type' => (new finfo(FILEINFO_MIME))->buffer($consumer->picture)
    ));
  }

  public function getConsumerSignature($accountNo, Request $request){
    $consumer = Consumer::find($accountNo);

    // Return the image in the response with the correct MIME type
    return response()->make($consumer->signature_of_member, 200, array(
        'Content-Type' => (new finfo(FILEINFO_MIME))->buffer($consumer->signature_of_member)
    ));
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
