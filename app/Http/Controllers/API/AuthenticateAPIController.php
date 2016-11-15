<?php

namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Utils\ResponseUtil;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Validator;

class AuthenticateAPIController extends AppBaseController
{

	public function getSignIn(Request $request) {

		$credentials = $request->only('email', 'password');

        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
            	return Response::json(ResponseUtil::makeError('invalid_credentials'));
            }
        } catch (JWTException $e) {
            // something went wrong
        	return Response::json(ResponseUtil::makeError('could_not_create_token'));
        }

        $loggedInUser = JWTAuth::toUser($token);


        $arrLoggedInUser = $loggedInUser->toArray();
        $arrLoggedInUser['remember_token'] = $token;

        // if no errors are encountered we can return a JWT
        return $this->sendResponse($arrLoggedInUser, 'Logged in successfully');
	}

	public function getReSignIn(Request $request) {

		$remember_token = $request->input('remember_token');
		try {
            $loggedInUser = JWTAuth::toUser($remember_token); 
            $arrLoggedInUser = $loggedInUser->toArray();
    		$arrLoggedInUser['remember_token'] = $remember_token;
    		return $this->sendResponse($arrLoggedInUser, 'Logged in successfully');           
        } catch (\Exception $e) {
	        return Response::json(ResponseUtil::makeError('Token is invalid'));
	    } 

	}

	public function getSignUp(Request $request) {

		$credentials = $request->only('email', 'password', 'name');

		$validator = Validator::make($credentials, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return Response::json(ResponseUtil::makeError('Wrong input fields'));
        }

		try {
		   	$user = User::create([
	            'name' => $credentials['name'],
	            'email' => $credentials['email'],
	            'password' => bcrypt($credentials['password']),
                'phone' => '',
                'address' => '',
                'nation' => '',
				'firebase_token' => ''
        	]);
		} catch (Exception $e) {

		   return Response::json(ResponseUtil::makeError('User already exists'))	;
		}

		return $this->sendResponse(true, 'User created successfully');
	}

}
