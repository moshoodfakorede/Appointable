<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', 'FrontendController@index');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

Route::get('/new-appointment/{doctor}/{date}', 'FrontendController@showAppointment')->name('show.appointment');

Auth::routes();

Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::resource('department', 'DepartmentController');
    Route::resource('doctor', 'DoctorController');
    Route::get('/patients', 'PatientController@index')->name('bookings.today');
    Route::get('/patients/all', 'PatientController@allBookings')->name('bookings.all');
    Route::get('/update/{id}/status', 'PatientController@updateStatus')->name('update.status');
});

Route::group(['middleware' => ['auth', 'doctor']], function () {
    Route::resource('appointment', 'AppointmentController');
    Route::post('appointment/check', 'AppointmentController@check')->name('appointment.check');
    Route::post('appointment/update', 'AppointmentController@updateTime')->name('appointment.update');
    Route::get('prescription/today', 'PrescriptionController@index')->name('prescription.today');
    Route::get('prescription/all', 'PrescriptionController@allPresriptions')->name('prescription.all');
    Route::get('/prescription/{userId}/{date}', 'PrescriptionController@show')->name('show.prescription');
    Route::post('/prescription/store', 'PrescriptionController@store')->name('store.prescription');
});

Route::group(['middleware' => ['auth', 'patient']], function () {
    Route::get('/my-bookings', 'FrontendController@myBookings')->name('my.bookings');
    Route::get('/my-prescriptions', 'PrescriptionController@myPrescriptions')->name('my.prescriptions');
    Route::post('/book/appointment', 'FrontendController@storeAppointment')->name('store.appointment');
    Route::get('/user/profile', 'ProfileController@index')->name('profile');
    Route::post('/profile/update', 'ProfileController@store')->name('store.profile');
    Route::post('/avatar', 'ProfileController@updateAvatar')->name('update.avatar');
});
