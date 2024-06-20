<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Services\RoleService;
use App\Http\Requests\RoleRequest;

class RoleController extends Controller
{
    const MODULE_NAME = 'roles';
    const TEMPLATE_NAME = 'Roles';

    protected $permissionService;

    protected $lastSegment;

    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
        $this->lastSegment = request()->segment(count(request()->segments()));
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $lastSegment = $this->lastSegment;

        $data = $this->roleService->showData($request);
        return Inertia::render("admin/$lastSegment", [
            'roles' => $data['roles'],
            'permissions' => $data['permissions'],
            'filters' => $data['filters'],
            'response' => $data['response'],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        $this->roleService->storeData($request);

        $queryParam = $request->query_params;

        return redirect()->route("$this->lastSegment.index", $queryParam)->with('response', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, string $id)
    {
        if ($request->has('id')) {
            $this->roleService->updateData($request);

            // Retrieve current query parameters
            $queryParam = $request->query_params;

            // Generate the route name dynamically
            $routeName = request()->segment(2) . ".index";

            return redirect()->route($routeName, $queryParam)->with('response', 'success');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if ($request->has('id')) {
            $this->roleService->deleteData($request);

            $routeName = request()->segment(2) . ".index";

            // Retrieve current query parameters
            $queryParam = $request->query_params;

            return redirect()->route($routeName, $queryParam)->with('response', 'success');
        }
    }

    public function status(Request $request)
    {
        if ($request->has('id')) {
            $this->roleService->statusData($request);

            // Constructing the query parameters
            $queryParams = http_build_query([
                'agentId' => $request->agent_id
            ]);

            // Redirecting with the query parameters
            return redirect()->route(request()->segment(2) . '.index', $queryParams)->with('response', 'success');
        }
    }

    /**
     * Remove the bulk resource from storage.
     */
    public function bulkDelete(Request $request)
    {

        $this->roleService->bulkDelete($request);
        return redirect()->route(self::MODULE_NAME . '.index')->with('response', 'success');
    }
}
