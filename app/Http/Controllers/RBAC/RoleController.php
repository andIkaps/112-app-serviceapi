<?php

namespace App\Http\Controllers\RBAC;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $roles = Role::get();

            return $this->success_json("Successfully get roles", $roles);
        } catch (\Throwable $th) {
            return $this->error_json("Failed to get roles", $th->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|unique:ms_roles',
        ]);

        if ($validator->fails()) {
            return $this->error_json("Faield to create roles", $validator->errors(), 400);
        }

        try {
            $newRoles = Role::create([
                'name' => $request->name,
                'description' => $request->description,
                'created_by' => $request->user()->id
            ]);

            if ($newRoles) {
                return $this->success_json('Successfully create new role', $newRoles);
            }
        } catch (\Throwable $th) {
            return $this->error_json("Faield to create roles", $th->getMessage(), 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $roles)
    {
        return $this->success_json('Successfully create new role', $roles);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $find = Role::where('id', $id)->first();

        if (!$find) {
            return $this->error_json("Roles not Found! ", $find, 404);
        }

        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|min:3',
        ]);

        if ($validator->fails()) {
            return $this->error_json("Faield to update roles", $validator->errors(), 400);
        }

        try {
            $updatedRoles = $find->update([
                'name' => $request->name,
                'description' => $request->description,
                'updated_by' => $request->user()->id
            ]);

            if ($updatedRoles) {
                return $this->success_json('Successfully update role', $updatedRoles);
            }
        } catch (\Throwable $th) {
            return $this->error_json("Faield to update roles", $th->getMessage(), 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $find = Role::where('id', $id)->first();

        if (!$find) {
            return $this->error_json("Roles not Found! ", $find, 404);
        }

        try {
            $delete = $find->delete();

            if ($delete) {
                return $this->success_json('Successfully delete role', $delete);
            }
        } catch (\Throwable $th) {
            return $this->error_json("Failed to delete role ", $th->getMessage(), 500);
        }
    }
}
