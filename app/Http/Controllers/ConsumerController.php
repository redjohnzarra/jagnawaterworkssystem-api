<?php

namespace App\Http\Controllers;

use App\Models\Consumer;
use App\Models\UserPrivileges;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use finfo;

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
    foreach($objList as $obj){
      $hasPicture = false;
      $hasSignature = false;
      if($obj->picture != null){
        $hasPicture = true;
      }
      if($obj->signature_of_member != null){
        $hasSignature = true;
      }
      $obj->haspicture = $hasPicture;
      $obj->hassignature_of_member = $hasSignature;
    }
    return response()->json($objList);

    // $consumers  = Consumer::all();
    //
    // return response()->json($consumers);
  }

  public function getConsumer($accountNo) {
    $consumer = $this->getConsumerObj($accountNo);
    $hasPicture = false;
    $hasSignature = false;
    if($consumer->picture != null){
      $hasPicture = true;
    }
    if($consumer->signature_of_member != null){
      $hasSignature = true;
    }
    $consumer->haspicture = $hasPicture;
    $consumer->hassignature_of_member = $hasSignature;
    return response()->json($consumer);
  }

  public function getConsumerObj($accountNo){
    return Consumer::find($accountNo);
  }

  public function createConsumer(Request $request){
      $userInput = $request->all();
      if(empty($userInput['application_date'])) $userInput['application_date'] = date("Y-m-d H:i:s");

    	$consumer = Consumer::create($userInput);

      $username = str_replace(' ', '', $userInput["fname"]).".".str_replace(' ', '', $userInput["mname"]).".".str_replace(' ', '', $userInput["lname"]);
      $password = date("mdY", strtotime($userInput["birth_date"]));
      $userLevel = "consumer";
      $accountNo = $consumer["account_no"];

      $user = UserPrivileges::create([
          'username' => $username,
          'password'=> Hash::make($password),
          'userlevel' => $userLevel,
          'account_no' => $accountNo
      ]);
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

      $consumer["user"] = $user;
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
    $picture = $consumer->picture;
    // $data = base64_decode(substr($picture,23));
    $data = base64_decode($picture); //uncomment if data:image/jpeg;base64,

    // echo '<img src="'.$picture.'"/>';
    return response()->make($data, 200, array(
        'Content-Type' => (new finfo(FILEINFO_MIME))->buffer($data)
    ));
  }

  public function getConsumerSignature($accountNo, Request $request){
    $consumer = Consumer::find($accountNo);

    $signature = $consumer->signature_of_member;
    // $data = base64_decode(substr($signature,23));
    $data = base64_decode($signature); //uncomment if data:image/jpeg;base64,

    // echo '<img src="'.$signature.'"/>';
    // Return the image in the response with the correct MIME type
    return response()->make($data, 200, array(
        'Content-Type' => (new finfo(FILEINFO_MIME))->buffer($data)
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
