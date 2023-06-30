<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Hash;

use Illuminate\Support\Facades\Crypt;
use App\Models\OrganizationUser;
use App\Models\Organisationuserrequest;

use Mail;
use Illuminate\Support\Facades\Crypt as LaravelCrypt;

class UserController extends Controller
{
    function get_logged_in_user()
    {
        $user = auth('sanctum')->user(); //Get Logged in user details
        
        $response = [
            'status' => 'success',
            'user' => $user
            ];
        return response()->json($response, 200); 
    }
    function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required'],
            'password' => ['required'],
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()
            ], 400);
        } else {

            $user= User::where('email', $request->email)->first();
            if($user->status > 0)
            {
                if (!$user || !Hash::check($request->password, $user->password)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => ['These credentials do not match our records.']
                    ], 404);
                }
                //Update last login
                $user->last_login = date('Y-m-d H:i:s');
                $user->save();
                $token = $user->createToken('my-app-token')->plainTextToken;
                $response = [
                    'status' => 'success',
                    'user' => $user,
                    'token' => $token
                ];
                return response()->json($response, 200);
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => ['Please verify your email first.']
                ], 404);
            }
        }        
    }



    function register(Request $request){
        //Validation
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required', 'email:rfc,dns', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8', 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/'],
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()
            ], 400);
        } else {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'emails_for_marketting' => $request->emails_for_marketting
            ];
            DB::beginTransaction();
            try{
                $user = User::create($data);

                //Send Email for email verification
                $encryptedId = LaravelCrypt::encryptString($user->id);
                $target_link = "https://master.diditgml51xfa.amplifyapp.com/organiser/authorization/".$encryptedId;
                $data = ['name'=>$request->name, 'target_link'=>$target_link];
                $user['to'] = $request->email;
                Mail::send('email_verification',$data, function($messages) use ($user){
                    $messages->to($user['to']);
                    $messages->subject('Email verification');
                });

                DB::commit();
            } catch (\Exception $e){
                DB::rollback();
                dd($e->getMessage());
            }

            if($user != null){
                return response()->json([
                    'status' => 'success',
                    'message' => 'User registered successfully'
                ], 200);
            }else{
                 return response()->json([
                    'status' => 'error',
                    'message' => 'Internal server error'
                ], 500);
            }
        }
    }
    
    function logout(Request $request)
    {
        $logout = $request->user()->currentAccessToken()->delete();
        if(isset($logout)){
            $response = [
            'status' => 'success',
            'message' => ["successfully logged out"]
            ];

            return response()->json($response, 200);
        }else{
            $response = [
            'status' => 'error',
            'message' => ["something went wrong"]
            ];
            return response()->json($response, 200);
        }
    }
    
    function register_with_request(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6'],
            'how_did_you_hear_about_us' => ['nullable'],
            'encryption_key' => ['required']
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()
            ], 400);
        } else {
            //dycrypt encryption key
            $decryptedId = Crypt::decryptString($request->encryption_key);
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'how_did_you_hear_about_us' => $request->how_did_you_hear_about_us
            ];
            DB::beginTransaction();
            try{
                $user = User::create($data);


                $org_req_data = Organisationuserrequest::find($decryptedId)->toArray();

                //Insert data To organisation_user table
                $organizationUser = new OrganizationUser;
                $organizationUser->organisation_id = $org_req_data['organisation_id'];
                $organizationUser->user_id = $user->id;
                $organizationUser->role_id = $org_req_data['role_id'];

                $organizationUser->save();

                //Update Organisation request table
                $org_up_data = Organisationuserrequest::find($decryptedId);
                $org_up_data->sent_mail = 1;
                $org_up_data->save();




                DB::commit();
            } catch (\Exception $e){
                DB::rollback();
                dd($e->getMessage());
            }

            if($user != null){
                return response()->json([
                    'status' => 'success',
                    'message' => 'User registered successfully'
                ], 200);
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Internal server error'
                ], 500);
            }


        }
    }
    
    function verify_reg_email(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'encryption_key' => ['required']
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()
            ], 400);
        } else {
            //dycrypt encryption key
            $decryptedId = Crypt::decryptString($request->encryption_key);
            
            DB::beginTransaction();
            try{
                //Update data To user table
                $user = User::find($decryptedId);

                if($user->status > 0)
                {
                    $user_flg = 1;
                }
                else
                {
                    $user->status = 1;
                    $user->email_verified_at = date("Y-m-d H:i:s");
                    $user->last_login = date('Y-m-d H:i:s');
                    $user->save();
                    $token = $user->createToken('my-app-token')->plainTextToken;
                    $user_flg = 0;
                }

                DB::commit();
            } catch (\Exception $e){
                DB::rollback();
                dd($e->getMessage());
            }

            if($user_flg == 0){
                return response()->json([
                    'status' => 'success',
                    'user' => $user,
                    'token' => $token
                ], 200);
            }else if($user_flg == 1){
                 return response()->json([
                    'status' => 'success',
                    'message' => 'Email already verified'
                ], 200);

            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Internal server error'
                ], 500);
            }


        }
    }
    
    //Forget password steps
    function send_link_forget_pass(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email:rfc,dns']
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()
            ], 400);
        } else {
            //check email exist or not in user table
            $user = User::where('email', $request->email)->first();
            if ($user) {
                // Email exists in the users table
                $encryptedId = LaravelCrypt::encryptString($user->id);
                $target_link = "https://master.diditgml51xfa.amplifyapp.com/organiser/change-password/".$encryptedId;
                $data = ['name'=>$user->name, 'target_link'=>$target_link];
                $user['to'] = $user->email;
                Mail::send('password_change',$data, function($messages) use ($user){
                    $messages->to($user['to']);
                    $messages->subject('Change password');
                });

                return response()->json([
                    'status' => 'success',
                    'message' => 'Change password link is sent to your email'
                ], 200);

            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email is not exist'
                ], 200);
            }
        }
    }
    
    function forget_change_new_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'encryption_key' => ['required'],
            'password' => ['required', 'confirmed', 'min:8', 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/']
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()
            ], 400);
        } else {
            //dycrypt encryption key
            $decryptedId = Crypt::decryptString($request->encryption_key);
            $user = User::find($decryptedId);

            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                    'status' => 'success',
                    'message' => 'Password successfully changed'
                ], 200);

        }    
    }
    
    //Update account settings
    function update_user_account(Request $request)
    {
        $logged_in_user = auth('sanctum')->user();
        
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'phone_no' => ['numeric']
            //'password' => ['required', 'confirmed', 'min:8', 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%@]).*$/',],
        ]);
        
        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()
            ], 400);
        } else {
            $user = User::find($logged_in_user->id);
            
            $user->name = $request->name;
            $user->country_id = $request->country_id;
            $user->phone_no_prefix = $request->phone_no_prefix;
            $user->phone_no = $request->phone_no;
            $user->address = $request->address;
            $user->city = $request->city;
            $user->state = $request->state;
            $user->post_code = $request->post_code;
            //$user->password = Hash::make($request->password);
            $user->save();
            
            return response()->json([
                    'status' => 'success',
                    'message' => 'Successfully updated',
                    'user' => $user
                ], 200);
            
            
        }
    }
    
    //Update password
    function update_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => ['required'],
            'password' => ['required', 'confirmed', 'min:8', 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/']
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()
            ], 400);
        } else {
            //check old password matched or not
            $logged_in_user = auth('sanctum')->user();
            $user = User::find($logged_in_user->id);
            if (!$user || !Hash::check($request->old_password, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => ['Old password do not match our records.']
                ], 404);
            }else{
                $user->password = Hash::make($request->password);
                $user->save();
                
                return response()->json([
                        'status' => 'success',
                        'message' => 'Successfully passord updated',
                        'user' => $user
                    ], 200);
            }
        }
    }
}
