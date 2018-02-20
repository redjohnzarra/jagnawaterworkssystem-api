<?php

namespace App\Http\Controllers;

use App\Models\MonthlyBill;
use App\Models\Consumer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\ConsumerController;

class MonthlyBillController extends Controller{
  private $commonController;
  private $consumerController;
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      // $this->middleware('auth');
      $this->commonController = new CommonController();
      $this->consumerController = new ConsumerController();
  }

  public function getMonthlyBills(){

    	$monthlyBills  = MonthlyBill::all();

    	return response()->json($monthlyBills);
	}

  public function getMonthlyBill($id){
    	$monthlyBill  = $this->getMonthlyBillObj($id);

    	return response()->json($monthlyBill);
	}

  public function getMonthlyBillObj($id){
    	$monthlyBill  = MonthlyBill::find($id);

    	return $monthlyBill;
	}


  public function getMonthlyBillsByAccountNo($accountNo, Request $request){
    if(empty($request->input('startDate')) && empty($request->input('endDate'))){
      $monthlyBills = MonthlyBill::where('account_no', $accountNo)->get();
    }else{
      $start = $request->input('startDate');
      $end = $request->input('endDate');
      $monthlyBills = $this->getMonthlyBillsByAccountNoAndDates($accountNo, $start, $end);
    }

    return response()->json($monthlyBills);
  }

  public function getUnpaidMonthlyBillsByAccountNo($accountNo, Request $request){
    $unpaidMonthlyBills = $this->getUnpaidMonthlyBillsObj($accountNo);
    return response()->json($unpaidMonthlyBills);
  }

  public function getUnpaidMonthlyBillsObj($accountNo){
    $unpaidMonthlyBills = MonthlyBill::whereRaw('account_no = ? and unpaid > 0', [$accountNo])->get();

    foreach($unpaidMonthlyBills as $key=>$umb){
      if(strtotime('+2 day') > strtotime($umb['due_date'])){
        $umb["penalty"] = 0.02 * ($umb['net_amount']);
        $unpaidMonthlyBills[$key] = $umb;
      }
    }

    return $unpaidMonthlyBills;
  }

  public function consumersInquiry(Request $request){
    $userInput = $request->all();
    $account_no = !empty($userInput['account_no'])?$userInput['account_no']:'';
    $lname = !empty($userInput['lname'])?$userInput['lname']:'';
    $fname = !empty($userInput['fname'])?$userInput['fname']:'';
    $mname = !empty($userInput['mname'])?$userInput['mname']:'';
    $users = Consumer::whereRaw('account_no = ? and lname = ? and fname = ? and mname = ? ', [$account_no, $lname, $fname, $mname])->get();
    $forReturn = [];
    if(!empty($users)){
      $user = $users->first();
      $forReturn['unpaid'] = $this->getUnpaidMonthlyBillsObj($user->account_no);
      $forReturn['fullname'] = $user->lname.', '.$user->fname.' '.$user->mname;
    }

    return response()->json($forReturn);
  }

  public function getMonthlyBillsByAccountNoAndDates($accountNo, $startDate, $endDate){
    $monthlyBills = MonthlyBill::where('account_no', $accountNo)->whereBetween('created_at', [$startDate, $endDate])->orderBy('created_at', 'DESC')->get();
    // $month

    return $monthlyBills;
  }

  public function createMonthlyBill(Request $request){
      $userInput = $request->all();

      $monthlyBill = $this->insertMonthlyBill($userInput);
    	return response()->json($monthlyBill);
	}

  public function insertMonthlyBill($userInput){
    $consumer = $this->consumerController->getConsumerObj($userInput['account_no']);
    if(!empty($consumer)){
      if(empty($userInput['bill_no'])){
        $userInput['bill_no'] = $this->commonController->billNoGenerator();
      }

      if(empty($userInput['consumer_type'])) $userInput['consumer_type'] = $consumer->consumer_type;
    }

    return MonthlyBill::create($userInput);
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
