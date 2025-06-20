<?php

use App\Exports\DataSiswaExport;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AkunData\Guru\AdminGuruController;
use App\Http\Controllers\Admin\Pengelolaan\AdminPenempatanController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\Pengelolaan\AdminInstansiController;
use App\Http\Controllers\Admin\Pengelolaan\AdminKelasController;
use App\Http\Controllers\Admin\Pengelolaan\AdminPembimbinganController;
use App\Http\Controllers\Admin\Pengelolaan\AdminTahunAjarController;
use App\Http\Controllers\Admin\AkunData\Siswa\AdminDataSiswaController;
use App\Http\Controllers\Admin\AkunData\Siswa\AdminNilaiPklController;
use App\Http\Controllers\Admin\Data\Absen\AdminAbsenController;
use App\Http\Controllers\Admin\Data\Jurnal\AdminJurnalController;
use App\Http\Controllers\Guru\Data\GuruAbsensiController;
use App\Http\Controllers\Guru\Data\GuruJurnalController;
use App\Http\Controllers\Guru\Data\GuruNilaiPklController;
use App\Http\Controllers\Guru\Data\GuruSiswaController;
use App\Http\Controllers\Guru\GuruDashboardController;
use App\Http\Controllers\Guru\GuruProfileController;
use App\Http\Controllers\Siswa\SiswaAbsenController;
use App\Http\Controllers\Siswa\SiswaDashboardController;
use App\Http\Controllers\Siswa\SiswaJurnalController;
use App\Http\Controllers\Siswa\SiswaProfileController;
use App\Http\Controllers\Siswa\SiswaRiwayatController;
use App\Imports\SiswaImport;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

Route::redirect('/', 'login');
Route::fallback(function () {
    return redirect()->route('login');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'index')->name('login');
    Route::post('/login', 'login')->name('post.login');

    Route::get('/logout', 'logout')->name('logout')->middleware('auth');
    Route::post('/change-password', 'changePassword')->name('password.change')->middleware('auth');
});

// Route Path Admin
Route::prefix('/admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::controller(AdminDashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('admin.dashboard');
    });

    Route::controller(AdminProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('admin.profile');
        Route::post('/profile/edit', 'edit')->name('admin.profile.edit');
        Route::post('/picture/edit', 'changeProfile')->name('admin.profile.picture.edit');;
    });

    Route::controller(AdminSettingController::class)->group(function () {
        Route::get('/setting', 'index')->name('admin.setting');
    });

    Route::prefix('/pengelolaan')->group(function () {
        Route::controller(AdminInstansiController::class)->group(function () {
            Route::prefix('/instansi')->group(function () {
                Route::get('/', 'index')->name('admin.pengelolaan.instansi');
                Route::get('/form/{id?}', 'form')->name('admin.pengelolaan.instansi.form');
                Route::post('/form/{id?}', 'store')->name('admin.pengelolaan.instansi.form.store');
                Route::get('/data', 'data')->name('admin.pengelolaan.instansi.data');
                Route::get('/{id}/delete', 'delete')->name('admin.pengelolaan.instansi.delete');
                Route::post('/import', 'importInstansi')->name('admin.pengelolaan.instansi.import');
                Route::get('/export', 'exportInstansi')->name('admin.pengelolaan.instansi.export');
                Route::get('/{id}/siswa', 'instansiSiswa')->name('admin.pengelolaan.instansi.siswa');
                Route::get('/{id}/siswa/{siswaId}/nonactive', 'nonactiveSiswa')->name('admin.pengelolaan.instansi.siswa.nonactive');
            });
        });

        Route::prefix('/tahun-ajar')->group(function () {
            Route::controller(AdminTahunAjarController::class)->group(function () {
                Route::get('/', 'index')->name('admin.pengelolaan.tahun-ajar');
                Route::post('/add', 'addTahunAjar')->name('admin.pengelolaan.tahun-ajar.add');
                Route::post('/{id}/edit', 'editTahunAjar')->name('admin.pengelolaan.tahun-ajar.edit');
                Route::get('/{id}/delete', 'deleteTahunAjar')->name('admin.pengelolaan.tahun-ajar.delete');
                Route::get('/data', 'data')->name('admin.pengelolaan.tahun-ajar.data');
                Route::get('/data/{id}', 'dataById')->name('admin.pengelolaan.tahun-ajar.data.id');
                Route::get('/{id}/alumni', 'alumni')->name('admin.pengelolaan.tahun-ajar.alumni');
            });

            Route::controller(AdminKelasController::class)->group(function () {
                Route::get('/{id}/kelas', 'index')->name('admin.pengelolaan.tahun-ajar.kelas');
                Route::post('/{id}/kelas/add', 'addKelas')->name('admin.pengelolaan.tahun-ajar.kelas.add');
                Route::post('/{id}/kelas/{kelasId}/edit', 'editKelas')->name('admin.pengelolaan.tahun-ajar.kelas.edit');
                Route::get('/{id}/kelas/{kelasId}/delete', 'deleteKelas')->name('admin.pengelolaan.tahun-ajar.kelas.delete');
                Route::get('/{id}/kelas/data', 'data')->name('admin.pengelolaan.tahun-ajar.kelas.data');
                Route::get('/{id}/kelas/data/{kelasId}', 'dataById')->name('admin.pengelolaan.kelas.data.id');
                Route::post('/{id}/kelas/import', 'importKelas')->name('admin.pengelolaan.tahun-ajar.kelas.import');
            });
        });

        Route::controller(AdminPenempatanController::class)->group(function () {
            Route::prefix('/penempatan')->group(function () {
                Route::get('/', 'index')->name('admin.pengelolaan.penempatan');
                Route::post('/add', 'addPenempatan')->name('admin.pengelolaan.penempatan.add');
                Route::post('/{id}/edit', 'editPenempatan')->name('admin.pengelolaan.penempatan.edit');
                Route::get('/{id}/delete', 'deletePenempatan')->name('admin.pengelolaan.penempatan.delete');
                Route::get('/data', 'data')->name('admin.pengelolaan.penempatan.data');
                Route::get('/data/{id}', 'dataById')->name('admin.pengelolaan.penempatan.data.id');
                Route::post('/import', 'importPenempatan')->name('admin.pengelolaan.penempatan.import');
                Route::get('/export', 'exportPenempatan')->name('admin.pengelolaan.penempatan.export');
            });
        });

        Route::controller(AdminPembimbinganController::class)->group(function () {
            Route::prefix('/pembimbingan')->group(function () {
                Route::get('/', 'index')->name('admin.pengelolaan.pembimbingan');
                Route::post('/add', 'addPembimbingan')->name('admin.pengelolaan.pembimbingan.add');
                Route::post('/{id}/edit', 'editPembimbingan')->name('admin.pengelolaan.pembimbingan.edit');
                Route::get('/{id}/delete', 'deletePembimbingan')->name('admin.pengelolaan.pembimbingan.delete');
                Route::get('/data', 'data')->name('admin.pengelolaan.pembimbingan.data');
                Route::get('/data/{id}', 'dataById')->name('admin.pengelolaan.pembimbingan.data.id');
                Route::get('/export', 'exportPembimbingan')->name('admin.pengelolaan.pembimbingan.export');
            });
        });
    });

    Route::prefix('/siswa')->group(function () {
        Route::controller(AdminDataSiswaController::class)->group(function () {
            Route::get('/', 'index')->name('admin.siswa');
            Route::get('/form/{id?}', 'form')->name('admin.siswa.form');
            Route::post('/form/{id?}', 'store')->name('admin.siswa.form.store');
            Route::get('/data', 'data')->name('admin.siswa.data');
            Route::get('/data/{id}', 'dataById')->name('admin.siswa.data.id');
            Route::get('/{id}/delete', 'delete')->name('admin.siswa.delete');
            Route::post('/import', 'importSiswa')->name('admin.siswa.import');
            Route::get('/export', 'exportSiswa')->name('admin.siswa.export');
        });

        Route::prefix('/nilai')->group(function () {
            Route::controller(AdminNilaiPklController::class)->group(function () {
                Route::get('/', 'index')->name('admin.siswa.nilai');
                Route::post('/store/{id?}', 'store')->name('admin.siswa.nilai.store');
                Route::get('/data/{id}', 'dataById')->name('admin.siswa.nilai.data.id');
                Route::get('/export', 'exportNilai')->name('admin.siswa.nilai.export');
            });
        });

        Route::prefix('/absen')->group(function () {
            Route::controller(AdminAbsenController::class)->group(function () {
                Route::get('/', 'index')->name('admin.absen.siswa');
                Route::get('/data', 'data')->name('admin.absen.siswa.data');
                Route::get('/data/{id}', 'dataById')->name('admin.absen.siswa.data.id');
            });
        });

        Route::prefix('/jurnal')->group(function () {
            Route::controller(AdminJurnalController::class)->group(function () {
                Route::get('/', 'index')->name('admin.jurnal.siswa');
                Route::get('/data', 'data')->name('admin.jurnal.siswa.data');
                Route::get('/data/{id}', 'dataById')->name('admin.jurnal.siswa.data.id');
                Route::post('/{id}/status/change', 'editStatus')->name('admin.jurnal.siswa.edit.status');
            });
        });
    });

    Route::prefix('/guru')->group(function () {
        Route::controller(AdminGuruController::class)->group(function () {
            Route::get('/', 'index')->name('admin.guru');
            Route::post('/add', 'addGuru')->name('admin.guru.add');
            Route::post('/{id}/edit', 'editGuru')->name('admin.guru.edit');
            Route::get('/{id}/delete', 'deleteGuru')->name('admin.guru.delete');
            Route::get('/data', 'data')->name('admin.guru.data');
            Route::get('/data/{id}', 'dataById')->name('admin.guru.data.id');
            Route::post('/import', 'importGuru')->name('admin.guru.import');
            Route::get('/export', 'exportGuru')->name('admin.guru.export');
        });
    });
});

// Route Path Guru
Route::prefix('/guru')->middleware(['auth', 'role:guru'])->group(function () {
    Route::controller(GuruDashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('guru.dashboard');
    });

    Route::controller(GuruProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('guru.profile');
        Route::post('/profile/edit', 'edit')->name('guru.profile.edit');
        Route::post('/picture/edit', 'changeProfile')->name('guru.profile.picture.edit');
    });

    Route::prefix('/siswa')->group(function () {
        Route::controller(GuruSiswaController::class)->group(function () {
            Route::get('/', 'index')->name('guru.siswa');
            Route::get('/data/{id}', 'dataById')->name('guru.siswa.data.id');
        });

        Route::prefix('/absen')->group(function () {
            Route::controller(GuruAbsensiController::class)->group(function () {
                Route::get('/', 'index')->name('guru.siswa.absen');
                Route::get('/data', 'data')->name('guru.siswa.absen.data');
                Route::get('/data/{id}', 'dataById')->name('guru.siswa.absen.data.id');
            });
        });

        Route::prefix('/jurnal')->group(function () {
            Route::controller(GuruJurnalController::class)->group(function () {
                Route::get('/', 'index')->name('guru.siswa.jurnal');
                Route::get('/data', 'data')->name('guru.siswa.jurnal.data');
                Route::get('/data/{id}', 'dataById')->name('guru.siswa.jurnal.data.id');
                Route::post('/{id}/check', 'checkJurnal')->name('guru.siswa.jurnal.check');
            });
        });

        Route::prefix('/nilai')->group(function () {
            Route::controller(GuruNilaiPklController::class)->group(function () {
                Route::get('/', 'index')->name('guru.siswa.nilai');
                Route::post('/store/{id?}', 'store')->name('guru.siswa.nilai.store');
                Route::get('/data/{id}', 'dataById')->name('guru.siswa.nilai.data.id');
                Route::get('/{id}/rekomendasi-nilai', 'getRekomendasi')->name('guru.siswa.nilai.rekomendasi');
                Route::get('export', 'exportNilai')->name('guru.siswa.nilai.export');
            });
        });
    });
});

// Route Path Siswa
Route::prefix('/siswa')->middleware(['auth', 'role:siswa'])->group(function () {
    Route::controller(SiswaDashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('siswa.dashboard');
    });

    Route::controller(SiswaAbsenController::class)->group(function () {
        Route::prefix('/absen')->group(function () {
            Route::get('/', 'index')->name('siswa.absen');
            Route::post('/', 'absen')->name('siswa.absen.post');
        });
    });

    Route::controller(SiswaRiwayatController::class)->group(function () {
        Route::prefix('/riwayat')->group(function () {
            Route::get('/', 'index')->name('siswa.riwayat');
            Route::get('/data', 'data')->name('siswa.riwayat.data');
        });
    });

    Route::controller(SiswaJurnalController::class)->group(function () {
        Route::prefix('/jurnal')->group(function () {
            Route::get('/', 'index')->name('siswa.jurnal');
            Route::post('/{id}/edit', 'editJurnal')->name('siswa.jurnal.edit');
            Route::get('/data/{id}', 'dataById')->name('siswa.jurnal.data.id');
            Route::get('export', 'exportJurnal')->name('siswa.jurnal.export');
        });
    });

    Route::controller(SiswaProfileController::class)->group(function () {
        Route::prefix('/profile')->group(function () {
            Route::get('/', 'index')->name('siswa.profile');
            Route::post('/fields/add', 'addFields')->name('siswa.profile.fields.add');
            Route::post('/picture/edit', 'changeProfile')->name('siswa.profile.picture.edit');
        });
    });
});

// Route Path Alumni
Route::prefix('/alumni')->middleware(['auth', 'role:alumni'])->group(function () {
    Route::get('dasboard', function () {
        return view('alumni.dashboard');
    })->name('alumni.dashboard');
});

Route::get('/test/mail', function () {
    Mail::raw('ini test mail dari sipapii@smkn2smi.sch.id', function ($message) {
        $message->to('hilal.muhammad0807@gmail.com')->subject('Test Mail');
    });
});
