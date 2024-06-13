<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PatientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthenticationController::class, 'login']);

Route::get('doctors', [DoctorController::class, 'doctors']);
Route::get('getAccount/{id}', [DoctorController::class, 'getAccount']);
Route::get('showDoctor/{id}', [DoctorController::class, 'showDoctor']);
Route::post('registerDoctors', [DoctorController::class, 'registerDoctors']);
Route::put('editDoctor/{id}', [DoctorController::class, 'editDoctor']);
Route::delete('deleteDoctor/{id}', [DoctorController::class, 'deleteDoctor']);
Route::delete('removeDoctor/{id}', [DoctorController::class, 'removeDoctor']);

Route::get('patients', [PatientController::class, 'patients']);
Route::get('showPatient/{id}', [PatientController::class, 'showPatient']);
Route::post('registerPatients', [PatientController::class, 'registerPatients']);
Route::put('editPatient/{id}', [PatientController::class, 'editPatient']);
Route::delete('deletePatient/{id}', [PatientController::class, 'deletePatient']);
Route::delete('removePatient/{id}', [PatientController::class, 'removePatient']);

Route::get('getAppointments/{id}', [AppointmentController::class, 'getAppointments']);
Route::get('showAppointment/{id}', [AppointmentController::class, 'showAppointment']);
Route::patch('doneAppointment/{id}', [AppointmentController::class, 'doneAppointment']);
Route::patch('verifiedAppointment/{id}', [AppointmentController::class, 'verifiedAppointment']);
Route::delete('removeAppointment/{id}', [AppointmentController::class, 'removeAppointment']);
Route::post('bookAppointment', [AppointmentController::class, 'bookAppointment']);
Route::get('getAllAppointments', [AppointmentController::class, 'getAllAppointments']);
Route::get('getDoctorAppointments/{id}', [AppointmentController::class, 'getDoctorAppointments']);
Route::post('issueMedicalRecord/{id}', [AppointmentController::class, 'issueMedicalRecord']);

Route::get('getAllMedicalRecords', [MedicalRecordController::class, 'getAllMedicalRecords']);
Route::get('getAllDoctorMedicals/{id}', [MedicalRecordController::class, 'getAllDoctorMedicals']);
Route::get('showMedicalRecords/{id}', [MedicalRecordController::class, 'showMedicalRecords']);
Route::get('viewMedicalRecords/{id}', [MedicalRecordController::class, 'viewMedicalRecords']);
Route::patch('editMedicalRecord/{id}', [MedicalRecordController::class, 'editMedicalRecord']);


