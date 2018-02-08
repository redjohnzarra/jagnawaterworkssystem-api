<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SMSController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function sendSMSURL(Request $request){
      $userInput = $request->all();
      $sendTo = $userInput['mobile_no'];
      $message = $userInput['message'];

      $result = $this->sendSMS($sendTo, $message);
      $respMsg = "";
      if ($result == ""){
        $respMsg = "iTexMo: No response from server!!!
        Please check the METHOD used (CURL or CURL-LESS). If you are using CURL then try CURL-LESS and vice versa.
        Please CONTACT US for help. ";
      }else if ($result == 0){
        $respMsg = "Message Sent!";
      }
      else{
        $respMsg = "Error Num ". $result . " was encountered!";
      }

      return response()->json([
        'message' => $respMsg
      ]);
    }

    public function sendSMS($sendTo, $message){
      $apiCode = "TR-REDEN478675_NKT67"; //Trial API Code with 10 SMS/day .. Register at www.itexmo.com
      $itextmoURL = "https://www.itexmo.com/php_api/api.php";

      $ch = curl_init();
			$itexmo = array('1' => $sendTo, '2' => $message, '3' => $apiCode);
			curl_setopt($ch, CURLOPT_URL, $itextmoURL);
			curl_setopt($ch, CURLOPT_POST, 1);
			 curl_setopt($ch, CURLOPT_POSTFIELDS,
			          http_build_query($itexmo));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			return curl_exec ($ch);
			curl_close ($ch);
    }
}
