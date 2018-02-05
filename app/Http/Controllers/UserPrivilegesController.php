<?php

namespace App\Http\Controllers;

use App\Models\UserPrivileges;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserPrivilegesController extends Controller{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      // $this->middleware('auth:api');
  }

  public function createUserPrivileges(Request $request){
      // $this->validateRequest($request);
      $input = $request->all();

      $rules = [
          'username' => 'required|unique:user_privileges',
          'password' => 'required',
      ];

      $messages = [
        // 'required' => 'The :attribute field is required',
        // 'unique' => 'User already exists. Username must be unique'
      ];

      $validator = Validator::make($input, $rules, $messages);

      //if validation fails
      if ($validator->fails()) {
        return array(
            'error' => true,
            'message' => $validator->errors()->all()
        );
      }

      $user = UserPrivileges::create([
          'username' => $request->get('username'),
          'password'=> Hash::make($request->get('password'))
      ]);

      return response()->json(['data' => "The user with id: {$user->id} has been created"], 201);
	}

  public function updateUserPrivileges(Request $request, $id){
      $user = UserPrivileges::find($id);

      if(!$user){
          return response()->json(['message' => "The user with id: {$id} doesn't exist"], 404);
      }

      $input = $request->all();

      $rules = [
          'username' => 'required|unique:user_privileges',
          'password' => 'required',
      ];

      $messages = [
        // 'required' => 'The :attribute field is required',
        // 'unique' => 'User already exists. Username must be unique'
      ];

      $validator = Validator::make($input, $rules, $messages);

      //if validation fails
      if ($validator->fails()) {
        return array(
            'error' => true,
            'message' => $validator->errors()->all()
        );
      }

      $user = UserPrivileges::create([
          'username' => $request->get('username'),
          'password'=> Hash::make($request->get('password'))
      ]);

      $user->username        = $request->get('username');
      $user->password     = Hash::make($request->get('password'));
      $user->save();

      return response()->json(['data' => "The user with id: {$user->id} has been updated"], 200);

	}

	public function deleteUserPrivileges($id){
    	$user  = UserPrivileges::find($id);
      if(!$user){
          return response()->json(['message' => "The user with id: {$id} doesn't exist"], 404);
      }
    	$user->delete();

    	return response()->json('Removed successfully.');
	}

  public function getUser($id){
      $user = UserPrivileges::find($id);
      if(!$user){
          return response()->json(['message' => "The user with {$id} doesn't exist"], 404);
      }
      return response()->json($user);
  }

	public function getAllUsers(){

    	$userPrivilegess  = UserPrivileges::all();

    	return response()->json($userPrivilegess);
	}

  /**

   * Display a listing of the resource.

   *

   * @return \Illuminate\Http\Response

   */

  public function authenticate(Request $request)
  {
    $validator = Validator::make($request->all(), [
        'username' => 'required',
        'password' => 'required'
    ]);

    if ($validator->fails()) {
        return array(
            'error' => true,
            'message' => $validator->errors()->all()
        );
    }

    $user = UserPrivileges::where('username', $request->input('username'))->first();

    if (count($user)) {
        if (password_verify($request->input('password'), $user->password)) {
            unset($user->password);
            $apikey = base64_encode(str_random(40));
            UserPrivileges::where('username', $request->input('username'))->update(['api_key' => "$apikey"]);;

            return response()->json(['error' => false,'api_key' => $apikey,'user' => $user]);
            // return array('error' => false, 'user' => $user);
        } else {
            return array('error' => true, 'message' => 'Invalid password');
        }
    } else {
        return array('error' => true, 'message' => 'User does not exist');
    }

  }

  public function validateRequest(Request $request){
      $input = $request->all();

      $rules = [
          'username' => 'required|unique:user_privileges',
          'password' => 'required',
      ];

      $messages = [
        // 'required' => 'The :attribute field is required',
        // 'unique' => 'User already exists. Username must be unique'
      ];

      $validator = Validator::make($input, $rules, $messages);

      //if validation fails
      if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
          // return array(
          //     'error' => true,
          //     'message' => $validator->errors()->all()
          // );
      }
  }
}
