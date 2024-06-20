<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Services\PermissionService;
use App\Http\Requests\PermissionRequest;

class PermissionController extends Controller
{
    protected $permissionService;

    protected $lastSegment;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
        $this->lastSegment = request()->segment(count(request()->segments()));
    }

    public function index(Request $request)
    {
        $lastSegment = $this->lastSegment;

        $data = $this->permissionService->showData($request, $lastSegment);
        return Inertia::render("admin/$lastSegment", [
            'permissions' => $data['permissions'],
            // 'agents' => $data['agents'],
            'filters' => $data['filters'],
            'response' => $data['response'],
            'query_params' => $request->query(),

        ]);
    }

    public function store(PermissionRequest $request)
    {
        $this->permissionService->storeData($request);

        $queryParam = $request->query_params;

        return redirect()->route("$this->lastSegment.index", $queryParam)->with('response', 'success');
    }

    public function update(PermissionRequest $request, string $id)
    {
        if ($request->has('id')) {
            $this->permissionService->updateData($request);

            // Retrieve current query parameters
            $queryParam = $request->query_params;

            // Generate the route name dynamically
            $routeName = request()->segment(2) . ".index";

            // Redirect with query parameters
            return redirect()->route($routeName, $queryParam)->with('response', 'success');
        }
    }

    public function destroy(Request $request)
    {
        if ($request->has('id')) {
            $this->permissionService->deleteData($request);

            $routeName = request()->segment(2) . ".index";

            // Retrieve current query parameters
            $queryParam = $request->query_params;

            return redirect()->route($routeName, $queryParam)->with('response', 'success');
        }
    }

    public function status(Request $request)
    {
        if ($request->has('id')) {
            $this->permissionService->statusData($request);

            // Constructing the query parameters
            $queryParams = http_build_query([
                'agentId' => $request->agent_id
            ]);

            // Redirecting with the query parameters
            return redirect()->route(request()->segment(2) . '.index', $queryParams)->with('response', 'success');
        }
    }
}
