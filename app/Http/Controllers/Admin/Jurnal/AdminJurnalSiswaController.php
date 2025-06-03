<?php

namespace App\Http\Controllers\Admin\Jurnal;

use App\Http\Controllers\Controller;
use App\Models\Jurnal;
use Illuminate\Http\Request;

class AdminJurnalSiswaController extends Controller
{
    public function index()
    {
        return view('admin.jurnal.jurnal-siswa.index', [], ['menu_type' => 'jurnal', 'submenu_type' => 'jurnal-siswa']);
    }

    public function data(Request $request)
    {
        $length = intval($request->input('length', 15));
        $start = intval($request->input('start', 0));
        $search = $request->input('search');
        $columns = $request->input('columns');
        $order = $request->input('order');
        $type = $request->input('type');

        $query = Jurnal::with('siswa.user');

        if ($type === 'belum-validasi') {
            $query->where('validasi', 'Belum Validasi');
        }

        if (!empty($search['value'])) {
            $keyword = '%' . $search['value'] . '%';
            $query->whereHas('siswa.user', function ($q) use ($keyword) {
                $q->where('nama_lengkap', 'like', $keyword);
            });
        }

        $query->orderBy('created_at', 'desc');

        if (!empty($order)) {
            $orderColumn = $columns[$order[0]['column']]['data'];
            $orderDir = $order[0]['dir'];

            if ($orderColumn === 'nama_lengkap') {
                $query = $query
                    ->join('siswa', 'jurnal.siswa_id', '=', 'siswa.id')
                    ->join('users', 'siswa.user_id', '=', 'users.id')
                    ->orderBy('users.nama_lengkap', $orderDir)
                    ->select('jurnal.*')
                    ->with('siswa.user');
            } else {
                $query->orderBy($orderColumn, $orderDir);
            }
        }

        $count = Jurnal::count();
        $countFiltered = $query->count();

        $data = $query->skip($start)->take($length)->get();

        return response()->json([
            'draw' => intval($request->input('draw', 1)),
            'recordsTotal' => $count,
            'recordsFiltered' => $countFiltered,
            'limit' => $length,
            'data' => $data,
        ]);
    }
}
