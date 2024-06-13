<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicalRecordController extends Controller
{
    private $record, $patient;

    public function __construct()
    {
        $this->record = new MedicalRecord;
        $this->patient = new Patient;
    }

    public function getAllMedicalRecords()
    {
        $records = DB::table('medical_records as m')
            ->join('users as u', 'u.id', '=', 'm.user_id')
            ->join('patient as p', 'p.user_id', '=', 'u.id')
            ->select(
                'm.id',
                'm.height',
                'm.weight',
                'u.fullname',
                'u.email',
                'u.address',
                'u.contact',
                'p.birthday'
            )
            ->get();

        return response()->json($records, 200);
    }

    public function showMedicalRecords($id)
    {
        $records = DB::table('medical_records as m')
            ->join('users as u', 'u.id', '=', 'm.user_id')
            ->join('patient as p', 'p.user_id', '=', 'u.id')
            ->select(
                'm.*',
                'm.id as medical_id',
                'u.fullname as user_fullname',
                'u.contact as user_contact',
                'u.address as user_address',
                'u.email as user_email',
                'p.*'
            )
            ->where('m.id', $id)
            ->get();

        return response()->json($records, 200);
    }

    public function getAllDoctorMedicals($id)
    {
        $records = MedicalRecord::join('users as u', 'u.id', '=', 'medical_records.user_id')
            ->select('u.fullname', 'u.address', 'u.email', 'medical_records.id')
            ->where('medical_records.doctor_id', $id)
            ->get();

        return response()->json($records, 200);
    }

    public function viewMedicalRecords($id)
    {
        $records = MedicalRecord::join('users as u', 'u.id', '=', 'medical_records.user_id')
            ->select('u.fullname', 'u.address', 'u.email', 'medical_records.id')
            ->where('medical_records.user_id', $id)
            ->get();

        return response()->json($records, 200);
    }

    public function editMedicalRecord(Request $request, $id)
    {
        $request->validate(
            [
                'weight' => 'required',
                'height' => 'required',
                'patient_id' => 'required',
            ]
        );

        $record = $this->record->find($id);
        $patient = $this->patient->find($request->patient_id);

        $record->update([
            'weight' => $request->weight,
            'height' => $request->height,
        ]);

        $patient->update([
            'weight' => $request->weight,
            'height' => $request->height,
        ]);

        return response()->json(['message' => "Successfully Edited."], 200);
    }
}
