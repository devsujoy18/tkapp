<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Organisation;
use App\Models\OrganizationUser;
use App\Models\Organisationuserrequest;
use App\Notifications\SendEmailNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use App\Models\Role;
use App\Models\Country;

class OrganisationController extends Controller
{
    //Organisation create
    function store(Request $request){

        $user = auth('sanctum')->user(); //Get User details

        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'country' => ['required'],
            'address' => ['nullable'],
            'contact_email' => ['nullable','email:rfc,dns'],
            'contact_phone_prefix' => ['nullable'],
            'contact_phone' => ['nullable','numeric'],
            'contact_person' => ['nullable']
        ]);

        if($validator->fails()){
             return response()->json([
                'status' => 'error',
                'message' => $validator->messages()
            ], 400);
        } else {
            $data = [
                'user_id' => $user->id,
                'name' => $request->name,
                'country_id' => $request->country,
                'address' => $request->address,
                'contact_email' => $request->contact_email,
                'contact_phone_prefix'=>$request->contact_phone_prefix,
                'contact_phone' => $request->contact_phone,
                'contact_person' => $request->contact_person
            ];

            //return $data;
           DB::beginTransaction();
            try{
                $organisation = Organisation::create($data);

                //Insert organisation user for the organisation owner
                $data = [
                    'organisation_id'=>$organisation->id,
                    'user_id'=>$user->id,
                    'role_id'=>1,
                ];

                $organizationUser = OrganizationUser::create($data);

                DB::commit();
            } catch (\Exception $e){
                DB::rollback();
                dd($e->getMessage());
            }

            if($organisation != null){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Organisation registered successfully'
                ], 200);
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Internal server error'
                ], 500);
            }
        }

    }
    //Organisation update
    function update(Request $request, $organisation_id)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'country' => ['required'],
            'address' => ['nullable'],
            'contact_email' => ['nullable','email:rfc,dns'],
            'contact_phone_prefix' => ['nullable'],
            'contact_phone' => ['nullable','numeric'],
            'contact_person' => ['nullable']
        ]);
        
        if($validator->fails()){
             return response()->json([
                'status' => 'error',
                'message' => $validator->messages()
            ], 400);
        } else {
            $organisation = Organisation::find($organisation_id);
            
            $organisation->name = $request->name;
            $organisation->country_id = $request->country;
            $organisation->address = $request->address;
            $organisation->contact_phone_prefix = $request->contact_phone_prefix;
            $organisation->contact_phone = $request->contact_phone;
            $organisation->contact_person = $request->contact_person;
            $organisation->save();
            
            return response()->json([
                    'status' => 'success',
                    'message' => 'Organisation updated successfully'
                ], 200);
        }
    }
    //Organisation status change
    function updateStatus($organisation_id)
    {
        $organisation = Organisation::find($organisation_id);
        if($organisation->status == 1)
        {
            $organisation->status = 0;
        }else{
            $organisation->status = 1;
        }
        $organisation->save();
        
        $response = [
                'status' => 'success',
                'message' => 'Organisation status changed successfully'
            ];
        return response()->json($response, 200);
    }
    


    //Get all organisations
    function get_all_organisations()
    {

        $user = auth('sanctum')->user(); //Get User details
        $userID = $user->id;
        // $organizations = Organisation::with(['users' => function ($query) use ($userID) {
        //     $query->where('users.id', $userID);
        // }])->get();

        $user = User::find($userID);
        //$organizations = $user->organizations;
        //$organizations = $user->organizations()->with('roles')->get();
        //$organizations = $user->organizations()->with('roles')->where('status', 1)->get();
        $organizations = $user->organizations()->with('roles', 'country')->where('status', 1)->get();

        $response = [
                'status' => 'success',
                'organisations' => $organizations
            ];
             return response()->json($response, 200);
    }
    
        //Request organisation member
    function request_org_member(Request $request)
    {
        $user = auth('sanctum')->user(); //Get User details

        $validator = Validator::make($request->all(), [
            'organisation_id' => ['required'],
            'role_id' => ['required'],
            'email' => ['required','email:rfc,dns']
        ]);

        if($validator->fails()){
             return response()->json([
                'status' => 'error',
                'message' => $validator->messages()
            ], 400);
        } else {
            $data = [
                'user_id' => $user->id,
                'organisation_id' => $request->organisation_id,
                'role_id' => $request->role_id,
                'email' => $request->email
            ];

            //dd($data);

            //return $data;
           DB::beginTransaction();
            try{
                $Organisationuserrequest = Organisationuserrequest::create($data);

                $encryptedId = Crypt::encryptString($Organisationuserrequest->id);//Encrypt the id

                $user = Organisationuserrequest::find($Organisationuserrequest->id);
                
                $org_name = Organisation::find($request->organisation_id)->toArray();
                $role_name = Role::find($request->role_id)->toArray();
                
                $dynamicSubject = "You have been invited to join ".$org_name['name']." on TicketCart";
                $dynamicBody = "You have been invited to join ".$org_name['name']." as ".$role_name['name']." on TicketCart. ".$org_name['name']." uses TicketCart to sell tickets online.";
                
                $dynamicAction = "https://master.diditgml51xfa.amplifyapp.com/signup/".$encryptedId;

                $sent = Notification::send($user, new SendEmailNotification($dynamicSubject, $dynamicBody, $dynamicAction));


                DB::commit();
            } catch (\Exception $e){
                DB::rollback();
                dd($e->getMessage());
            }

            if($Organisationuserrequest != null){
                return response()->json([
                    'status' => 'success',
                    'message' => 'email sent successfully'
                ], 200);
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Internal server error'
                ], 500);
            }
        }
    }
    
    
    function get_all_request_member($organisation_id)
    {
        $result = Organisationuserrequest::where('organisation_id', $organisation_id)->get();
        $response = [
                'status' => 'success',
                'members' => $result
            ];
             return response()->json($response, 200);
    }


    function test_email()
    {
        // $user = User::find(1);

        // $dynamicSubject = "Dynamic Subject";
        // $dynamicBody = "This is the dynamic body.";

        // $test_send = Notification::send($user, new SendEmailNotification($dynamicSubject, $dynamicBody));

        // return $test_send;


        // $id = 1; // Replace with the actual ID you want to encrypt
        // echo $encryptedId = Crypt::encryptString($id);

        // echo "<br>";


        // echo $decryptedId = Crypt::decryptString($encryptedId);
    }
}
