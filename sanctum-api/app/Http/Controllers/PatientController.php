<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    private $users, $patient;

    public function __construct()
    {
        $this->patient = new Patient;
        $this->users = new User;
    }

    public function patients()
    {
        $patients = $this->users->where('account_type', 3)->get();

        return response()->json($patients, 200);
    }

    public function showPatient($id)
    {
        $patient = DB::table('patient as p')
            ->join('users as u', 'u.id', '=', 'p.user_id')
            ->select('p.*', 'u.*')
            ->where('p.user_id', $id)
            ->first();

        if (!$patient) {
            $patient = $this->users->find($id);
        }

        return response()->json($patient, 200);
    }

    public function registerPatients(Request $request)
    {
        $request->validate(
            [
                'birthday' => 'required',
                'weight' => 'required',
                'height' => 'required',
                'fullname' => 'required',
                'email' => 'required|email|unique:users,email',
                'address' => 'required',
                'contact' => 'required',
                'password' => 'required|min:6|confirmed',
            ],
            [
                'email.unique' => 'The email has already been taken.',
                'password.min' => 'The password must be at least 6 characters.',
                'password.confirmed' => 'The password confirmation does not match.',
            ]
        );

        $patient = $this->users->create([
            'fullname' => trim($request->fullname),
            'email' => trim($request->email),
            'address' => trim($request->address),
            'contact' => trim($request->contact),
            'account_type' => 3,
            'password' => Hash::make($request->password),
        ]);

        $this->patient->create([
            'birthday' => $request->birthday,
            'weight' => $request->weight,
            'height' => $request->height,
            'user_id' => $patient->id,
        ]);

        return response()->json(['message' => 'Successfully Registered.'], 201);
    }

    public function editPatient(Request $request, $id)
    {
        $request->validate([
            'fullname' => 'required',
            'address' => 'required',
            'contact' => 'required',
            'birthday' => 'required',
            'weight' => 'required',
            'height' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $patient = $this->users->find($id);
        if (!$patient) {
            return response()->json(['message' => 'Patient not found'], 404);
        }

        $patientdata = $this->patient->where('user_id', $id)->first();

        $patient->update([
            'fullname' => $request->fullname,
            'address' => $request->address,
            'contact' => $request->contact,
            'email' => $request->email,
        ]);

        if ($patientdata) {
            $patientdata->update([
                'birthday' => $request->birthday,
                'weight' => $request->weight,
                'height' => $request->height,
            ]);
        } else {
            $this->patient->create([
                'user_id' => $patient->id,
                'birthday' => $request->birthday,
                'weight' => $request->weight,
                'height' => $request->height,
            ]);
        }

        return response()->json(['message' => 'Successfully Edited.'], 200);
    }

    public function deletePatient($id)
    {
        $this->users->find($id)->delete();

        return response()->json(['message' => 'Successfully Removed.'], 200);
    }

    public function removePatient($id)
    {
        $patient = $this->patient->where('user_id', $id)->first();

        if (!$patient) {
            return response()->json(['message' => 'Patient Already Removed.'], 401);
        }

        $patient->delete();

        return response()->json(['message' => 'Successfully Removed.'], 200);
    }
}
