<?php

namespace App\Services;

use App\Models\User;
use App\Models\Agent;
use App\Models\CmsMessage;
use App\Models\Config;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;

class PermissionService
{
    protected $model;

    public function __construct(Permission $config)
    {
        $this->model = $config;
    }

    public function showData($request, $moduleName)
    {
        $term = $request->input('term');
        $show = $request->input('show');
        // $agentIdFilter = $request->agentId;

        $data = [
            'permissions'     => $this->model
                ->when($term, function ($query, $term) {
                    $query->where('name', 'LIKE', '%' . $term . '%')
                    ->orWhere('module', 'LIKE', '%' . $term . '%');
                })
                // ->when(!empty($agentIdFilter), function ($query, $term) use ($agentIdFilter) {
                //     $query->where('agent_id', $agentIdFilter);
                // })
                ->orderBy('id', 'asc')
                ->paginate($show)
                ->withQueryString(),
            'filters'   => $request->only(['term', 'show']),
            'response'  => Session::get('response'),
            // 'agents'  => Agent::get(),
        ];
        return $data;
    }

    public function storeData($request)
    {
        $request->validated();
        $dataCreated = $this->model->create([
            `name` => $request->name,
            `guard_name` => $request->guard_name,
            `module` => $request->module,
            `status` => $request->status,
        ]);
    }

    public function updateData($request)
    {
        $request->validated();

        $this->model->find($request->input('id'))->update([
            `name` => $request->name,
            `guard_name` => $request->guard_name,
            `module` => $request->module,
            `status` => $request->status,
        ]);
    }

    public function statusData($request)
    {
        $this->model->find($request->input('id'))->update([
            'status' => ($request->status == 1) ? 0 : 1,
        ]);
    }

    public function deleteData($request)
    {
        $this->model->find($request->input('id'))->delete();
    }
}
