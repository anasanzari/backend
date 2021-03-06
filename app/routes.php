<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


/*
Base URL:
http://www.ragam.org.in/2015/cms/


Normal API:
/api/


Manage:
/manage/

*/

//Handle 404 errors.
App::missing(function($exception)
{
	return View::make('404');
});



Route::get('/', function()
{
	return Redirect::away(Config::get('app.homepage'));
});


Route::group(array(
	'prefix' => 'api'
	), function(){
	    Route::get('/', array(
	    	'uses'=> 'ApiController@index'
	    	));

	    Route::get('events', array(
	    	'uses'=> 'ApiController@events'
	    	));

	    //Gets event details, and whether user is registered for the event.
	    Route::get('event/{code}', array(
	    	'uses'=> 'ApiController@event'
	    	));



	    // Returns user details,
	    // and details of registered events.
	    Route::get('user', array(
	    	'uses' => 'ApiController@user'
	    	));

	    //Using GET temporarily to support JSONP to work locally.
	    Route::get('user/login', array(
	    	'uses' => 'ApiController@userPostLogin'
	    	));

	    //Using GET temporarily to support JSONP to work locally.
	    Route::get('user/signup', array(
	    	'uses' => 'ApiController@userSignup'
	    	));



	    Route::get('user/logout', array(
	    	'uses' => 'ApiController@userLogout'
	    	));

	    Route::get('user/fb_login', array(
	    	'uses' => 'ApiController@userFbLogin'
	    	));

	    Route::get('user/fb_complete', array(
	    	'as' => 'api_fb_complete_get',
	    	'uses' => 'ApiController@userFbComplete'
	    	));

	    Route::post('user/fb_complete', array(
	    	'before' => 'csrf',
	    	'as' => 'api_fb_complete_post',
	    	'uses' => 'ApiController@userFbCompletePost'
	    	));



	    //Handle event registration
	    Route::get('event_register', array(
	    	'uses' => 'ApiController@eventRegister'
	    	));

	    Route::get('event_deregister', array(
	    	'uses' => 'ApiController@eventDeregister'
	    	));






	    //Search for college
	    Route::get('colleges', array(
	    	'as' => 'api_college_search',
	    	'uses' => 'ApiController@collegeSearch'
	    	));


	    //New College
	    Route::get('colleges/new', array(
	    	'as' => 'api_new_college',
	    	'uses' => 'ApiController@collegeNew'
	    	));


	    //Search for registered users.
	    Route::get('users', array(
	    	'uses' => 'ApiController@userSearch'
	    	));


	}
);


//Login, logout features of manage.
Route::group(array(
	'prefix' => 'manage',
	), function(){
	
		Route::get('login', array(
			'as' => 'manager_login',
			'uses'=> 'ManageController@login',
			));

		Route::post('login', array(
			'before' => 'csrf',
			'uses'=> 'ManageController@postLogin',
			));

		Route::get('test_api', array(
			'uses'=> 'ManageController@testApi',
			));

		Route::get('signup', array(
			'as' => 'manager_signup',
			'uses'=> 'ManageController@signup',
			));

		Route::post('signup', array(
			'before' => 'csrf',
			'uses'=> 'ManageController@postSignup',
			));

		Route::get('logout', array(
			'as' => 'manager_logout',
			'uses'=> 'ManageController@logout',
			));
	}
);

//Features after being logged in.
Route::group(array(
	'prefix' => 'manage',
	'before' => 'auth.manager',
	), function(){
	

	    Route::get('/', array(
	    	'as' => 'manager_dashboard',
	    	'uses'=> 'ManageController@index'
	    	));

	    Route::get('error', array(
	    	'as' => 'error_unauthorized',
	    	'uses'=> 'ManageController@errorUnauthorized'
	    	));

	    Route::get('managers', array(
	    	'before' => 'role.edit_managers',
	    	'as' => 'manager_managers',
	    	'uses'=> 'ManageController@managers'
	    	));

	    Route::post('managers/new', array(
	    	'before' => 'role.edit_managers',
	    	'as' => 'action_new_manager',
	    	'before' => 'csrf',
	    	'uses'=> 'ManageController@managersNew'
	    	));


	    Route::get('managers/change_status', array(
	    	'before' => 'role.edit_managers',
	    	'as' => 'action_change_manager_status',
	    	'uses'=> 'ManageController@managersChangeStatus'
	    	));


	    Route::get('event_categories', array(
	    	'before' => 'role.event_categories',
	    	'as' => 'manager_event_categories',
	    	'uses'=> 'ManageController@eventCategories'
	    	));


	    Route::post('event_categories/add', array(
	    	'before' => 'role.event_categories|csrf',
	    	'as' => 'action_add_event_category',
	    	'uses'=> 'ManageController@eventCategoriesNew'
	    	));


	    Route::get('event_categories/delete/{id}', array(
	    	'before' => 'role.event_categories',
	    	'as' => 'action_delete_event_category',
	    	'uses'=> 'ManageController@eventCategoriesDelete'
	    	))->where('id','[0-9]+');



	    Route::get('edit_homepage', array(
	    	'before' => 'role.homepage',
	    	'as' => 'manager_edit_homepage',
	    	'uses'=> 'ManageController@editHomepage'
	    	));

    	Route::get('edit_homepage/change_status', array(
    		'before' => 'role.homepage',
    		'as' => 'action_update_display_status',
    		'uses'=> 'ManageController@editHomepageStatus'
    		));


    	Route::get('edit_homepage/delete', array(
    		'before' => 'role.homepage',
    		'as' => 'action_update_delete',
    		'uses'=> 'ManageController@editHomepageDeleteUpdate'
    		));

    	Route::post('edit_homepage/add', array(
    		'before' => 'role.homepage|csrf',
    		'as' => 'action_homepage_add_update',
    		'uses'=> 'ManageController@editHomepageAddUpdate'
    		));



    	Route::get('verify_colleges', array(
	    	'before' => 'role.colleges',
	    	'as' => 'manager_verify_colleges',
	    	'uses'=> 'ManageController@verifyColleges'
	    	));

    	Route::post('verify_colleges/add', array(
	    	'before' => 'role.colleges',
	    	'as' => 'manager_verify_colleges_add',
	    	'uses'=> 'ManageController@verifyCollegesAdd'
	    	));

    	Route::get('verify_colleges/change_status', array(
    		'before' => 'role.colleges',
    		'as' => 'action_update_college_status',
    		'uses'=> 'ManageController@verifyCollegesStatus'
    		));

    	Route::get('verify_colleges/pending_registrations', array(
	    	'before' => 'role.colleges',
	    	'as' => 'manager_pending_registrations',
	    	'uses'=> 'ManageController@verifyCollegesPending'
	    	));


    	Route::get('student_registrations', array(
	    	'before' => 'role.student_registrations',
	    	'as' => 'manager_student_registrations',
	    	'uses'=> 'ManageController@studentRegistrations'
	    	));

    	Route::get('event_registrations', array(
	    	'before' => 'role.student_registrations',
	    	'as' => 'manager_event_registrations',
	    	'uses'=> 'ManageController@eventRegistrations'
	    	));

    	Route::get('event_registrations/{id}', array(
	    	'before' => 'role.student_registrations',
	    	'as' => 'manager_event_registration_details',
	    	'uses'=> 'ManageController@eventRegistrationDetails'
	    	))->where('id', '[0-9]+');


    	Route::get('hospitality', array(
	    	'before' => 'role.hospitality',
	    	'as' => 'manager_hospitality',
	    	'uses'=> 'ManageController@hospitality'
	    	));


    	//Registration software
    	//#####################

    	Route::get('software/statistics', array(
	    	'before' => 'role.software_level2',
	    	'as' => 'software_statistics',
	    	'uses'=> 'SoftwareController@statistics'
	    	));

    	Route::get('software/admin', array(
	    	'before' => 'role.software_level2',
	    	'as' => 'software_admin',
	    	'uses'=> 'SoftwareController@admin'
	    	));

    	Route::get('software/admin/edit_registration', array(
	    	'before' => 'role.software_level2',
	    	'as' => 'software_admin_edit_registration',
	    	'uses'=> 'SoftwareController@adminEditRegistration'
	    	));

    	Route::post('software/admin/save_registration', array(
	    	'before' => 'role.software_level2',
	    	'as' => 'software_admin_save_registration',
	    	'uses'=> 'SoftwareController@adminSaveRegistration'
	    	));

    	Route::post('software/admin/unconfirm_team', array(
	    	'before' => 'role.software_level2',
	    	'as' => 'software_admin_unconfirm_team',
	    	'uses'=> 'SoftwareController@adminUnconfirmTeam'
	    	));


    	Route::get('software/results', array(
	    	'before' => 'role.software_level1',
	    	'as' => 'software_results',
	    	'uses'=> 'SoftwareController@results'
	    	));




    	Route::get('software/block_events', array(
	    	'before' => 'role.software_level1',
	    	'as' => 'software_block_events',
	    	'uses'=> 'SoftwareController@blockEvents'
	    	));

    	Route::get('software/block_events/do', array(
	    	'before' => 'role.software_level1',
	    	'as' => 'software_block_events_do',
	    	'uses'=> 'SoftwareController@blockEventsDo'
	    	));


    	Route::get('software/student_registration', array(
	    	'before' => 'role.software',
	    	'as' => 'software_student_registration',
	    	'uses'=> 'SoftwareController@studentRegistration'
	    	));

    	Route::get('software/student_registration/{id}', array(
	    	'before' => 'role.software',
	    	'as' => 'software_student_registration_details',
	    	'uses'=> 'SoftwareController@studentRegistrationDetails'
	    	))->where('id', '[0-9]+');

		Route::get('software/student_registration/new', array(
	    	'before' => 'role.software',
	    	'as' => 'software_student_registration_new',
	    	'uses'=> 'SoftwareController@studentRegistrationNew'
	    	));

    	Route::get('software/student_registration/do', array(
	    	'before' => 'role.software',
	    	'as' => 'software_student_registration_do',
	    	'uses'=> 'SoftwareController@studentRegistrationDo'
	    	));

    	Route::post('software/student_registration/save', array(
	    	'before' => 'role.software|csrf',
	    	'as' => 'software_student_registration_save',
	    	'uses'=> 'SoftwareController@studentRegistrationSave'
	    	));




    	Route::get('software/event_registration', array(
	    	'before' => 'role.software',
	    	'as' => 'software_event_registration',
	    	'uses'=> 'SoftwareController@eventRegistration'
	    	));


    	//For Ajax
    	Route::post('software/event_registration/confirm', array(
	    	'before' => 'role.software',
	    	'as' => 'software_event_registration_confirm',
	    	'uses'=> 'SoftwareController@eventRegistrationConfirm'
	    	));

    	//For Button
    	Route::get('software/event_registration/confirm', array(
	    	'before' => 'role.software',
	    	'as' => 'software_event_registration_confirm_get',
	    	'uses'=> 'SoftwareController@eventRegistrationConfirmGet'
	    	));

    	Route::get('software/event_registration/{id}', array(
	    	'before' => 'role.software',
	    	'as' => 'software_event_registration_details',
	    	'uses'=> 'SoftwareController@eventRegistrationDetails'
	    	))->where('id', '[0-9]+');

    	Route::get('software/event_registration/{id}/edit', array(
	    	'before' => 'role.software',
	    	'as' => 'software_event_registration_details_edit',
	    	'uses'=> 'SoftwareController@eventRegistrationDetailsEdit'
	    	))->where('id', '[0-9]+');

    	Route::get('software/event_registration/{id}/delete', array(
	    	'before' => 'role.software',
	    	'as' => 'software_event_registration_delete',
	    	'uses'=> 'SoftwareController@eventRegistrationDelete'
	    	))->where('id', '[0-9]+');

    	Route::get('software/event_registration/remove_member', array(
	    	'before' => 'role.software',
	    	'as' => 'software_event_registration_remove_member',
	    	'uses'=> 'SoftwareController@eventRegistrationRemoveMember'
	    	))->where('id', '[0-9]+');

    	Route::post('software/event_registration/add_members', array(
	    	'before' => 'role.software',
	    	'as' => 'software_event_registration_add_members',
	    	'uses'=> 'SoftwareController@eventRegistrationAddMember'
	    	))->where('id', '[0-9]+');

    	Route::get('software/event_registration/new', array(
	    	'before' => 'role.software',
	    	'as' => 'software_event_registration_new',
	    	'uses'=> 'SoftwareController@eventRegistrationNew'
	    	));

    	Route::post('software/event_registration/new', array(
	    	'before' => 'role.software',
	    	'as' => 'software_event_registration_new_post',
	    	'uses'=> 'SoftwareController@eventRegistrationNewPost'
	    	));






    	Route::get('software/workshop_registration', array(
	    	'before' => 'role.software',
	    	'as' => 'software_workshop_registration',
	    	'uses'=> 'SoftwareController@workshopRegistration'
	    	));

    	Route::get('software/workshop_registration/{id}', array(
	    	'before' => 'role.software',
	    	'as' => 'software_workshop_registration_details',
	    	'uses'=> 'SoftwareController@workshopRegistrationDetails'
	    	))->where('id', '[0-9]+');


    	Route::get('software/workshop_registration/confirm', array(
	    	'before' => 'role.software',
	    	'as' => 'software_workshop_registration_confirm',
	    	'uses'=> 'SoftwareController@workshopRegistrationConfirm'
	    	));

    	Route::get('software/workshop_registration/new', array(
	    	'before' => 'role.software',
	    	'as' => 'software_workshop_registration_new',
	    	'uses'=> 'SoftwareController@workshopRegistrationNew'
	    	));

    	Route::post('software/workshop_registration/new', array(
	    	'before' => 'role.software',
	    	'as' => 'software_workshop_registration_new_post',
	    	'uses'=> 'SoftwareController@workshopRegistrationNewPost'
	    	));



    	Route::get('software/event_list', array(
	    	'before' => 'role.software',
	    	'as' => 'software_event_list',
	    	'uses'=> 'SoftwareController@eventList'
	    	));

    	Route::post('software/event_list', array(
	    	'before' => 'role.software',
	    	'as' => 'software_event_list_post',
	    	'uses'=> 'SoftwareController@eventListPost'
	    	));


    	Route::get('software/event_list/print', array(
	    	'before' => 'role.software',
	    	'as' => 'software_event_list_print',
	    	'uses'=> 'SoftwareController@eventListPrint'
	    	));



    	Route::get('software/hospitality', array(
	    	'before' => 'role.hospitality_manager',
	    	'as' => 'software_hospitality_manager',
	    	'uses'=> 'SoftwareController@hospitalityManager'
	    	));

    	Route::get('software/hospitality/allocate', array(
	    	'before' => 'role.hospitality_manager',
	    	'as' => 'software_hospitality_allocate',
	    	'uses'=> 'SoftwareController@hospitalityAllocate'
	    	));

    	Route::get('software/hospitality/allocate_do', array(
	    	'before' => 'role.hospitality_manager',
	    	'as' => 'software_hospitality_allocate_do',
	    	'uses'=> 'SoftwareController@hospitalityAllocateDo'
	    	));

    	Route::post('software/hospitality/allocate_do', array(
	    	'before' => 'role.hospitality_manager',
	    	'as' => 'software_hospitality_allocate_do_ajax',
	    	'uses'=> 'SoftwareController@hospitalityAllocateDoAjax'
	    	));

    	Route::post('software/hospitality/show', array(
	    	'before' => 'role.hospitality_manager',
	    	'as' => 'software_hospitality_show_team',
	    	'uses'=> 'SoftwareController@hospitalityShowTeam'
	    	));

    	Route::get('software/hospitality/checkout', array(
	    	'before' => 'role.hospitality_manager',
	    	'as' => 'software_hospitality_checkout',
	    	'uses'=> 'SoftwareController@hospitalityCheckout'
	    	));





    	//Events
	    Route::get('events', array(
	    	'before' => 'role.event_edit',
	    	'as' => 'manager_events',
	    	'uses'=> 'ManageController@events'
	    	));

	    Route::get('events/new', array(
	    	'before' => 'role.events',
	    	'as' => 'action_new_event',
	    	'uses'=> 'ManageController@eventsNew'
	    	));

	    Route::get('events/change_status', array(
	    	'before' => 'role.events',
	    	'as' => 'action_change_event_status',
	    	'uses'=> 'ManageController@eventsChangeStatus'
	    	));

	    Route::get('events/{id}', array(
	    	'before' => 'role.event_edit',
	    	'as' => 'action_edit_event',
	    	'uses'=> 'ManageController@eventsEdit'
	    	))->where('id','[0-9]+');


	    Route::post('events/save', array(
	    	'before' => 'role.event_edit|csrf',
	    	'as' => 'action_save_event',
	    	'uses'=> 'ManageController@eventsSave'
	    	));


	    Route::get('events/edit', array(
	    	'before' => 'role.event_edit',
	    	'as' => 'action_event_redirect_to_edit',
	    	'uses'=> 'ManageController@eventsRedirectToEdit'
	    	));


	    Route::post('events/upload_image', array(
	    	'before' => 'role.event_edit',
	    	'as' => 'action_upload_image',
	    	'uses'=> 'ManageController@eventsUploadImage'
	    	));

	}
);