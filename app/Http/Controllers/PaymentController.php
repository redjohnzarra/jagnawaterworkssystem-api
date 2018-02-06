<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class PaymentController extends Controller{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      // $this->middleware('auth');
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
      $payments = Payment::where('bill_no', $monthlyBillId)->get();
    }else{
      $payments = Payment::where('bill_no', $monthlyBillId)->whereBetween('payment_date', [$request->input('startDate'), $request->input('endDate')])->get();
    }

    return response()->json($payments);
  }

  public function createPayment(Request $request){

    	$payment = Payment::create($request->all());

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
