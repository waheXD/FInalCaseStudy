<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    private $patient, $appointments, $medical_records;

    public function __construct()
    {
        $this->patient = new Patient;
        $this->appointments = new Appointment;
        $this->medical_records = new MedicalRecord;
    }

    public function getAllAppointments()
    {
        $appointments = DB::table('appointments as a')
            ->join('users as d', 'd.id', '=', 'a.doctor_id')
            ->join('users as u', 'u.id', '=', 'a.user_id')
            ->select('a.*', 'd.fullname as doctor_name', 'u.fullname as patient_name')
            ->where('status', '!=', 3)
            ->get();

        return response()->json($appointments, 200);
    }

    public function getAppointments($id)
    {
        $appoitments = $this->appointments->where('user_id', $id)->get();

        return response()->json($appoitments, 200);
    }

    public function getDoctorAppointments($id)
    {
        $appoitments = $this->appointments->where('doctor_id', $id)->get();

        return response()->json($appoitments, 200);
    }

    public function showAppointment($id)
    {
        $appointment = DB::table('appointments as a')
            ->join('users as u', 'a.doctor_id', '=', 'u.id')
            ->select('a.*', 'u.*', 'a.id as appointment_id')
            ->where('a.id', $id)
            ->first();

        return response()->json($appointment, 200);
    }

    public function bookAppointment(Request $request)
    {
        $request->validate(
            [
                'type' => 'required',
                'date' => 'required',
                'doctor' => 'required',
            ]
        );

        $this->appointments->create([
            'type' => $request->type,
            'user_id' => $request->user_id,
            'doctor_id' => $request->doctor,
            'date' => $request->date,
            'status' => 0,
        ]);

        return response()->json(['message' => 'Successfully Booked.'], 201);
    }

    public function removeAppointment($id)
    {
        $appointment = $this->appointments->find($id)->delete();

        return response()->json($appointment, 200);
    }

    public function verifiedAppointment($id)
    {
        $appointment = $this->appointments->find($id);
        $appointment->update([
            'status' => 1
        ]);

        return response()->json($appointment, 200);
    }

    public function doneAppointment($id)
    {
        $appointment = $this->appointments->find($id);
        $appointment->update([
            'status' => 2
        ]);

        return response()->json($appointment, 200);
    }

    public function issueMedicalRecord($id)
    {
        $appointment = $this->appointments->find($id);
        $patient = $this->patient->where('user_id', $appointment->user_id)->first();

        if (!$patient)
            return response()->json(['message' => 'Patient no record, Please register this patient.'], 404);

        $appointment->update([
            'status' => 3
        ]);

        $record = $this->medical_records->create([
            'weight' => $patient->weight,
            'height' => $patient->height,
            'user_id' => $appointment->user_id,
            'doctor_id' => $appointment->doctor_id
        ]);

        return response()->json($record, 200);
    }
}
