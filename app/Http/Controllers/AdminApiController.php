<?php

namespace App\Http\Controllers;

use App\Http\Resources\ScholarResource;
use App\Models\Admin;
use App\Models\Scholar;
use App\Models\Supervisor;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class AdminApiController extends Controller
{
    function getAllAdmins(Request $request)
    {
        $query = Admin::query()
            ->nameLike($request->name)
            ->isActive($request->is_active);

        if ($request->filled('sort_by')) {
            $query->orderBy($request->sort_by, $request->get('sort_order', 'asc'));
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $admins = $query->paginate($request->get('per_page', 15));

        return response()->json($admins);
    }

    public function getAllScholars(Request $request)
    {
        $scholars = Scholar::query()
            ->with([
                'activeSupervisor.supervisor',
                'activeSupervisor.assignedBy'
            ])
            ->when($request->supervisor_id, fn($q, $supervisorId) => $q->fromSupervisor($supervisorId))
            ->when($request->start_date && $request->end_date, fn($q) => $q->registeredBetween($request->start_date, $request->end_date)
            )
            ->orderBy(
                $request->get('sort_by', 'registration_date'),
                $request->get('sort_dir', 'desc')
            )
            ->paginate($request->get('per_page', env('DEFAULT_PER_PAGE', 15)));

        // TODO: Progress calculation and column names list
        return ScholarResource::collection($scholars);
    }

    function getAllSupervisors(Request $request)
    {
        $query = Supervisor::query()
            ->type($request->type)
            ->nameLike($request->name)
            ->isActive($request->is_active);

        if ($request->filled('sort_by')) {
            $query->orderBy($request->sort_by, $request->get('sort_order', 'asc'));
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $supervisors = $query->paginate($request->get('per_page', env('DEFAULT_PER_PAGE', 15)));

        return response()->json($supervisors);
    }

    function getAdminDetails(Request $request)
    {
        // TODO
    }

    function getScholarDetails(Request $request)
    {
        // TODO
    }

    function getSupervisorDetails(Request $request)
    {
        // TODO
    }
}
