<?php

namespace App\Http\Controllers;

use App\Models\Reading;
use App\Models\MonthlyBill;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

use App\Http\Controllers\MonthlyBillController;
use App\Http\Controllers\ConsumerController;

class ReadingController extends Controller{
  private $monthlyBillController;
  private $consumerController;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      // $this->middleware('auth');

      $this->monthlyBillController = new MonthlyBillController();
      $this->consumerController = new ConsumerController();
  }

  public function getReadings(){

    	$readings  = Reading::all();

    	return response()->json($readings);
	}

  public function getReading($id){
    	$reading  = Reading::find($id);

    	return response()->json($reading);
	}

  public function getReadingsByAccountNo($accountNo, Request $request){
    if(empty($request->input('startDate')) && empty($request->input('endDate'))){
      $readings = Reading::where('account_no', $accountNo)->get();
    }else{
      $readings = Reading::where('account_no', $accountNo)->whereBetween('reading_date', [$request->input('startDate'), $request->input('endDate')])->get();
    }

    return response()->json($readings);
  }

  public function createReading(Request $request){
    $allData = $request->all();
    if(empty($allData["reading_date"])) $allData["reading_date"] = date('Y-m-d H:i:s');

    $reading = Reading::create($allData);

    $accountNo = $allData["account_no"];
    $readDate = $allData["reading_date"];

    $startDate = date('Y-m-01 00:00:00', strtotime($readDate));
    $endDate  = date('Y-m-t 23:59:59', strtotime($readDate));

    $monthlyBills = $this->monthlyBillController->getMonthlyBillsByAccountNoAndDates($accountNo, $startDate, $endDate);
    if($monthlyBills->isEmpty()){
      $monthlyBill = [];
      $monthlyBill['account_no'] = $accountNo;
      $monthlyBill['previous_reading'] = $reading['previous_reading'];
      $monthlyBill['current_reading'] = $reading['current_reading'];
      $this->monthlyBillController->insertMonthlyBill($monthlyBill);
    }else{
      $monthlyBill = $monthlyBills->first();
      $monthlyBill['previous_reading'] = $monthlyBill['current_reading'];
      $monthlyBill['current_reading'] = $reading['current_reading'];

      $monthlyBill->save();
    }

  	return response()->json($reading);
	}

  public function updateReading($id, Request $request){
      $userInput = $request->all();

    	$reading  = Reading::find($id);
      if(empty($reading)){
        return response()->json([
            'message' => "The reading with id: {$id} doesn't exist"
          ], 404);
      }else{
        foreach($userInput as $key=>$item){
          if($key != 'userid') $reading[$key] = $item;
        }

        // $reading->make = $request->input('make');
      	// $reading->model = $request->input('model');
      	// $reading->year = $request->input('year');
      	$reading->save();

      	return response()->json($reading);
      }
	}

	public function deleteReading($id){
    	$reading  = Reading::find($id);
      if(empty($reading)){
        return response()->json([
            'message' => "The reading with id: {$id} doesn't exist"
          ], 404);
      }else{
    	   $reading->delete();
      }

    	return response()->json('Removed successfully.');
	}
}
