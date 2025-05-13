<?php

namespace App\Http\Controllers\Admin\Pengelolaan;

use App\Http\Controllers\Controller;
use App\Imports\PenempatanImport;
use App\Models\Instansi;
use App\Models\Menempati;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class AdminPenempatanController extends Controller
{
    public function index()
    {
        $data['siswa'] = Siswa::whereDoesntHave('penempatan')->orderBy('kelas_id', 'ASC')->get();
        $data['instansi'] = Instansi::orderBy('nama', 'ASC')->get();

        return view('admin.pengelolaan.penempatan.index', [], ['menu_type' => 'penempatan'])->with($data);
    }

    public function addPenempatan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'siswa_id.*' => 'required',
            'instansi_id' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }

        if ($request->siswa_id == null) {
            return redirect()->back()->with('error', 'Pilih siswa terlebih dahulu');
        } else {
            foreach ($request->siswa_id as $siswaId) {
                Menempati::create([
                    'siswa_id' => $siswaId,
                    'instansi_id' => $request->instansi_id
                ]);
            }
        }

        return redirect()->back()->with('success', 'Berhasil menempatkan siswa');
    }

    public function editPenempatan(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'instansi_id' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }

        $penempatan = Menempati::find($id);
        $penempatan->instansi_id = $request->instansi_id;
        $penempatan->save();

        return redirect()->back()->with('success', 'Berhasil mengubah penempatan siswa');
    }

    public function data(Request $request)
    {
        $length = intval($request->input('length', 15));
        $start = intval($request->input('start', 0));
        $search = $request->input('search');
        $columns = $request->input('columns');
        $order = $request->input('order');

        $data = Menempati::query()->with(['siswa', 'siswa.user', 'instansi']);

        if (!empty($order)) {
            $order = $order[0];
            $orderBy = $order['column'];
            $orderDir = $order['dir'];

            if (isset($columns[$orderBy]['data'])) {
                $columnName = $columns[$orderBy]['data'];

                if ($columnName == 'siswa.user.nama_lengkap') {
                    $data->join('siswas', 'menempatis.siswa_id', '=', 'siswas.id')
                        ->join('users', 'siswas.user_id', '=', 'users.id')
                        ->orderBy('users.nama_lengkap', $orderDir)
                        ->select('menempatis.*', 'users.nama_lengkap as user_name');
                } elseif ($columnName == 'instansi.nama') {
                    $data->join('instansis', 'menempatis.instansi_id', '=', 'instansis.id')
                        ->orderBy('instansis.nama', $orderDir)
                        ->select('menempatis.*', 'instansis.nama as instansi_name');
                } else {
                    $data->orderBy($columnName, $orderDir);
                }
            } else {
                $data->orderBy('created_at', 'desc');
            }
        } else {
            $data->orderBy('created_at', 'desc');
        }


        $count = $data->count();
        $countFiltered = $count;

        if (!empty($search['value'])) {
            $searchValue = $search['value'];

            $data->where(function ($query) use ($searchValue) {
                $query->where('created_at', 'LIKE', '%' . $searchValue . '%')
                    ->orWhereHas('siswa.user', function ($query) use ($searchValue) {
                        $query->where('nama_lengkap', 'LIKE', '%' . $searchValue . '%');
                    })
                    ->orWhereHas('instansi', function ($query) use ($searchValue) {
                        $query->where('nama', 'LIKE', '%' . $searchValue . '%');
                    });
            });

            $countFiltered = $data->count();
        }


        $data = $data->skip($start)->take($length)->get();

        $response = [
            "draw" => intval($request->input('draw', 1)),
            "recordsTotal" => $count,
            "recordsFiltered" => $countFiltered,
            "limit" => $length,
            "data" => $data
        ];

        return response()->json($response);
    }

    public function dataById($id)
    {
        $penempatan = Menempati::with(['siswa', 'siswa.user', 'instansi'])->where('id', $id)->first();

        return response()->json($penempatan);
    }

    public function deletePenempatan($id)
    {
        $penempatan = Menempati::find($id);
        $penempatan->delete();

        return redirect()->back()->with('success', 'Berhasil menghapus penempatan siswa');
    }

    public function importPenempatan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls,csv,txt|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }

        Excel::import(new PenempatanImport(), $request->file('file'));
        return redirect()->back()->with('success', 'Data penempatan berhasil diimport');
    }
}
