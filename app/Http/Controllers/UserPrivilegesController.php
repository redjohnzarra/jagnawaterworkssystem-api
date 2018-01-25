<?php

namespace App\Http\Controllers;

use App\Models\UserPrivileges;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserPrivilegesController extends Controller{
  public function createUserPrivileges(Request $request){

    	$userPrivileges = UserPrivileges::create($request->all());

    	return response()->json($userPrivileges);
	}

  public function updateUserPrivileges(Request $request, $id){

    	$userPrivileges  = UserPrivileges::find($id);
    	// $userPrivileges->make = $request->input('make');
    	// $userPrivileges->model = $request->input('model');
    	// $userPrivileges->year = $request->input('year');
    	$userPrivileges->save();

    	return response()->json($userPrivileges);
	}

	public function deleteUserPrivileges($id){
    	$userPrivileges  = UserPrivileges::find($id);
    	$userPrivileges->delete();

    	return response()->json('Removed successfully.');
	}

	public function index(){

    	$userPrivilegess  = UserPrivileges::all();

    	return response()->json($userPrivilegess);
	}
}
