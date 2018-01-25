<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller{
  public function createPayment(Request $request){

    	$payment = Payment::create($request->all());

    	return response()->json($payment);
	}

  public function updatePayment(Request $request, $id){

    	$payment  = Payment::find($id);
    	// $payment->make = $request->input('make');
    	// $payment->model = $request->input('model');
    	// $payment->year = $request->input('year');
    	$payment->save();

    	return response()->json($payment);
	}

	public function deletePayment($id){
    	$payment  = Payment::find($id);
    	$payment->delete();

    	return response()->json('Removed successfully.');
	}

	public function index(){

    	$payments  = Payment::all();

    	return response()->json($payments);
	}
}
