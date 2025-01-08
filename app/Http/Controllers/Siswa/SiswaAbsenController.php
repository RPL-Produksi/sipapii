<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Absen;
use App\Models\Instansi;
use App\Models\Jurnal;
use App\Models\Menempati;
use App\Models\Pembimbingan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;

class SiswaAbsenController extends Controller
{
  public function index(Request $request)
  {
    $data['siswa'] = Auth::user();

    if (!Menempati::where('siswa_id', Auth::user()->siswa->id)->first()) {
      return redirect()->route('siswa.dashboard')->with('error', 'Anda belum menempati instansi');
    }

    if ($request->query('type') != 'masuk' && $request->query('type') != 'pulang') {
      return redirect()->route('siswa.dashboard')->with('error', 'Invalid type absen');
    }

    $absenHariIni = Absen::where('siswa_id', Auth::user()->siswa->id)
      ->where('tanggal', Carbon::now()->format('d-m-Y'))
      ->first();

    if ($request->query('type') == 'masuk' && $absenHariIni && $absenHariIni->jam_masuk) {
      return redirect()->route('siswa.dashboard')->with('error', 'Anda sudah melakukan absen masuk');
    }

    if ($request->query('type') == 'pulang') {
      if (!$absenHariIni || !$absenHariIni->jam_masuk) {
        return redirect()->route('siswa.dashboard')->with('error', 'Anda belum melakukan absen masuk');
      }

      if ($absenHariIni->jam_pulang) {
        return redirect()->route('siswa.dashboard')->with('error', 'Anda sudah melakukan absen pulang');
      }
    }

    return view('siswa.absen', [], ['menu_type' => 'absen'])->with($data);
  }

  public function absen(Request $request)
  {

    // dd($request->all());
    $id = $request->query('absen_id');
    $type = $request->query('type');

    if (!in_array($type, ['masuk', 'pulang'])) {
      return redirect()->route('siswa.dashboard')->with('error', 'Invalid type absen');
    }

    $validator = Validator::make($request->all(), [
      'camera_data' => 'required|file',
      'lat' => 'required_if:type,masuk|numeric|between:-90,90',
      'long' => 'required_if:type,masuk|numeric|between:-180,180',
      'status' => 'required_if:type,pulang|in:Hadir,Sakit,Izin',
      'jurnal' => 'required_if:type,pulang',
    ]);

    $validator->sometimes('alasan', 'required', function ($input) {
      return $input->type === 'pulang' && in_array($input->status, ['Sakit', 'Izin']);
    });

    if ($validator->fails()) {
      return redirect()->route('siswa.dashboard')->withErrors($validator->errors())->withInput($request->all());
    }

    $siswaId = Auth::user()->siswa->id;
    $menempati = Menempati::where('siswa_id', $siswaId)->with('instansi')->first();
    $pembimbing = Pembimbingan::where('siswa_id', $siswaId)->first();
    $jarak = $this->calculateDistance($request->lat, $request->long, $menempati->instansi->latitude, $menempati->instansi->longitude);

    $input = $request->all();
    $input['siswa_id'] = $siswaId;
    $input['tanggal'] = Carbon::now()->format('d-m-Y');
    $input['status'] = $request->status;
    $input['alasan'] = $request->alasan;

    if ($request->hasFile('camera_data')) {
      $file = $request->camera_data;

      $image = ImageManager::gd()->read($file);
      $image = $image->resize(720, 720, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
      });

      $tahunAjar = Auth::user()->siswa->tahunAjar->tahun_ajar;;

      $storePath = str_replace('/', '-', $tahunAjar) . '/' . 'absen' . '/' . $siswaId . '/' . Carbon::now()->format('d-m-Y');
      $fileName = $file->hashName();

      $storedPath = $storePath . '/' . $fileName;
      Storage::put($storedPath, (string) $image->encode());

      $filePath = Storage::url($storedPath);

      $input[$type == 'masuk' ? 'foto_masuk' : 'foto_pulang'] = $filePath;
    }

    $input[$type == 'masuk' ? 'jam_masuk' : 'jam_pulang'] = Carbon::now()->format('H:i');
    if ($type == 'masuk') {
      $input['latitude'] = $request->lat;
      $input['longitude'] = $request->long;
      $input['jarak'] = $jarak;
    } else {
      $jurnal = new Jurnal();
      $jurnal->siswa_id = $siswaId;
      $jurnal->guru_mapel_pkl_id = $pembimbing->guru_mapel_pkl_id;
      $jurnal->tanggal = Carbon::now()->format('d-m-Y');
      $jurnal->deskripsi_jurnal = $request->jurnal;
      $jurnal->save();
    }

    Absen::updateOrCreate(['id' => $id], $input);
    return redirect()->route('siswa.dashboard')->with('success', 'Absen ' . $type . ' berhasil');
  }
}
