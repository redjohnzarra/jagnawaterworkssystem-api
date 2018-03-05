<?php

namespace App\Http\Controllers;

use App\Models\Reading;
use App\Models\MonthlyBill;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

use App\Http\Controllers\MonthlyBillController;
use App\Http\Controllers\ConsumerController;
use App\Http\Controllers\ConsumerTypeController;
use App\Http\Controllers\SettingsController;

class ReadingController extends Controller{
  private $monthlyBillController;
  private $consumerController;
  private $consumerTypeController;
  private $settingsController;
  private $settings;

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
      $this->consumerTypeController = new ConsumerTypeController();
      $this->settingsController = new SettingsController();

      $this->settings = $this->settingsController->getSettingsObj();
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
      $readings = Reading::where('account_no', $accountNo)->orderBy('created_at', 'ASC')->get();
    }else{
      $readings = Reading::where('account_no', $accountNo)->whereBetween('reading_date', [$request->input('startDate'), $request->input('endDate')])->orderBy('created_at', 'ASC')->get();
    }

    return response()->json($readings);
  }

  public function getCurrentMonthReadingsByAccountNo($accountNo){
    $startDate = date('Y-m-01 00:00:00');
    $endDate  = date('Y-m-t 23:59:59');

    $readings = Reading::where('account_no', $accountNo)->whereBetween('reading_date', [$startDate, $endDate])->get();

    return $readings;
  }

  public function getLastReadingByAccountNo($accountNo){
    $readings = Reading::where('account_no', $accountNo)->orderBy('created_at', 'ASC')->get();
    $reading = null;
    if($readings->isEmpty()){
    }else{
      $reading = $readings->last();
    }

    return $reading;
  }

  public function createReading(Request $request){
    $allData = $request->all();
    if(empty($allData["reading_date"])) $allData["reading_date"] = date('Y-m-d H:i:s');

    $accountNo = $allData["account_no"];
    $readDate = $allData["reading_date"];

    $settings = $this->settings;
    $billDueDate = date('Y-m-t 17:00:00');
    $servicePeriod = '';

    $startDate = date('Y-m-01 00:00:00', strtotime($readDate));
    $endDate  = date('Y-m-t 23:59:59', strtotime($readDate));

    if($settings){
      $currentDay = date('Y-m-d H:i:s');
      $dueDate = date('Y-m-'.$settings['due_date_day'].' '.$settings['due_date_time']);
      $servicePeriod = empty($settings['service_period'])?"":strval($settings['service_period']);
      if(strtotime($dueDate) > strtotime($currentDay)){
        $billDueDate = $dueDate;
      }else{
        $nextMonthDue = date('Y-m-d', strtotime($dueDate.'+1 month'));
        $billDueDate = date('Y-m-d H:i:s', strtotime($nextMonthDue));
      }

      $startDate = date('Y-m-d H:i:s', strtotime($billDueDate.'-1 month'));
      $endDate = $billDueDate;
    }

    $lastRead = $this->getLastReadingByAccountNo($accountNo);
    $prevReading = 0;
    if($lastRead){
      $prevReading = $lastRead['current_reading'];
    }

    $allData['service_period_end'] = empty($allData['service_period_end'])? $servicePeriod:$allData['service_period_end'];

    $reading = Reading::create($allData);

    $monthlyBills = $this->monthlyBillController->getMonthlyBillsByAccountNoAndDates($accountNo, $startDate, $endDate);
    $consumer = $this->consumerController->getConsumerObj($accountNo);
    $consumerType = $this->consumerTypeController->getConsumerTypeObj($consumer["consumer_type"]);
    $cubicMeterAmt = 0;
    if(!empty($consumerType)){
      $cubicMeterAmt = $consumerType["price"];
    }

    $monthlyBillUnpaid = null;
    foreach($monthlyBills as $mb){
      if($mb["unpaid"] > 0){
        $monthlyBillUnpaid = $mb;
      }
    }

    $charges = 0;
    if($monthlyBills->isEmpty() || $monthlyBillUnpaid == null){
      if(!empty($consumer)){
        if(!empty($allData['$charges'])) $charges = $allData['$charges'];
        $monthlyBill = [];
        $monthlyBill['account_no'] = $accountNo;
        $monthlyBill['previous_reading'] = $prevReading;
        $monthlyBill['current_reading'] = $reading['current_reading'];
        $monthlyBill['consumption'] = $reading['current_reading'] - $prevReading;
        $monthlyBill['cubic_meter_amt'] = $cubicMeterAmt;
        $monthlyBill['billing_date'] = date('Y-m-d H:i:s');
        $monthlyBill['charges'] = $charges;
        $monthlyBill['net_amount'] = $monthlyBill['consumption']*($monthlyBill['cubic_meter_amt']) + $monthlyBill['charges'];
        $monthlyBill['paid'] = 0;
        $monthlyBill['unpaid'] = $monthlyBill['net_amount'] - $monthlyBill['paid'];
        $monthlyBill['due_date'] = $billDueDate;
        $monthlyBill['service_period_end'] = $servicePeriod;
        $monthlyBill['meter_no'] = $consumer["meter_number"];
        $this->monthlyBillController->insertMonthlyBill($monthlyBill);
      }
    }else{
      $monthlyBill = $monthlyBillUnpaid;

      // $monthlyBill['previous_reading'] = $monthlyBill['current_reading'];
      $monthlyBill['current_reading'] = $reading['current_reading'];
      if($monthlyBill["cubic_meter_amt"] == 0){
        $monthlyBill['cubic_meter_amt'] = $cubicMeterAmt;
      }

      $monthlyBill['consumption'] = $monthlyBill['current_reading'] - $monthlyBill['previous_reading'];
      if(!$monthlyBill["billing_date"]){
        $monthlyBill['billing_date'] = date('Y-m-d H:i:s');
      }

      $monthlyBill['net_amount'] = $monthlyBill['consumption']*($monthlyBill['cubic_meter_amt']) + $monthlyBill['charges'];
      $monthlyBill['unpaid'] = $monthlyBill['net_amount'] - $monthlyBill['paid'];

      $monthlyBill['due_date'] = $billDueDate;
      $monthlyBill['service_period_end'] = $servicePeriod;
      $monthlyBill['meter_no'] = $consumer["meter_number"];

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
