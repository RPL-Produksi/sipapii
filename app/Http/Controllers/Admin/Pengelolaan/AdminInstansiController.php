<?php

namespace App\Http\Controllers\Admin\Pengelolaan;

use App\Http\Controllers\Controller;
use App\Imports\InstansiImport;
use App\Models\Instansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class AdminInstansiController extends Controller
{
    public function index()
    {
        return view('admin.pengelolaan.instansi.index', [], ['menu_type' => 'pengelolaan-instansi']);
    }

    public function form($id = null)
    {
        $data['instansi'] = Instansi::find($id);

        return view('admin.pengelolaan.instansi.form', [], ['menu_type' => 'pengelolaan-instansi'])->with($data);
    }

    public function store(Request $request, $id = null)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'alamat' => 'required',
            'domisili' => 'required:in:Dalam Kota,Luar Kota',
            'lat' => 'required|numeric|between:-90,90',
            'long' => 'required|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }

        $input = $request->all();
        $input['nama'] = $request->nama;
        $input['alamat'] = $request->alamat;
        $input['domisili'] = $request->domisili;
        $input['latitude'] = $request->lat;
        $input['longitude'] = $request->long;

        Instansi::updateOrCreate(['id' => @$id], $input);

        return redirect()->route('admin.pengelolaan.instansi')->with('success', 'Data instansi berhasil disimpan');
    }

    public function data(Request $request)
    {
        $length = intval($request->input('length', 15));
        $start = intval($request->input('start', 0));
        $search = $request->input('search');
        $columns = $request->input('columns');
        $order = $request->input('order');

        $data = Instansi::query();

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

    public function delete($id)
    {
        $instansi = Instansi::find($id);

        if ($instansi) {
            $instansi->delete();
        }

        return redirect()->route('admin.pengelolaan.instansi')->with('success', 'Data instansi berhasil dihapus');
    }

    public function importInstansi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xls,xlsx,csv'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }

        Excel::import(new InstansiImport(), $request->file('file'));
        return redirect()->back()->with('success', 'Data instansi berhasil diimport');
    }
}
