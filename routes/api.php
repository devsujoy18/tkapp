<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\OrganisationController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\EventTypeController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SubcategoryController;
use App\Http\Controllers\Api\TimezoneController;
use App\Http\Controllers\Api\TicketController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

//All development releated APIs



Route::post('organiser-reg', [UserController::class, 'register']);
Route::post('verify-email', [UserController::class, 'verify_reg_email']); //Verify register email and login
Route::post('send-link-forget-password', [UserController::class, 'send_link_forget_pass']); //Send link to change password
Route::post('change-password', [UserController::class, 'forget_change_new_password']); //Change password

Route::post('login', [UserController::class, 'login']);
//Register with request
Route::post('request-signup', [UserController::class, 'register_with_request']);

Route::post('calculate-buyer-total', [TicketController::class, 'calculate_buyer_total']);

//All master routes
Route::get('all-roles', [RoleController::class, 'get_all_roles']);
Route::get('all-countries', [CountryController::class, 'get_all_country']);
Route::get('all-event-types', [EventTypeController::class, 'get_all_event_types']);
Route::get('all-categories', [CategoryController::class, 'get_all_categories']);
Route::get('all-subcategory/{category_id}', [SubcategoryController::class, 'get_all_subcategories']);
Route::get('all-timezones', [TimezoneController::class, 'get_all_timezone']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    //All secure URL's
    Route::post('organisation-create', [OrganisationController::class, 'store']);
    Route::post('organisation-update/{organisation_id}', [OrganisationController::class, 'update']);
    Route::get('all-organisations', [OrganisationController::class, 'get_all_organisations']);
    Route::get('update-status/{organisation_id}', [OrganisationController::class, 'updateStatus']);
    
    Route::get('loggedin-user', [UserController::class, 'get_logged_in_user']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::post('update-user-account', [UserController::class, 'update_user_account']);
    Route::post('update-user-password', [UserController::class, 'update_password']);
    
    
    Route::post('event-create', [EventController::class, 'store']);
    Route::post('event-details-update/{slug}', [EventController::class, 'update_event_details']);
    Route::post('event-general-info-update/{slug}', [EventController::class, 'update_event_general_info']);
    Route::get('all-events/{organisation_id}/{type}', [EventController::class, 'get_all_events']);
    Route::get('events/{slug}', [EventController::class, 'get_specific_event']);
    Route::get('publish-event/{slug}', [EventController::class, 'publish_event']);
    Route::get('event-status-update/{slug}', [EventController::class, 'updateStatus']);
    
    Route::post('request-for-member', [OrganisationController::class, 'request_org_member']);
    Route::get('all-request-member/{organisation_id}', [OrganisationController::class, 'get_all_request_member']);
    
    Route::post('save-ticket', [TicketController::class, 'store']); 
    Route::get('ticket-details/{ticket_id}', [TicketController::class, 'show']);
    Route::get('ticket-list/{event_id}', [TicketController::class, 'get_all_tickets']);
    Route::post('update-ticket/{ticket_id}', [TicketController::class, 'update']);
    Route::get('ticket-status-update/{ticket_id}', [TicketController::class, 'updateStatus']);
});





