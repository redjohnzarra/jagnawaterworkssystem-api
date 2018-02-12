<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

use App\Models\MonthlyBill;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\ConsumerController;
use App\Http\Controllers\MonthlyBillController;

class PaymentController extends Controller{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      // $this->middleware('auth');
      $this->commonController = new CommonController();
      $this->monthlyBillController = new MonthlyBillController();
      $this->consumerController = new ConsumerController();
  }

  public function getPayments(){

    	$payments  = Payment::all();

    	return response()->json($payments);
	}

  public function getPayment($id){

    	$payment  = Payment::find($id);

    	return response()->json($payment);
	}

  public function getPaymentsByAccountNo($accountNo, Request $request){
    if(empty($request->input('startDate')) && empty($request->input('endDate'))){
      $payments = Payment::where('account_no', $accountNo)->get();
    }else{
      $payments = Payment::where('account_no', $accountNo)->whereBetween('payment_date', [$request->input('startDate'), $request->input('endDate')])->get();
    }

    return response()->json($payments);
  }

  public function getPaymentsByMonthlyBill($monthlyBillId, Request $request){
    if(empty($request->input('startDate')) && empty($request->input('endDate'))){
      $payments = Payment::where('bill_id', $monthlyBillId)->get();
    }else{
      $payments = Payment::where('bill_id', $monthlyBillId)->whereBetween('payment_date', [$request->input('startDate'), $request->input('endDate')])->get();
    }

    return response()->json($payments);
  }

  public function createPayment(Request $request){
      $userInput = $request->all();
      $monthlyBill = MonthlyBill::where('bill_no', $userInput['bill_no'])->get()->first();
      if(!empty($userInput['bill_id'])){
        $monthlyBill = $this->monthlyBillController->getMonthlyBillObj($userInput['bill_id']);
      }
      if(!empty($monthlyBill)){
        $penalty = empty($userInput["penalty"])? 0:$userInput["penalty"];
        $monthlyBill["paid"] = $userInput["total_amount"] - $penalty;
        $monthlyBill["unpaid"] = $monthlyBill["net_amount"] - $monthlyBill["paid"];
        $monthlyBill->save();

        $userInput["bill_no"] = $monthlyBill['bill_no'];
      }

      $consumer = $this->consumerController->getConsumerObj($userInput['account_no']);
      if(!empty($consumer)){
        if($monthlyBill["unpaid"] <= 0){
          $consumer['current_balance'] = $consumer['current_balance'] - $monthlyBill["unpaid"];
          $consumer->save();
        }
      }

      $currentDate = date("Y-m-d H:i:s");
      $userInput["payment_date"] = $currentDate;
      $userInput["or_no"] = $this->commonController->orNoGenerator();
      $userInput["or_date"] = $currentDate;
    	$payment = Payment::create($userInput);

    	return response()->json($payment);
	}

  public function updatePayment($id, Request $request){
      $userInput = $request->all();

    	$payment  = Payment::find($id);
      if(empty($payment)){
        return response()->json([
            'message' => "The payment with id: {$id} doesn't exist"
          ], 404);
      }else{
        foreach($userInput as $key=>$item){
          if($key != 'userid') $payment[$key] = $item;
        }

        // $payment->make = $request->input('make');
      	// $payment->model = $request->input('model');
      	// $payment->year = $request->input('year');
      	$payment->save();

        return response()->json($payment);
      }
	}

	public function deletePayment($id){
    	$payment  = Payment::find($id);
      if(empty($payment)){
        return response()->json([
            'message' => "The payment with id: {$id} doesn't exist"
          ], 404);
      }else{
    	   $payment->delete();
      }

    	return response()->json('Removed successfully.');
	}
}
