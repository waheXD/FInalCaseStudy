<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    private $users, $doctor;

    public function __construct()
    {
        $this->doctor = new Doctor;
        $this->users = new User;
    }

    public function doctors()
    {
        $doctors = $this->users->where('account_type', 2)->get();

        return response()->json($doctors, 200);
    }

    public function showDoctor($id)
    {
        $doctor = DB::table('doctor as d')
            ->join('users as u', 'u.id', '=', 'd.user_id')
            ->select('d.*', 'u.*')
            ->where('u.id', $id)
            ->first();

        if (!$doctor) {
            $doctor = $this->users->find($id);
        }

        return response()->json($doctor, 200);
    }

    public function registerDoctors(Request $request)
    {
        $request->validate(
            [
                'type' => 'required',
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

        $doctor = $this->users->create([
            'fullname' => trim($request->fullname),
            'email' => trim($request->email),
            'address' => trim($request->address),
            'contact' => trim($request->contact),
            'account_type' => 2,
            'password' => Hash::make($request->password),
        ]);

        $this->doctor->create([
            'type' => $request->type,
            'user_id' => $doctor->id,
        ]);

        return response()->json(['message' => 'Successfully Registered.'], 201);
    }

    public function editDoctor(Request $request, $id)
    {
        $request->validate([
            'fullname' => 'required',
            'address' => 'required',
            'contact' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $doctor = $this->users->find($id);

        $doctordata = $this->doctor->where('user_id', $id)->first();

        if (!$doctor) {
            return response()->json(['message' => "Doctor Not Found"], 404);
        }

        if (!$doctordata) {
            $this->doctor->create([
                'type' => $request->type,
                'user_id' => $doctor->id,
            ]);
        } else {
            $doctordata->update([
                'type' => $request->type,
            ]);
        }

        $doctor->update([
            'fullname' => $request->fullname,
            'address' => $request->address,
            'contact' => $request->contact,
            'email' => $request->email,
        ]);

        return response()->json(['message' => 'Successfully Edited.'], 200);
    }

    public function deleteDoctor($id)
    {
        $this->users->find($id)->delete();

        return response()->json(['message' => 'Successfully Removed.'], 200);
    }

    public function removeDoctor($id)
    {
        $doctor = $this->doctor->where('user_id', $id)->first();

        if (!$doctor) {
            return response()->json(['message' => 'Doctor Already Removed.'], 401);
        }

        $doctor->delete();

        return response()->json(['message' => 'Successfully Removed.'], 200);
    }

    public function getAccount($id)
    {
        $account = DB::table('doctor as d')
            ->join('users as u', 'u.id', '=', 'd.user_id')
            ->select('d.*', 'u.*')
            ->where('u.id', $id)
            ->first();

        return response()->json($account, 200);
    }
}
