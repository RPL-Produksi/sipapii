<?php

namespace App\Http\Controllers\Admin\Pengelolaan;

use App\Http\Controllers\Controller;
use App\Models\TahunAjar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminTahunAjarController extends Controller
{
    public function index()
    {
        return view('admin.pengelolaan.tahun-ajar.index', [], ['menu_type' => 'pengelolaan-tahun-ajar']);
    }

    public function data(Request $request)
    {
        $length = intval($request->input('length', 15));
        $start = intval($request->input('start', 0));
        $search = $request->input('search');
        $columns = $request->input('columns');
        $order = $request->input('order');

        $data = TahunAjar::query();

        if (!empty($order)) {
            $order = $order[0];
            $orderBy = $order['column'];
            $orderDir = $order['dir'];

            if (isset($columns[$orderBy]['data'])) {
                $data->orderBy($columns[$orderBy]['data'], $orderDir);
            } else {
                $data->orderBy('tahun_ajar', 'asc');
            }
        } else {
            $data->orderBy('tahun_ajar', 'asc');
        }

        $count = $data->count();
        $countFiltered = $count;

        if (!empty($search['value'])) {
            $data->where('tahun_ajar', 'LIKE', '%' . $search['value'] . '%');
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
        $tahunAjar = TahunAjar::find($id);

        return response()->json($tahunAjar);
    }

    public function addTahunAjar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun_ajar' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }

        $tahunAjar = new TahunAjar();
        $tahunAjar->tahun_ajar = $request->tahun_ajar;
        $tahunAjar->save();

        return redirect()->back()->with('success', 'Data tahun ajar berhasil disimpan');
    }

    public function editTahunAjar(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tahun_ajar' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }

        $tahunAjar = TahunAjar::find($id);
        $tahunAjar->tahun_ajar = $request->tahun_ajar;
        $tahunAjar->save();

        return redirect()->back()->with('success', 'Data tahun ajar berhasil diubah');
    }

    public function deleteTahunAjar($id)
    {
        $tahunAjar = TahunAjar::find($id);
        $tahunAjar->delete();

        return redirect()->back()->with('success', 'Data tahun ajar berhasil dihapus');
    }
}
