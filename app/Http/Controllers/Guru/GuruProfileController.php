<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;

class GuruProfileController extends Controller
{
    public function index()
    {
        $data['guru'] = Auth::user()->guru;

        return view('guru.profile', [], ['menu_type' => 'profile'])->with($data);
    }

    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'nullable|email',
            'nomor_wa' => 'required|digits_between:10,15',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }

        $nomorWa = preg_replace('/\D/', '', $request->nomor_wa);

        $user = User::where('id', Auth::user()->id)->first();
        $guru = Guru::where('user_id', $user->id)->first();

        $user->email = $request->email;
        $guru->nomor_wa = $nomorWa;

        $user->save();
        $guru->save();

        return redirect()->back()->with('success', 'Data akun telah diperbarui');
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
