<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Event;

class EventController extends Controller
{
    //
    //
    function store(Request $request){

        $user = auth('sanctum')->user(); //Get User details

        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'organisation_id' => ['required'],
            'type_id' => ['required'],
            'category_id' => ['required'],
            'tags' => ['required'],
            'venu_type' => ['required'],
            
        ]);

        if($validator->fails()){
             return response()->json([
                'status' => 'error',
                'message' => $validator->messages()
            ], 400);
        } else {
           DB::beginTransaction();
            try{
                //Tags 
                //check tags in tags table and insert unique tags
                $tags_arr = explode(",",$request->tags);
                foreach ($tags_arr as $tagName) {
                    $tagName = trim($tagName);
                
                    // Check if the tag exists
                    $existingTag = DB::table('tags')
                        ->where('name', $tagName)
                        ->first();
                
                    // If the tag doesn't exist, insert it
                    if (!$existingTag) {
                        DB::table('tags')->insert(['name' => $tagName, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
                    }
                }
                

                $display_start_time = $request->display_start_time ? 1 : 0;
                $display_end_time = $request->display_end_time ? 1 : 0;

                $event = new Event;
                $event->name = $request->name;
                $event->user_id = $user->id;
                $event->organisation_id = $request->organisation_id;
                $event->type_id = $request->type_id;
                $event->category_id = $request->category_id;
                $event->subcategory_id = $request->subcategory_id;
                $event->tags = $request->tags;
                $event->venu_type = $request->venu_type;
                $event->full_address = $request->full_address;
                $event->address_one = $request->address_one;
                $event->address_two = $request->address_two;
                $event->city = $request->city;
                $event->state = $request->state;
                $event->post_code = $request->post_code;
                $event->country_id = $request->country_id;
                $event->online_link = $request->online_link;
                $event->starts_date = $request->starts_date;
                $event->ends_date = $request->ends_date;
                $event->starts_time = $request->starts_time;
                $event->ends_time = $request->ends_time;
                $event->display_start_time = $display_start_time;
                $event->display_end_time = $display_end_time;
                $event->timezone_id = $request->timezone_id;
                $event->video_url = $request->video_url;
                $event->summery = $request->summery;
                $event->decription = $request->decription;
                $event->save();
                DB::commit();
            } catch (\Exception $e){
                DB::rollback();
                dd($e->getMessage());
            }

            if($event != null){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Event created successfully',
                    'event' => $event
                ], 200);
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Internal server error'
                ], 500);
            }
        }

    }
    
    public function update_event_details(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'summery' => ['required'],
            'decription' => ['required']
        ]);
        
        if($validator->fails()){
             return response()->json([
                'status' => 'error',
                'message' => $validator->messages()
            ], 400);
        } else {
            
            $event = Event::where('slug', $slug)->firstOrFail();
            if($event)
            {
                //$event->update($request->all());
                $event->video_url = $request->video_url;
                $event->summery = $request->summery;
                $event->decription = $request->decription;
                $event->save();
                
                return response()->json([
                    'status' => 'success',
                    'message' => 'Event updated successfully',
                    'event' => $event
                ], 200);
            }else{
                return response()->json([
                        'status' => 'error',
                        'message' => 'Internal server error'
                    ], 500);
            }
        }
    }
    
    public function update_event_general_info(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'organisation_id' => ['required'],
            'type_id' => ['required'],
            'category_id' => ['required'],
            'tags' => ['required'],
            'venu_type' => ['required'],
            
        ]);

        if($validator->fails()){
             return response()->json([
                'status' => 'error',
                'message' => $validator->messages()
            ], 400);
        } else {
            $user = auth('sanctum')->user();
            //Tags 
                //check tags in tags table and insert unique tags
                $tags_arr = explode(",",$request->tags);
                foreach ($tags_arr as $tagName) {
                    $tagName = trim($tagName);
                
                    // Check if the tag exists
                    $existingTag = DB::table('tags')
                        ->where('name', $tagName)
                        ->first();
                
                    // If the tag doesn't exist, insert it
                    if (!$existingTag) {
                        DB::table('tags')->insert(['name' => $tagName, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
                    }
                }
                

                $display_start_time = $request->display_start_time ? 1 : 0;
                $display_end_time = $request->display_end_time ? 1 : 0;
                
                $event = Event::where('slug', $slug)->firstOrFail();
                
                if($event)
                {
                    $event->name = $request->name;
                    $event->user_id = $user->id;
                    $event->organisation_id = $request->organisation_id;
                    $event->type_id = $request->type_id;
                    $event->category_id = $request->category_id;
                    $event->subcategory_id = $request->subcategory_id;
                    $event->tags = $request->tags;
                    $event->venu_type = $request->venu_type;
                    $event->full_address = $request->full_address;
                    $event->address_one = $request->address_one;
                    $event->address_two = $request->address_two;
                    $event->city = $request->city;
                    $event->state = $request->state;
                    $event->post_code = $request->post_code;
                    $event->country_id = $request->country_id;
                    $event->online_link = $request->online_link;
                    $event->starts_date = $request->starts_date;
                    $event->ends_date = $request->ends_date;
                    $event->starts_time = $request->starts_time;
                    $event->ends_time = $request->ends_time;
                    $event->display_start_time = $display_start_time;
                    $event->display_end_time = $display_end_time;
                    $event->timezone_id = $request->timezone_id;
                    $event->save();
                    
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Event updated successfully',
                        'event' => $event
                    ], 200);
                }else{
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Internal server error'
                    ], 500);
                    
                }
        }
    }
    
    function publish_event($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        if($event)
        {
            $event->is_publish = 1;
            $event->save();
                    
            return response()->json([
                'status' => 'success',
                'message' => 'Event published successfully',
                'event' => $event
            ], 200);
            
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500); 
        }
    }
    
    function updateStatus($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        if($event)
        {
            if($event->status == 1)
            {
                $event->status = 0;
            }else{
                $event->status = 1;
            }
            $event->save();
                    
            return response()->json([
                'status' => 'success',
                'message' => 'Event status changed successfully',
                'event' => $event
            ], 200);
            
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500); 
        }
    }

    function get_all_events($organisation_id,$str)
    {
        $user = auth('sanctum')->user(); //Get User details
        $userID = $user->id;
        $cur_date = date('Y-m-d');
        
        if($str == 'upcomming')
        {
            $events = Event::where('user_id', $userID)
                   ->where('organisation_id', $organisation_id)
                   ->where('is_publish', 1)
                   ->where('status', 1)
                   ->where('starts_date', '>', $cur_date)
                   ->get();
        }else if($str == 'past'){
           $events = Event::where('user_id', $userID)
                   ->where('organisation_id', $organisation_id)
                   ->where('is_publish', 1)
                   ->where('status', 1)
                   ->where('starts_date', '<', $cur_date)
                   ->get(); 
        }else if($str == 'all'){
            $events = Event::where('user_id', $userID)
                   ->where('organisation_id', $organisation_id)
                   ->where('is_publish', 1)
                   ->where('status', 1)
                   ->get();
        }else if($str == 'draft'){
            $events = Event::where('user_id', $userID)
                   ->where('organisation_id', $organisation_id)
                   ->where('is_publish', 0)
                   ->where('status', 1)
                   ->get();
        }else{
            $events = '';
        }
        
        $response = [
                'status' => 'success',
                'events' => $events
            ];
             return response()->json($response, 200);
    }
    
    function get_specific_event($event_slug)
    {
        $event = Event::with(['organisation'])->where('slug', $event_slug)->first();
        if ($event) {
            return response()->json([
                    'status' => 'success',
                    'message' => 'Event created successfully',
                    'event' => $event
                ], 200);
        } else {
            return response()->json([
                    'status' => 'error',
                    'message' => 'Internal server error'
                ], 500);
        }
    }
}
