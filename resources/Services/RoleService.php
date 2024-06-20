<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;

class RoleService
{
    protected $model;

    public function __construct(Role $roleModel)
    {
        $this->model = $roleModel;
    }

    public function showData($request)
    {
        try {
            DB::beginTransaction();

            $result = [
                'roles' => $this->model->with('permissions')->when($request->term, function ($query, $term) {
                    $query->where('name', 'LIKE', '%' . $term . '%');
                })->orderBy('id', 'asc')->paginate($request->show)->withQueryString(),
                'permissions' => Permission::where('status', 1)->get(),
                'filters' => $request->only(['term', 'show']),
                'response' => Session::get('response'),
            ];

            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function storeData($request)
    {
        try {
            DB::beginTransaction();

            $request->validated();

            $role = $this->model->create([
                'name' => $request->name,
                'status' => $request->status,
            ]);

            $role->syncPermissions($request->selectedOptions);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateData($request)
    {
        try {
            DB::beginTransaction();

            // Validate the request data
            $request->validated();

            // Find the role by ID
            $role = $this->model->find($request->input('id'));

            if ($request->update_type == 'all') {
                // Update the role with specified fields
                $role->update([
                    'name' => $request->name,
                    'status' => $request->status,
                ]);

                // Sync permissions with selected options
                $role->syncPermissions($request->selectedOptions);
            } else {
                // Toggle the status if update_type is not 'all'
                $role->update([
                    'status' => ($request->status == 1) ? 0 : 1,
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteData($request)
    {
        try {
            DB::beginTransaction();
            $this->model->where('id', $request->input('id'))->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function statusData($request)
    {
        $this->model->where('id', $request->input('id'))->update([
            'status' => ($request->status == 1) ? 0 : 1,
        ]);
    }

    public function bulkDelete($request)
    {
        try {
            DB::beginTransaction();
            $role = $this->model->whereIn('id', array_values($request->all()));
            $role->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred while deleting bulk data.'], 500);
        }
    }
}
