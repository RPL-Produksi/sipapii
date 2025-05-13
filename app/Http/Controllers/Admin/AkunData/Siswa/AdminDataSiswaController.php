<?php

namespace App\Http\Controllers\Admin\AkunData\Siswa;

use App\Http\Controllers\Controller;
use App\Imports\SiswaImport;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class AdminDataSiswaController extends Controller
{
    public function index()
    {
        $data['kelas'] = Kelas::orderBy('nama', 'ASC')->get();
        $data['tahunAjar'] = TahunAjar::orderBy('tahun_ajar', 'ASC')->get();

        return view('admin.akun-data.siswa.data-siswa.index', [], ['menu_type' => 'siswa', 'submenu_type' => 'siswa-data'])->with($data);
    }

    public function form($id = null)
    {
        $data['siswa'] = Siswa::where('user_id', $id)->first();
        $data['kelas'] = Kelas::orderBy('nama', 'ASC')->get();
        $data['tahunAjar'] = TahunAjar::orderBy('tahun_ajar', 'ASC')->get();

        return view('admin.akun-data.siswa.data-siswa.form', [], ['menu_type' => 'siswa', 'submenu_type' => 'siswa-data'])->with($data);
    }

    public function store(Request $request, $id = null)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'nis' => 'required',
            'jenis_kelamin',
            'kelas_id' => 'required',
            'tahun_ajar_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $user = User::updateOrCreate(['id' => @$id], [
            'nama_lengkap' => $request->nama_lengkap,
            'username' => $request->nis,
            'password' => Str::random(8),
            'role' => 'siswa',
        ]);

        if ($user) {
            Siswa::updateOrCreate(['user_id' => $user->id], [
                'user_id' => $user->id,
                'nis' => $request->nis,
                'jenis_kelamin' => $request->jenis_kelamin,
                'kelas_id' => $request->kelas_id,
                'tahun_ajar_id' => $request->tahun_ajar_id,
            ]);
        }

        return redirect()->route('admin.siswa')->with('success', 'Data siswa berhasil disimpan');
    }

    public function data(Request $request)
    {
        $length = intval($request->input('length', 15));
        $start = intval($request->input('start', 0));
        $search = $request->input('search');
        $columns = $request->input('columns');
        $order = $request->input('order');

        $data = User::query()
            ->where('role', 'siswa')
            ->join('siswas', 'users.id', '=', 'siswas.user_id')
            ->join('kelas', 'siswas.kelas_id', '=', 'kelas.id')
            ->select('users.*')
            ->with('siswa', 'siswa.kelas', 'siswa.tahunAjar');

        if (!empty($order)) {
            $order = $order[0];
            $orderBy = $order['column'];
            $orderDir = $order['dir'];

            if (isset($columns[$orderBy]['data'])) {
                $data->orderBy($columns[$orderBy]['data'], $orderDir);
            } else {
                $data->orderBy('kelas.nama', 'asc');
            }
        } else {
            $data->orderBy('kelas.nama', 'asc');
        }

        $count = $data->count();
        $countFiltered = $count;

        if (!empty($search['value'])) {
            $data->where('users.nama_lengkap', 'LIKE', '%' . $search['value'] . '%');
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
        $siswa = User::where('id', $id)->where('role', 'siswa')->first();

        return response()->json($siswa);
    }

    public function delete($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();
        }

        return redirect()->route('admin.siswa')->with('success', 'Data siswa berhasil dihapus');
    }

    public function importSiswa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xls,xlsx',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        Excel::import(new SiswaImport(), $request->file('file'));
        return redirect()->route('admin.siswa')->with('success', 'Data siswa berhasil diimport');
    }
}
