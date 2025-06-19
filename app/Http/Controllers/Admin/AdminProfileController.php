<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;

class AdminProfileController extends Controller
{
    public function index()
    {
        $data['user'] = Auth::user();

        return view('admin.profile', [], ['menu_type' => 'profile'])->with($data);
    }

    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'username' => 'required',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }

        $user = User::where('id', Auth::user()->id)->first();
        $user->nama_lengkap = $request->nama_lengkap;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->save();

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
