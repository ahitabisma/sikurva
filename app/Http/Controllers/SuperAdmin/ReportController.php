<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $users = DB::table('users')
            ->leftJoin('instansis', 'users.instansi_id', '=', 'instansis.id')
            ->leftJoin('patients as patients_by_user', 'users.id', '=', 'patients_by_user.created_by') // Gunakan created_by, bukan user_id
            ->select(
                'users.id',
                'users.instansi_id',
                'users.name',
                'instansis.name as instansi_name',
                DB::raw('COUNT(DISTINCT patients_by_user.id) as total_patients_by_user'),
                DB::raw(
                    '
            (SELECT COUNT(DISTINCT p.id)
             FROM patients p
             INNER JOIN users u ON p.created_by = u.id
             WHERE u.instansi_id = instansis.id) as total_patients_by_instansi'
                )
            )
            ->groupBy('users.id', 'instansis.name', 'users.instansi_id', 'instansis.id', 'users.name')
            ->paginate(25);

        return view('super-admin.report.index')->with([
            'title' => 'Report',
            'users' => $users
        ]);
    }
}
