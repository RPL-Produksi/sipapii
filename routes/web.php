<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AkunData\Guru\AdminGuruController;
use App\Http\Controllers\Admin\Pengelolaan\AdminPenempatanController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\Pengelolaan\AdminInstansiController;
use App\Http\Controllers\Admin\Pengelolaan\AdminKelasController;
use App\Http\Controllers\Admin\Pengelolaan\AdminPembimbinganController;
use App\Http\Controllers\Admin\Pengelolaan\AdminTahunAjarController;
use App\Http\Controllers\Admin\AkunData\Siswa\AdminDataSiswaController;
use App\Http\Controllers\Admin\Data\Absen\AdminAbsenController;
use App\Http\Controllers\Admin\Data\Jurnal\AdminJurnalController;
use App\Http\Controllers\Guru\Data\GuruAbsensiController;
use App\Http\Controllers\Guru\Data\GuruJurnalController;
use App\Http\Controllers\Guru\Data\GuruNilaiPklController;
use App\Http\Controllers\Guru\Data\GuruSiswaController;
use App\Http\Controllers\Guru\GuruDashboardController;
use App\Http\Controllers\Siswa\SiswaAbsenController;
use App\Http\Controllers\Siswa\SiswaDashboardController;
use App\Http\Controllers\Siswa\SiswaJurnalController;
use App\Http\Controllers\Siswa\SiswaProfileController;
use App\Http\Controllers\Siswa\SiswaRiwayatController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

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

    Route::prefix('/pengelolaan')->group(function () {
        Route::controller(AdminKelasController::class)->group(function () {
            Route::prefix('/kelas')->group(function () {
                Route::get('/', 'index')->name('admin.pengelolaan.kelas');
                Route::post('/add', 'addKelas')->name('admin.pengelolaan.kelas.add');
                Route::post('/{id}/edit', 'editKelas')->name('admin.pengelolaan.kelas.edit');
                Route::get('/{id}/delete', 'deleteKelas')->name('admin.pengelolaan.kelas.delete');
                Route::get('/data', 'data')->name('admin.pengelolaan.kelas.data');
                Route::get('/data/{id}', 'dataById')->name('admin.pengelolaan.kelas.data.id');
                Route::post('/import', 'importKelas')->name('admin.pengelolaan.kelas.import');
            });
        });

        Route::controller(AdminInstansiController::class)->group(function () {
            Route::prefix('/instansi')->group(function () {
                Route::get('/', 'index')->name('admin.pengelolaan.instansi');
                Route::get('/form/{id?}', 'form')->name('admin.pengelolaan.instansi.form');
                Route::post('/form/{id?}', 'store')->name('admin.pengelolaan.instansi.form.store');
                Route::get('/data', 'data')->name('admin.pengelolaan.instansi.data');
                Route::get('/{id}/delete', 'delete')->name('admin.pengelolaan.instansi.delete');
                Route::post('/import', 'importInstansi')->name('admin.pengelolaan.instansi.import');
            });
        });

        Route::controller(AdminTahunAjarController::class)->group(function () {
            Route::prefix('/tahun-ajar')->group(function () {
                Route::get('/', 'index')->name('admin.pengelolaan.tahun-ajar');
                Route::post('/add', 'addTahunAjar')->name('admin.pengelolaan.tahun-ajar.add');
                Route::post('/{id}/edit', 'editTahunAjar')->name('admin.pengelolaan.tahun-ajar.edit');
                Route::get('/{id}/delete', 'deleteTahunAjar')->name('admin.pengelolaan.tahun-ajar.delete');
                Route::get('/data', 'data')->name('admin.pengelolaan.tahun-ajar.data');
                Route::get('/data/{id}', 'dataById')->name('admin.pengelolaan.tahun-ajar.data.id');
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
        });
    });
});

// Route Path Guru
Route::prefix('/guru')->middleware(['auth', 'role:guru'])->group(function () {
    Route::controller(GuruDashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('guru.dashboard');
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
        });
    });

    Route::controller(SiswaProfileController::class)->group(function () {
        Route::prefix('/profile')->group(function () {
            Route::get('/', 'index')->name('siswa.profile');
            Route::post('/email/add', 'addEmail')->name('siswa.profile.email.add');
            Route::post('/picture/edit', 'changeProfile')->name('siswa.profile.picture.edit');
        });
    });
});

Route::get('/test/mail', function () {
    Mail::raw('ini test mail dari sipapii@smkn2smi.sch.id', function ($message) {
        $message->to('hilal.muhammad0807@gmail.com')->subject('Test Mail');
    });
});
