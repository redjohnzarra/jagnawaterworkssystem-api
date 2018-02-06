<?php

namespace App\Http\Controllers;

use App\Models\MonthlyBill;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class MonthlyBillController extends Controller{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      // $this->middleware('auth');
  }

  public function getMonthlyBills(){

    	$monthlyBills  = MonthlyBill::all();

    	return response()->json($monthlyBills);
	}

  public function getMonthlyBill($id){
    	$monthlyBill  = MonthlyBill::find();

    	return response()->json($monthlyBill);
	}

  public function getMonthlyBillsByAccountNo($accountNo, Request $request){
    if(empty($request->input('startDate')) && empty($request->input('endDate'))){
      $monthlyBills = MonthlyBill::where('account_no', $accountNo)->get();
    }else{
      $monthlyBills = MonthlyBill::where('account_no', $accountNo)->whereBetween('created_at', [$request->input('startDate'), $request->input('endDate')])->get();
    }

    return response()->json($monthlyBills);
  }

  public function createMonthlyBill(Request $request){

    	$monthlyBill = MonthlyBill::create($request->all());

    	return response()->json($monthlyBill);
	}

  public function updateMonthlyBill($id, Request $request){
      $userInput = $request->all();
    	$monthlyBill  = MonthlyBill::find($id);
      if(empty($monthlyBill)){
        return response()->json([
            'message' => "The monthly bill with id: {$id} doesn't exist"
          ], 404);
      }else{
        foreach($userInput as $key=>$item){
          if($key != 'userid') $monthlyBill[$key] = $item;
        }

      	// $monthlyBill->service_period_end = $request->input('service_period_end');
      	// $monthlyBill->account_no = $request->input('account_no');
      	// $monthlyBill->current_reading = $request->input('current_reading');
      	$monthlyBill->save();

      	return response()->json($monthlyBill);
      }
	}

	public function deleteMonthlyBill($id){
    	$monthlyBill  = MonthlyBill::find($id);
      if(empty($monthlyBill)){
        return response()->json([
            'message' => "The monthly bill with id: {$id} doesn't exist"
          ], 404);
      }else{
    	   $monthlyBill->delete();
      }

    	return response()->json('Removed successfully.');
	}
}
