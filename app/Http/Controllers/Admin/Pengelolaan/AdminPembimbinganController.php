<?php

namespace App\Http\Controllers\Admin\Pengelolaan;

use App\Exports\DataPembimbinganExport;
use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Pembimbingan;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class AdminPembimbinganController extends Controller
{
    public function index()
    {
        $data['guru'] = Guru::join('users', 'gurus.user_id', '=', 'users.id')
            ->orderBy('users.nama_lengkap', 'ASC')
            ->select('gurus.*')
            ->get();

        $data['siswa'] = Siswa::whereDoesntHave('pembimbingan')
            ->orderBy('kelas_id', 'ASC')
            ->get();


        return view('admin.pengelolaan.pembimbingan.index', [], ['menu_type' => 'pembimbingan'])->with($data);
    }

    public function addPembimbingan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'siswa_id.*' => 'required',
            'pembimbing_id' => 'required',
            'guru_mapel_pkl_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        if ($request->siswa_id == null) {
            return redirect()->back()->with('error', 'Siswa tidak boleh kosong');
        } else {
            foreach ($request->siswa_id as $siswaId) {
                Pembimbingan::create([
                    'siswa_id' => $siswaId,
                    'pembimbing_id' => $request->pembimbing_id,
                    'guru_mapel_pkl_id' => $request->guru_mapel_pkl_id,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Pembimbingan berhasil ditambahkan');
    }

    public function editPembimbingan(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'pembimbing_id' => 'required',
            'guru_mapel_pkl_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $pembimbingan = Pembimbingan::find($id);
        $pembimbingan->pembimbing_id = $request->pembimbing_id;
        $pembimbingan->guru_mapel_pkl_id = $request->guru_mapel_pkl_id;
        $pembimbingan->save();

        return redirect()->back()->with('success', 'Pembimbingan berhasil diubah');
    }

    public function data(Request $request)
    {
        $length = intval($request->input('length', 15));
        $start = intval($request->input('start', 0));
        $search = $request->input('search');
        $columns = $request->input('columns');
        $order = $request->input('order');

        $data = Pembimbingan::query()->with(['siswa', 'siswa.user', 'siswa.penempatan.instansi', 'pembimbing.user', 'guruMapelPkl.user']);

        if (!empty($order)) {
            $order = $order[0];
            $orderBy = $order['column'];
            $orderDir = $order['dir'];

            if (isset($columns[$orderBy]['data'])) {
                $columnName = $columns[$orderBy]['data'];

                if ($columnName == 'siswa.user.nama_lengkap') {
                    $data->join('siswas', 'pembimbingans.siswa_id', '=', 'siswas.id')
                        ->join('users', 'siswas.user_id', '=', 'users.id')
                        ->orderBy('users.nama_lengkap', $orderDir)
                        ->select('pembimbingans.*', 'users.nama_lengkap as user_name');
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
                    ->orWhereHas('siswa.penempatan.instansi', function ($query) use ($searchValue) {
                        $query->where('nama', 'LIKE', '%' . $searchValue . '%');
                    })
                    ->orWhereHas('pembimbing.user', function ($query) use ($searchValue) {
                        $query->where('nama_lengkap', 'LIKE', '%' . $searchValue . '%');
                    })
                    ->orWhereHas('guruMapelPkl.user', function ($query) use ($searchValue) {
                        $query->where('nama_lengkap', 'LIKE', '%' . $searchValue . '%');
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
        $pembimbingan = Pembimbingan::with(['siswa', 'siswa.user', 'siswa.penempatan.instansi', 'pembimbing.user', 'guruMapelPkl.user'])->find($id);

        return response()->json($pembimbingan);
    }

    public function deletePembimbingan($id)
    {
        $pembimbingan = Pembimbingan::find($id);
        $pembimbingan->delete();

        return redirect()->back()->with('success', 'Pembimbingan berhasil dihapus');
    }

    public function exportPembimbingan()
    {
        return Excel::download(new DataPembimbinganExport, 'data_pembimbingan.xlsx');
    }
}
