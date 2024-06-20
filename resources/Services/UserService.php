<?php

namespace App\Services;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserService
{
    public function showData($request, $moduleName)
    {
        $term = $request->input('term');
        $show = $request->input('show');
        $roleFetch = ($moduleName === 'user-management' ? 'Administrator' : 'Support');
        $data = [
            'users'     => User::role($roleFetch)
                            ->with('roles')
                            ->when($term, function ($query, $term) {
                                $query->where('name', 'LIKE', '%' . $term . '%')
                                    ->orWhere('email', 'LIKE', '%' . $term . '%');
                            })
                            ->orderBy('id', 'asc')
                            ->paginate($show)
                            ->withQueryString(),
            'roles'     => Role::where('status', 1)->get(),
            'filters'   => $request->only(['term', 'show']),
            'response'  => Session::get('response'),
        ];

        return $data;
    }

    public function storeData($request)
    {
        $request->validated();
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'status'    => $request->status,
        ]);
        $user->assignRole($request->role);
    }

    public function updateData($request)
    {
        $request->validated();

        User::find($request->input('id'))->update([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'status'    => $request->status,
        ]);
        $user_role = User::find($request->input('id'));
        $user_role->syncRoles([]);
        $user_role->assignRole($request->role);
    }

    public function statusData($request)
    {
        User::find($request->input('id'))->update([
            'status' => ($request->status == 1) ? 0 : 1,
        ]);
    }

    public function deleteData($request)
    {
        User::find($request->input('id'))->delete();
    }
}
