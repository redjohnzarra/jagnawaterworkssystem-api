<?php

namespace App\Http\Controllers;

use App\Models\MonthlyBill;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MonthlyBillController extends Controller{
  public function createMonthlyBill(Request $request){

    	$monthlyBill = MonthlyBill::create($request->all());

    	return response()->json($monthlyBill);
	}

  public function updateMonthlyBill(Request $request, $id){

    	$monthlyBill  = MonthlyBill::find($id);
    	// $monthlyBill->make = $request->input('make');
    	// $monthlyBill->model = $request->input('model');
    	// $monthlyBill->year = $request->input('year');
    	$monthlyBill->save();

    	return response()->json($monthlyBill);
	}

	public function deleteMonthlyBill($id){
    	$monthlyBill  = MonthlyBill::find($id);
    	$monthlyBill->delete();

    	return response()->json('Removed successfully.');
	}

	public function index(){

    	$monthlyBills  = MonthlyBill::all();

    	return response()->json($monthlyBills);
	}
}
