<?php

namespace App\Http\Controllers;

use App\Models\EmployeeKPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeKPIController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = EmployeeKPI::with('employee')->get();

            return $this->success_json("Successfully get employee kpi", $data);
        } catch (\Throwable $th) {
            return $this->error_json("Failed to get employee kpi", $th->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id'      => 'required',
            'period'           => 'required',
            'year'             => 'required|numeric',
            'calm'             => 'required',
            'fast'             => 'required',
            'dispatch'         => 'required',
            'sosialization'    => 'required',
            'greating_opening' => 'required',
            'greating_closing' => 'required',
            'activity'         => 'required',
            'loyal'            => 'required',
            'late'             => 'required',
            'clean'            => 'required',
            'take_break'       => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error_json("Failed to create KPI", $validator->errors(), 400);
        }

        try {
            $create = EmployeeKPI::create([
                'employee_id'      => $request->employee_id,
                'period'           => $request->period,
                'year'             => $request->year,
                'calm'             => $request->calm,
                'fast'             => $request->fast,
                'dispatch'         => $request->dispatch,
                'sosialization'    => $request->sosialization,
                'greating_opening' => $request->greating_opening,
                'greating_closing' => $request->greating_closing,
                'activity'         => $request->activity,
                'loyal'            => $request->loyal,
                'late'             => $request->late,
                'clean'            => $request->clean,
                'take_break'       => $request->take_break,
            ]);

            if ($create) {
                return $this->success_json("Successfully create Employees KPI", $create);
            }
        } catch (\Throwable $th) {
            return $this->error_json("Failed to create Employee KPI", $th->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeKPI $employeeKPI)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $find = EmployeeKPI::where('id', $id)->first();

        if (!$find) {
            return $this->error_json("Period Not Found", $find, 404);
        }

        $validator = Validator::make($request->all(), [
            'employee_id'      => 'required',
            'period'           => 'required',
            'year'             => 'required|numeric',
            'calm'             => 'required',
            'fast'             => 'required',
            'dispatch'         => 'required',
            'sosialization'    => 'required',
            'greating_opening' => 'required',
            'greating_closing' => 'required',
            'activity'         => 'required',
            'loyal'            => 'required',
            'late'             => 'required',
            'clean'            => 'required',
            'take_break'       => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error_json("Failed to create KPI", $validator->errors(), 400);
        }


        try {
            $update = $find->update([
                'employee_id'      => $request->employee_id,
                'period'           => $request->period,
                'year'             => $request->year,
                'calm'             => $request->calm,
                'fast'             => $request->fast,
                'dispatch'         => $request->dispatch,
                'sosialization'    => $request->sosialization,
                'greating_opening' => $request->greating_opening,
                'greating_closing' => $request->greating_closing,
                'activity'         => $request->activity,
                'loyal'            => $request->loyal,
                'late'             => $request->late,
                'clean'            => $request->clean,
                'take_break'       => $request->take_break,
            ]);

            if ($update) {
                return $this->success_json("Successfully update Employees KPI", $update);
            }
        } catch (\Throwable $th) {
            return $this->error_json("Failed to create Employee KPI", $th->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeKPI $employeeKPI)
    {
        //
    }
}
