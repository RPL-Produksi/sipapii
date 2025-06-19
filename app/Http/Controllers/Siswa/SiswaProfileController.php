<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\TahunAjar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;

class SiswaProfileController extends Controller
{
    public function index()
    {
        $data['siswa'] = Auth::user()->siswa;
        $data['kelas'] = Kelas::orderBy('nama', 'ASC')->get();
        $data['tahunAjar'] = TahunAjar::orderBy('tahun_ajar', 'ASC')->get();

        return view('siswa.profile', [], ['menu_type' => 'profile'])->with($data);
    }

    public function addFields(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'nomor_wa' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::where('id', Auth::user()->id)->first();
        $user->email = $request->email;
        $user->siswa->nomor_wa = $request->nomor_wa;
        $user->save();
        $user->siswa->save();

        return redirect()->back()->with('success', 'Another fields berhasil ditambahkan');
    }

    public function changeProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'foto_profile' => 'required|file'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::where('id', Auth::user()->id)->first();

        if ($request->hasFile('foto_profile')) {
            $file = $request->foto_profile;

            $image = ImageManager::gd()->read($file);
            $image = $image->resize(720, 720, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $storePath = 'foto-profile' . '/' . $user->id;
            $fileName = $file->hashName();

            $storedPath = $storePath . '/' . $fileName;
            Storage::put($storedPath, (string) $image->encode());

            $filePath = Storage::url($storedPath);

            $user->profile_picture = $filePath;
        }

        $user->save();
        return redirect()->back()->with('success', 'Foto profile berhasil diubah');
    }
}
