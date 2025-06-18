<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
            page-break-inside: auto;
        }

        th,
        td {
            border: 1px solid black;
            padding: 6px;
            vertical-align: top;
        }

        th {
            text-align: center;
            background-color: #f0f0f0;
        }

        thead {
            display: table-header-group;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        h3,
        h4 {
            text-align: center;
            margin: 20px 0 10px;
        }

        .info-table td {
            border: none;
            padding: 4px 8px;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    @php $first = true; @endphp
    @foreach ($jurnal as $bulan => $entries)
        <div @if (!$first) class="page-break" @endif>
            @php $first = false; @endphp
            <h3>JURNAL KEGIATAN PRAKTIK KERJA LAPANGAN</h3>

            <table class="info-table">
                <tr>
                    <td width="200"><strong>Nama Peserta Didik</strong></td>
                    <td>: {{ $user->nama_lengkap }}</td>
                </tr>
                <tr>
                    <td><strong>Dunia Kerja Tempat PKL</strong></td>
                    <td>: {{ $siswa->penempatan->instansi->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <td><strong>Nama Instruktur</strong></td>
                    <td>: {{ $instruktur }}</td>
                </tr>
                <tr>
                    <td><strong>Nama Guru Pembimbing</strong></td>
                    <td>: {{ $siswa->pembimbingan->guruMapelPKL->user->nama_lengkap ?? '-' }}</td>
                </tr>
            </table>

            <table class="jurnal-table">
                <thead>
                    <tr>
                        <th style="width: 30px;">No</th>
                        <th style="width: 130px;">Hari/Tanggal</th>
                        <th>Unit Kerja / Pekerjaan</th>
                        <th style="width: 100px;">Paraf</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($entries as $i => $item)
                        <tr>
                            <td align="center">{{ $i + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                            </td>
                            <td>{!! nl2br(e($item->deskripsi_jurnal ?? '-')) !!}</td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
</body>

</html>
