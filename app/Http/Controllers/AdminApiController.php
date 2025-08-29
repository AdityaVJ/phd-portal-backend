<?php

namespace App\Http\Controllers;

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
            ->when($request->start_date && $request->end_date, fn($q) =>
            $q->registeredBetween($request->start_date, $request->end_date)
            )
            ->orderBy(
                $request->get('sort_by', 'registration_date'),
                $request->get('sort_dir', 'desc')
            )
            ->paginate($request->get('per_page', 15));

        return response()->json($scholars);
    }

    function getAllSupervisors(Request $request)
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
            case 'supervisor_name':
                $columnName = 'supervisors.name';
                break;
            case 'is_active':
                $columnName = 'supervisors.is_active';
                break;
            case 'type':
                $columnName = 'supervisors.type';
                break;
            case 'supervisor_email':
                $columnName = 'supervisors.email';
                break;
            case 'supervisor_phone':
                $columnName = 'supervisors.phone';
                break;
            default:
                $columnName = 'supervisors.id';
        }

        // Base query with joins
        $query = Supervisor::select(
            'supervisors.*'
        );

        // Apply search
        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('supervisors.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('supervisors.email', 'like', '%' . $searchValue . '%')
                    ->orWhere('supervisors.phone', 'like', '%' . $searchValue . '%')
                    ->orWhere('supervisors.type', 'like', '%' . $searchValue . '%');
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
                "supervisor_type" => $record->type,
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
