<?php

namespace App\Http\Controllers\Admin\Pengelolaan;

use App\Http\Controllers\Controller;
use App\Imports\KelasImport;
use App\Models\Kelas;
use App\Models\TahunAjar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class AdminKelasController extends Controller
{
    public function index($id)
    {
        $data['tahunAjar'] = TahunAjar::find($id);

        return view('admin.pengelolaan.kelas.index', [], ['menu_type' => 'pengelolaan-tahun-ajar'])->with($data);
    }

    public function data(Request $request, $id)
    {
        $length = intval($request->input('length', 15));
        $start = intval($request->input('start', 0));
        $search = $request->input('search');
        $columns = $request->input('columns');
        $order = $request->input('order');

        $data = Kelas::query()->where('tahun_ajar_id', $id)->with('tahunAjar');

        if (!empty($order)) {
            $order = $order[0];
            $orderBy = $order['column'];
            $orderDir = $order['dir'];

            if (isset($columns[$orderBy]['data'])) {
                $data->orderBy($columns[$orderBy]['data'], $orderDir);
            } else {
                $data->orderBy('nama', 'asc');
            }
        } else {
            $data->orderBy('nama', 'asc');
        }

        $count = $data->count();
        $countFiltered = $count;

        if (!empty($search['value'])) {
            $data->where('nama', 'LIKE', '%' . $search['value'] . '%');
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


    public function dataById($id, $kelasId)
    {
        $kelas = Kelas::where('id', $kelasId)->where('tahun_ajar_id', $id)->with('tahunAjar')->first();

        return response()->json($kelas);
    }

    public function addKelas(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $kelas = new Kelas();
        $kelas->nama = $request->nama;
        $kelas->tahun_ajar_id = $id;
        $kelas->save();

        return redirect()->back()->with('success', 'Data kelas berhasil ditambahkan');
    }

    public function editKelas(Request $request, $id, $kelasId)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $kelas = Kelas::find($kelasId);
        $kelas->nama = $request->nama;
        $kelas->tahun_ajar_id = $id;
        $kelas->save();

        return redirect()->back()->with('success', 'Data kelas berhasil diubah');
    }

    public function deleteKelas($id, $kelasId)
    {
        $kelas = Kelas::where('id', $kelasId)->where('tahun_ajar_id', $id)->first();
        $kelas->delete();

        return redirect()->back()->with('success', 'Data kelas berhasil dihapus');
    }

    public function importKelas(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,xls,xlsx'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        Excel::import(new KelasImport($id), $request->file('file'));
        return redirect()->back()->with('success', 'Data kelas berhasil diimport');
    }
}
