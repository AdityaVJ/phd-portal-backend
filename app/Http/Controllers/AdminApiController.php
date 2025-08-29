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
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length");

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column'];
        $columnName = $columnName_arr[$columnIndex]['data'];
        $columnSortOrder = $order_arr[0]['dir'];
        $searchValue = $search_arr['value'];

        // Map frontend column names to DB columns
        switch ($columnName) {
            case 'admin_name':
                $columnName = 'admins.name';
                break;
            case 'is_active':
                $columnName = 'admins.is_active';
                break;
            case 'admin_email':
                $columnName = 'admins.email';
                break;
            default:
                $columnName = 'admins.id';
        }

        // Base query with joins
        $query = Admin::select('admins.*');

        // Apply search
        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('admins.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('admins.email', 'like', '%' . $searchValue . '%');
            });
        }

        // Get filtered record count before pagination
        $totalFiltered = $query->count();

        // Order, paginate and get results
        $records = $query->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        // Prepare data
        $data_arr = [];

        foreach ($records as $record) {
            $data_arr[] = [
                "id" => $record->id,
                "name" => $record->name,
                "email" => $record->email,
                "phone" => $record->phone
            ];
        }

        // Total records (without filters)
        $totalRecords = Scholar::count();

        // Return JSON response
        return response()->json([
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalFiltered,
            "aaData" => $data_arr,
        ]);
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
            ->when($request->name, fn($q, $name) => $q->where('name', 'like', '%' . $name . '%'))
            ->when($request->type, fn($q, $type) => $q->type($type))
            ->when($request->is_active, fn($q, $is_active) => $q->where('is_active', $is_active));

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
