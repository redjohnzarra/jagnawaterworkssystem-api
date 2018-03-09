<?php

namespace App\Http\Controllers;
use App\Models\Consumer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\MonthlyBillController;

class SMSController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->monthlyBillController = new MonthlyBillController();
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
      $apiCode = "TR-GENEV930099_CRF2H"; //Trial API Code with 10 SMS/day .. Register at www.itexmo.com
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

    public function sendSMSBillToAllConsumer(){
      $consumers = Consumer::orderBy('lname')->get();
      $forReturn = [];
      foreach($consumers as $consumer){
        $unpaidMonthlyBills = $this->monthlyBillController->getUnpaidMonthlyBillsObj($consumer->account_no);
        $totalUnpaid = 0;
        foreach($unpaidMonthlyBills as $unpaidBill){
          $totalUnpaid += $unpaidBill->unpaid;
        }
        $message = "Hello ".$consumer->fname." ".$consumer->lname.", your balance as of ".date("m/d/Y")." is Php ".number_format($totalUnpaid, 2, '.', ',').". (Subject to change)";

        if(!empty($consumer->contact_no)){
          try{
            $response = $this->sendSMS($consumer->contact_no, $message);

            $json = json_decode($response, true);
            if ($json == "0") {
              array_push($forReturn, "Message successfully sent to ".$consumer->contact_no." (".$consumer->fname." ".$consumer->lname.")");
            }else{
              array_push($forReturn, "Message sending failed to ".$consumer->contact_no." (".$consumer->fname." ".$consumer->lname."). Error: ".$json);
            }
          }catch(Exception $e){
            array_push($forReturn, "Message sending failed to ".$consumer->contact_no." (".$consumer->fname." ".$consumer->lname.")");
          }
        }
      }

      return response()->json($forReturn);
    }
}
