<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Services\UserManagementService;
use App\Http\Requests\UserManagementRequest;

class UserManagementController extends Controller
{
    protected $userServices;

    protected $lastSegment;

    public function __construct(UserManagementService $userServices)
    {
        $this->userServices = $userServices;
        $this->lastSegment = request()->segment(count(request()->segments()));
    }

    public function index(Request $request)
    {
        $lastSegment = $this->lastSegment;

        $data = $this->userServices->showData($request, $lastSegment);
        return Inertia::render("admin/$lastSegment", [
            'users' => $data['users'],
            'filters' => $data['filters'],
            'roles' => $data['roles'],
            'response' => $data['response'],
        ]);
    }

    public function store(UserManagementRequest $request)
    {
        $this->userServices->storeData($request);
        return redirect()->route("$this->lastSegment.index")->with('response', 'success');
    }

    public function update(UserManagementRequest $request, string $id)
    {
        if ($request->has('id')) {
            $this->userServices->updateData($request);
            return redirect()->route("$this->lastSegment.index")->with('response', 'success');
        }
    }

    public function destroy(Request $request)
    {
        if ($request->has('id')) {
            $this->userServices->deleteData($request);
            return redirect()->route("$this->lastSegment.index")->with('response', 'success');
        }
    }

    public function status(Request $request)
    {
        if ($request->has('id')) {
            $this->userServices->statusData($request);
            return redirect()->route("$this->lastSegment.index")->with('response', 'success');
        }
    }
}
