<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Ticket;
use App\Models\Servicefee;
use App\Models\Processingfee;
use App\Models\Event;

class TicketController extends Controller
{
    public function calculate_buyer_total(Request $request)
    {
        $organizationId = $request->organisation_id;
        $given_cost = $request->given_cost;

        //Check organiser has service fee or not
        $serviceFee = Servicefee::where('organisation_id', $organizationId)->first();
        if (!$serviceFee) {
            $serviceFee = Servicefee::first(); //If not then get first row
        }
        $percentage_val = $serviceFee->percentage_val;
        $amount_val = $serviceFee->amount_val;

        $cost_one = ( $given_cost * ($percentage_val / 100));
        $cost_two = ($cost_one + $amount_val);

        $cost_three = ($given_cost + $cost_two);

        $processingFee = Processingfee::first();

        $pf_percentage_val = 0;
        if($processingFee){
            $pf_percentage_val = $processingFee->percentage_val;
        }

        //return $pf_percentage_val;
        $cost_four = ($cost_three * ($pf_percentage_val/100));

        $final_cost = ($given_cost + $cost_two + $cost_four);

         $data = array(
            'ticket_price' => $given_cost,
            'service_fee' => number_format($cost_two, 2),
            'processing_fee' => number_format($cost_four, 2),
            'cost_to_buyer' => number_format($final_cost, 2),
            'service_fee_per' => number_format($percentage_val,2),
            'service_fee_amount_val' => number_format($amount_val, 2),
            'processing_fee_per' => number_format($pf_percentage_val,2)
        );

        $response = [
            'status' => 'success',
            'data' => $data
        ];
        return response()->json($response, 200);
    }

    function store(Request $request)
    {
        $user = auth('sanctum')->user(); //Get User details
        
        $event_id = $request->event_id;
        $event = Event::find($event_id);
        $event_date = $event->starts_date;

        $validator = Validator::make($request->all(), [
            'event_id' => ['required'],
            'ticket_type' => ['required'],
            'ticket_name' => ['required'],
            'quantity' => ['required'],
            'given_cost' => ['required'],
            'cost_to_buyer' => ['required'],
            'sales_starts_date' => ['required', 'before:' .  $event_date],
            'sales_ends_date' => ['required', 'after:sales_starts_date', 'before:' .  $event_date]
        ]);

        if($validator->fails()){
             return response()->json([
                'status' => 'error',
                'message' => $validator->messages()
            ], 400);
        } else {
            $ticket = new Ticket;
            $ticket->user_id = $user->id;
            $ticket->event_id = $request->event_id;
            $ticket->ticket_type = $request->ticket_type;
            $ticket->ticket_name = $request->ticket_name;
            $ticket->quantity = $request->quantity;
            $ticket->given_cost = $request->given_cost;
            $ticket->service_fee_per = $request->service_fee_per;
            //$ticket->service_fee_per_cost = $request->service_fee_per_cost;
            $ticket->service_fee_amount_val = $request->service_fee_amount_val;
            //$ticket->service_fee_amount_val_cost = $request->service_fee_amount_val_cost;
            $ticket->processing_fee_per = $request->processing_fee_per;
            //$ticket->processing_fee_per_cost = $request->processing_fee_per_cost;
            $ticket->cost_to_buyer = $request->cost_to_buyer;
            //$ticket->absorb_fee = $request->absorb_fee;
            //$ticket->ticket_per_order_min = $request->ticket_per_order_min;
            //$ticket->ticket_per_order_max = $request->ticket_per_order_max;
            $ticket->sales_starts_date = $request->sales_starts_date;
            $ticket->sales_ends_date = $request->sales_ends_date;
            $ticket->sales_starts_time = $request->sales_starts_time;
            $ticket->sales_ends_time = $request->sales_ends_time;
            $ticket->description = $request->description;
            $ticket->save();

            if($ticket != null){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Ticket created successfully',
                    'ticket' => $ticket
                ], 200);
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Internal server error'
                ], 500);
            }

        }
    }
    
    function show($ticket_id)
    {
        $ticket = Ticket::find($ticket_id);
        if ($ticket) {
            return response()->json([
                    'status' => 'success',
                    'message' => 'Ticket details',
                    'ticket' => $ticket
                ], 200);
        } else {
            return response()->json([
                    'status' => 'error',
                    'message' => 'Internal server error'
                ], 500);
        }
    }
    
    function get_all_tickets($event_id)
    {
        $tickets = Ticket::where('event_id', $event_id)
                            ->where('status', 1)
                            ->get();
        if ($tickets) {
            return response()->json([
                    'status' => 'success',
                    'message' => 'Ticket details',
                    'tickets' => $tickets
                ], 200);
        } else {
            return response()->json([
                    'status' => 'error',
                    'message' => 'Internal server error'
                ], 500);
        }
        
    }
    
    function update(Request $request, $ticket_id)
    {
        $user = auth('sanctum')->user(); //Get User details

        $validator = Validator::make($request->all(), [
            'event_id' => ['required'],
            'ticket_type' => ['required'],
            'ticket_name' => ['required'],
            'quantity' => ['required'],
            'given_cost' => ['required'],
            'cost_to_buyer' => ['required']
        ]);

        if($validator->fails()){
             return response()->json([
                'status' => 'error',
                'message' => $validator->messages()
            ], 400);
        } else {
            
            $ticket = Ticket::find($ticket_id);
            
            $ticket->user_id = $user->id;
            $ticket->event_id = $request->event_id;
            $ticket->ticket_type = $request->ticket_type;
            $ticket->ticket_name = $request->ticket_name;
            $ticket->quantity = $request->quantity;
            $ticket->given_cost = $request->given_cost;
            $ticket->service_fee_per = $request->service_fee_per;
            //$ticket->service_fee_per_cost = $request->service_fee_per_cost;
            $ticket->service_fee_amount_val = $request->service_fee_amount_val;
            //$ticket->service_fee_amount_val_cost = $request->service_fee_amount_val_cost;
            $ticket->processing_fee_per = $request->processing_fee_per;
            //$ticket->processing_fee_per_cost = $request->processing_fee_per_cost;
            $ticket->cost_to_buyer = $request->cost_to_buyer;
            //$ticket->absorb_fee = $request->absorb_fee;
            //$ticket->ticket_per_order_min = $request->ticket_per_order_min;
            //$ticket->ticket_per_order_max = $request->ticket_per_order_max;
            $ticket->sales_starts_date = $request->sales_starts_date;
            $ticket->sales_ends_date = $request->sales_ends_date;
            $ticket->sales_starts_time = $request->sales_starts_time;
            $ticket->sales_ends_time = $request->sales_ends_time;
            $ticket->description = $request->description;
            $ticket->save();
            
            if($ticket != null){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Ticket updated successfully',
                    'ticket' => $ticket
                ], 200);
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Internal server error'
                ], 500);
            }
            
        }
    }
    
    function updateStatus($ticket_id)
    {
        $ticket = Ticket::find($ticket_id);
        if($ticket)
        {
            if($ticket->status == 1)
            {
                $ticket->status = 0;
            }else{
                $ticket->status = 1;
            }
            $ticket->save();
                    
            return response()->json([
                'status' => 'success',
                'message' => 'Ticket deleted successfully'
            ], 200);
            
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500); 
        }
    }
}
