<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Presensi {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <style>
        p {
            line-height: 1rem !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .btn {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            margin-bottom: 0;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            text-align: center;
            text-decoration: none;
            vertical-align: middle;
            cursor: pointer;
            border: 1px solid transparent;
            border-radius: 0.375rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .btn-warning {
            color: #000;
            background-color: #ffc107;
            border-color: #ffc107;
        }

        .btn-warning:hover {
            color: #000;
            background-color: #ffca2c;
            border-color: #ffc920;
        }

        .btn-secondary {
            color: #fff;
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            color: #fff;
            background-color: #5a6268;
            border-color: #545b62;
        }
    </style>
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/external/js/sweetalert2@11.js') }}"></script>
</head>

<body>

    <div class="header" style="margin-bottom: 10px">
        <table>
            <tr>
                <td>
                    @if ($generalsetting->logo && Storage::exists('public/logo/' . $generalsetting->logo))
                        <img src="{{ asset('storage/logo/' . $generalsetting->logo) }}" alt="Logo Perusahaan" style="max-width: 100px;">
                    @else
                        <img src="https://placehold.co/100x100?text=Logo" alt="Logo Default" style="max-width: 100px;">
                    @endif
                </td>
                <td>
                    <h4 style="line-height: 20px; margin-bottom: 5px">
                        LAPORAN PRESENSI
                        <br>
                        {{ $generalsetting->nama_perusahaan }}
                        <br>
                        PERIODE {{ date('d-m-Y', strtotime($periode_dari)) }} -
                        {{ date('d-m-Y', strtotime($periode_sampai)) }}
                    </h4>
                    <span style="font-style: italic;">{{ $generalsetting->alamat }}</span><br>
                    <span style="font-style: italic;">{{ $generalsetting->telepon }}</span>
                </td>
                <td style="text-align: right; vertical-align: top;">
                    <form id="formKunciLaporan" method="POST" action="{{ route('laporan.kuncilaporan') }}"
                        style="display: inline-block; margin-right: 5px;">
                        @csrf
                        <input type="hidden" name="periode_laporan" value="{{ $request_params['periode_laporan'] }}">
                        <input type="hidden" name="bulan" value="{{ $request_params['bulan'] }}">
                        <input type="hidden" name="tahun" value="{{ $request_params['tahun'] }}">
                        @if (!empty($request_params['kode_cabang']))
                            <input type="hidden" name="kode_cabang" value="{{ $request_params['kode_cabang'] }}">
                        @endif
                        @if (!empty($request_params['kode_dept']))
                            <input type="hidden" name="kode_dept" value="{{ $request_params['kode_dept'] }}">
                        @endif
                        @if (!empty($request_params['nik']))
                            <input type="hidden" name="nik" value="{{ $request_params['nik'] }}">
                        @endif
                        <button type="submit" class="btn btn-warning" id="btnKunciLaporan" style="padding: 8px 16px; font-size: 14px;">
                            <i class="ti ti-lock"></i> Kunci Laporan
                        </button>
                    </form>
                    <form id="formBatalkanKunciLaporan" method="POST" action="{{ route('laporan.batalkankuncilaporan') }}"
                        style="display: inline-block;">
                        @csrf
                        <input type="hidden" name="periode_laporan" value="{{ $request_params['periode_laporan'] }}">
                        <input type="hidden" name="bulan" value="{{ $request_params['bulan'] }}">
                        <input type="hidden" name="tahun" value="{{ $request_params['tahun'] }}">
                        @if (!empty($request_params['kode_cabang']))
                            <input type="hidden" name="kode_cabang" value="{{ $request_params['kode_cabang'] }}">
                        @endif
                        @if (!empty($request_params['kode_dept']))
                            <input type="hidden" name="kode_dept" value="{{ $request_params['kode_dept'] }}">
                        @endif
                        @if (!empty($request_params['nik']))
                            <input type="hidden" name="nik" value="{{ $request_params['nik'] }}">
                        @endif
                        <button type="submit" class="btn btn-secondary" id="btnBatalkanKunciLaporan"
                            style="padding: 8px 16px; font-size: 14px; background-color: #6c757d; border-color: #6c757d; color: #fff;">
                            <i class="ti ti-lock-off"></i> Batalkan Kunci
                        </button>
                    </form>
                </td>
            </tr>
        </table>
    </div>
    <div class="content">
        <table class="datatable3" style="width: 250%">
            <thead>
                <tr>
                    <th rowspan="3">No</th>
                    <th rowspan="3">Nik</th>
                    <th rowspan="3">Nama Karyawan</th>
                    <th rowspan="3">Jabatan</th>
                    <th rowspan="3">Dept</th>
                    <th rowspan="3">Cabang</th>
                    <th colspan="{{ $jmlhari }}">Tanggal</th>
                    <th rowspan="3">Denda</th>
                    <th rowspan="3">Pot. Jam</th>
                    <th rowspan="3">Lembur</th>
                    <th colspan="9">Rekap</th>
                </tr>
                <tr>
                    @php
                        $tanggal_presensi = $periode_dari;
                    @endphp
                    @while (strtotime($tanggal_presensi) <= strtotime($periode_sampai))
                        <th style="width: 100px">{{ getHari(date('Y-m-d', strtotime($tanggal_presensi))) }}</th>
                        @php
                            $tanggal_presensi = date('Y-m-d', strtotime('+1 day', strtotime($tanggal_presensi)));
                        @endphp
                    @endwhile
                    <th rowspan="2">Hadir</th>
                    <th rowspan="2">Izin</th>
                    <th rowspan="2">Sakit</th>
                    <th rowspan="2">Alfa</th>
                    <th rowspan="2">Libur</th>
                    <th rowspan="2">Terlambat</th>
                    <th rowspan="2">Tidak Scan Masuk</th>
                    <th rowspan="2">Tidak Scan Pulang</th>
                    <th rowspan="2">Pulang Cepat</th>
                </tr>
                <tr>
                    @php
                        $tanggal_presensi = $periode_dari;
                    @endphp
                    @while (strtotime($tanggal_presensi) <= strtotime($periode_sampai))
                        <th>{{ date('d', strtotime($tanggal_presensi)) }}</th>
                        @php
                            $tanggal_presensi = date('Y-m-d', strtotime('+1 day', strtotime($tanggal_presensi)));
                        @endphp
                    @endwhile
                </tr>
            </thead>
            <tbody>
                @foreach ($laporan_presensi as $d)
                    @php
                        $tanggal_presensi = $periode_dari;
                        // Mapping jadwal untuk NIK ini dari berbagai sumber
                        $mapJadwalByDate = $jadwal_bydate[$d['nik']] ?? [];
                        $mapJadwalGrupByDate = $jadwal_grup_bydate[$d['nik']] ?? [];
                        $mapJadwalByDay = $jadwal_byday[$d['nik']] ?? [];
                    @endphp
                    <tr>
                        <td style="width:1%">{{ $loop->iteration }}</td>
                        <td style="width:2%">'{{ $d['nik_show'] ?? $d['nik'] }}</td>
                        <td style="width:5%">{{ $d['nama_karyawan'] }}</td>
                        <td style="width:3%">{{ $d['nama_jabatan'] }}</td>
                        <td style="width:2%">{{ $d['kode_dept'] }}</td>
                        <td style="width:2%">{{ $d['kode_cabang'] }}</td>
                        @php
                            $total_denda = 0;
                            $total_potongan_jam = 0;
                            $total_jam_lembur = 0;
                            $jml_hadir = 0;
                            $jml_sakit = 0;
                            $jml_izin = 0;
                            $jml_cuti = 0;
                            $jml_libur = 0;
                            $jml_alfa = 0;
                            $jml_terlambat = 0;
                            $jml_pulangcepat = 0;
                            $jml_tidakscanmasuk = 0;
                            $jml_tidakscanpulang = 0;
                        @endphp
                        @while (strtotime($tanggal_presensi) <= strtotime($periode_sampai))
                            @php
                                $denda = 0;
                                $potongan_jam = 0;
                                $search = [
                                    'nik' => $d['nik'],
                                    'tanggal' => $tanggal_presensi,
                                ];

                                $ceklibur = ceklibur($datalibur, $search);
                                $ceklembur = ceklembur($datalembur, $search);
                                $lembur = hitungLembur($ceklembur);
                                if (!empty($ceklembur)) {
                                    $jml_jam_lembur = $lembur;
                                } else {
                                    $jml_jam_lembur = 0;
                                }
                                $nama_hari = getHari($tanggal_presensi);
                            @endphp
                            @if (isset($d[$tanggal_presensi]))
                                @if ($d[$tanggal_presensi]['status'] == 'h')
                                    @php
                                        $bgcolor = '';
                                        $textcolor = '';
                                        $jml_hadir++;

                                        $ket_nama_jam_kerja =
                                            '<h4 style="font-weight:bold; margin-bottom:10px">' . $d[$tanggal_presensi]['nama_jam_kerja'] . '</h4>';
                                        $ket_jadwal_kerja =
                                            '<p><span style="color:blue">' .
                                            date('H:i', strtotime($d[$tanggal_presensi]['jam_masuk'])) .
                                            ' - ' .
                                            date('H:i', strtotime($d[$tanggal_presensi]['jam_pulang'])) .
                                            '</span></p>';
                                        $jam_masuk = $tanggal_presensi . ' ' . $d[$tanggal_presensi]['jam_masuk'];
                                        $jam_in = !empty($d[$tanggal_presensi]['jam_in'])
                                            ? date('H:i', strtotime($d[$tanggal_presensi]['jam_in']))
                                            : '&#10008;';
                                        $jam_out = !empty($d[$tanggal_presensi]['jam_out'])
                                            ? date('H:i', strtotime($d[$tanggal_presensi]['jam_out']))
                                            : '&#10008;';

                                        $color_jam_in = !empty($d[$tanggal_presensi]['jam_in']) ? 'green' : 'red';
                                        $color_jam_out = !empty($d[$tanggal_presensi]['jam_out']) ? 'green' : 'red';

                                        $ket_presensi =
                                            '<p> <span
                                                style="color:' .
                                            $color_jam_in .
                                            '">' .
                                            $jam_in .
                                            '</span> -
                                            <span
                                                style="color:' .
                                            $color_jam_out .
                                            '">' .
                                            $jam_out .
                                            '</span></p>';

                                        $terlambat = hitungjamterlambat($d[$tanggal_presensi]['jam_in'], $jam_masuk);

                                        $color_terlambat = $terlambat != null ? $terlambat['color'] : '';
                                        $ket_terlambat =
                                            $terlambat != null
                                                ? '<p><span
                                                style="color:' .
                                                    $color_terlambat .
                                                    '">' .
                                                    $terlambat['show_laporan'] .
                                                    '</span></p>'
                                                : '';

                                        // Cek apakah denda sudah dikunci (ada di database)
                                        $denda_dari_db =
                                            isset($d[$tanggal_presensi]['denda']) && $d[$tanggal_presensi]['denda'] !== null
                                                ? $d[$tanggal_presensi]['denda']
                                                : null;

                                        if ($denda_dari_db !== null) {
                                            // Gunakan denda dari database (sudah dikunci)
                                            $denda = $denda_dari_db;
                                            // Hitung potongan jam tetap menggunakan rumus
                                            if ($terlambat != null) {
                                                if ($terlambat['desimal_terlambat'] < 1) {
                                                    $potongan_jam_terlambat = 0;
                                                } else {
                                                    $potongan_jam_terlambat =
                                                        $terlambat['desimal_terlambat'] > $d[$tanggal_presensi]['total_jam']
                                                            ? $d[$tanggal_presensi]['total_jam']
                                                            : $terlambat['desimal_terlambat'];
                                                }
                                                if ($terlambat['menitterlambat'] > 0) {
                                                    $jml_terlambat++;
                                                }
                                            } else {
                                                $potongan_jam_terlambat = 0;
                                            }
                                        } else {
                                            // Hitung denda menggunakan rumus (belum dikunci)
                                            if ($terlambat != null) {
                                                if ($terlambat['desimal_terlambat'] < 1) {
                                                    $potongan_jam_terlambat = 0;
                                                    $denda = hitungdenda($denda_list, $terlambat['menitterlambat']);
                                                } else {
                                                    $potongan_jam_terlambat =
                                                        $terlambat['desimal_terlambat'] > $d[$tanggal_presensi]['total_jam']
                                                            ? $d[$tanggal_presensi]['total_jam']
                                                            : $terlambat['desimal_terlambat'];
                                                    $denda = 0;
                                                }
                                                if ($terlambat['menitterlambat'] > 0) {
                                                    $jml_terlambat++;
                                                }
                                            } else {
                                                $potongan_jam_terlambat = 0;
                                                $denda = 0;
                                            }
                                        }

                                        $ket_denda = $denda != 0 ? '<p><span style="color:red">Denda : ' . formatAngka($denda) . '</span></p>' : '';

                                        $pulangcepat = hitungpulangcepat(
                                            $tanggal_presensi,
                                            $d[$tanggal_presensi]['jam_out'],
                                            $d[$tanggal_presensi]['jam_pulang'],
                                            $d[$tanggal_presensi]['istirahat'],
                                            $d[$tanggal_presensi]['jam_awal_istirahat'],
                                            $d[$tanggal_presensi]['jam_akhir_istirahat'],
                                            $d[$tanggal_presensi]['lintashari'],
                                        );

                                        $pulangcepat =
                                            $pulangcepat > $d[$tanggal_presensi]['total_jam'] ? $d[$tanggal_presensi]['total_jam'] : $pulangcepat;

                                        if ($pulangcepat != null) {
                                            $jml_pulangcepat++;
                                        }
                                        $ket_pulang_cepat =
                                            $pulangcepat != null ? '<p><span style="color:red">PC : ' . $pulangcepat . ' Jam </span></p>' : '';
                                        $color_pulang_cepat = $pulangcepat != null ? 'red' : '';
                                        $potongan_tidak_absen_masuk_atau_pulang =
                                            empty($d[$tanggal_presensi]['jam_out']) || empty($d[$tanggal_presensi]['jam_in'])
                                                ? $d[$tanggal_presensi]['total_jam']
                                                : 0;
                                        $potongan_jam =
                                            $potongan_tidak_absen_masuk_atau_pulang == 0
                                                ? $pulangcepat + $potongan_jam_terlambat
                                                : $potongan_tidak_absen_masuk_atau_pulang;
                                        $ket_potongan_jam = !empty($potongan_jam)
                                            ? '<p><span style="color:red">PJ: ' . formatAngkaDesimal($potongan_jam) . ' Jam</span></p>'
                                            : '';

                                        $ket_jam_lembur =
                                            $jml_jam_lembur > 0
                                                ? '<p><span style="color:rgb(11, 153, 179)"> Lembur :' . $jml_jam_lembur . ' Jam</span></p>'
                                                : '';
                                        $ket =
                                            $ket_nama_jam_kerja .
                                            $ket_jadwal_kerja .
                                            $ket_presensi .
                                            $ket_terlambat .
                                            $ket_denda .
                                            $ket_pulang_cepat .
                                            $ket_potongan_jam .
                                            $ket_jam_lembur;
                                        // $ket =
                                        //     $ket_nama_jam_kerja .
                                        //     $ket_jadwal_kerja .
                                        //     '<br>' .
                                        //     $ket_presensi .
                                        //     '<br>' .
                                        //     $ket_terlambat .
                                        //     '<br>' .
                                        //     $ket_denda .
                                        //     $ket_pulang_cepat .
                                        //     '<br>' .
                                        //     $ket_potongan_jam;

                                        if (empty($d[$tanggal_presensi]['jam_in'])) {
                                            $jml_tidakscanmasuk++;
                                        }

                                        if (empty($d[$tanggal_presensi]['jam_out'])) {
                                            $jml_tidakscanpulang++;
                                        }
                                    @endphp
                                @elseif($d[$tanggal_presensi]['status'] == 'i')
                                    @php
                                        $bgcolor = '#dea51f';
                                        $textcolor = 'white';
                                        $jml_izin++;
                                        $potongan_jam = $d[$tanggal_presensi]['total_jam'];

                                        // Cek apakah denda sudah dikunci (untuk izin biasanya 0, tapi ambil dari DB jika ada)
                                        $denda_dari_db =
                                            isset($d[$tanggal_presensi]['denda']) && $d[$tanggal_presensi]['denda'] !== null
                                                ? $d[$tanggal_presensi]['denda']
                                                : null;
                                        $denda = $denda_dari_db !== null ? $denda_dari_db : 0;

                                        $ket =
                                            '<h4 style="font-weight: bold; margin-bottom:10px">IZIN</h4><p>' .
                                            $d[$tanggal_presensi]['keterangan_izin_absen'] .
                                            '</p>
                                            <p>PJ : ' .
                                            formatAngkaDesimal($potongan_jam) .
                                            ' Jam</p>';
                                    @endphp
                                @elseif($d[$tanggal_presensi]['status'] == 's')
                                    @php
                                        $bgcolor = '#c8075b';
                                        $textcolor = 'white';
                                        $jml_sakit++;

                                        // Cek apakah denda sudah dikunci (untuk sakit biasanya 0, tapi ambil dari DB jika ada)
                                        $denda_dari_db =
                                            isset($d[$tanggal_presensi]['denda']) && $d[$tanggal_presensi]['denda'] !== null
                                                ? $d[$tanggal_presensi]['denda']
                                                : null;
                                        $denda = $denda_dari_db !== null ? $denda_dari_db : 0;

                                        $ket =
                                            '<h4 style="font-weight: bold; margin-bottom:10px">SAKIT</h4><span>' .
                                            $d[$tanggal_presensi]['keterangan_izin_sakit'] .
                                            '</span>
                                            ';
                                    @endphp
                                @elseif($d[$tanggal_presensi]['status'] == 'c')
                                    @php
                                        $bgcolor = '#0164b5';
                                        $textcolor = 'white';
                                        $jml_cuti++;

                                        // Cek apakah denda sudah dikunci (untuk cuti biasanya 0, tapi ambil dari DB jika ada)
                                        $denda_dari_db =
                                            isset($d[$tanggal_presensi]['denda']) && $d[$tanggal_presensi]['denda'] !== null
                                                ? $d[$tanggal_presensi]['denda']
                                                : null;
                                        $denda = $denda_dari_db !== null ? $denda_dari_db : 0;

                                        $ket =
                                            '<h4 style="font-weight: bold; margin-bottom:10px">CUTI</h4><span>' .
                                            $d[$tanggal_presensi]['keterangan_izin_cuti'] .
                                            '</span>';
                                    @endphp
                                @elseif($d[$tanggal_presensi]['status'] == 'a')
                                    @php
                                        $bgcolor = 'red';
                                        $textcolor = 'white';
                                        $jml_alfa++;
                                        $potongan_jam = $d[$tanggal_presensi]['total_jam'];

                                        // Cek apakah denda sudah dikunci (ada di database)
                                        $denda_dari_db =
                                            isset($d[$tanggal_presensi]['denda']) && $d[$tanggal_presensi]['denda'] !== null
                                                ? $d[$tanggal_presensi]['denda']
                                                : null;

                                        // Untuk alpa, denda biasanya 0 atau null, tapi tetap ambil dari DB jika sudah dikunci
                                        $denda = $denda_dari_db !== null ? $denda_dari_db : 0;

                                        $ket_denda_alpa =
                                            $denda != 0 ? '<p><span style="color:red">Denda : ' . formatAngka($denda) . '</span></p>' : '';

                                        $ket =
                                            '<h4 style="font-weight: bold; margin-bottom:10px">Alpa</h4>
                                        <span>PJ : ' .
                                            formatAngkaDesimal($potongan_jam) .
                                            ' Jam</span>' .
                                            $ket_denda_alpa;
                                    @endphp
                                @endif
                            @else
                                @php
                                    $bgcolor = 'red';
                                    $textcolor = 'white';
                                    $ket = '';
                                    $potongan_jam = 0;

                                    // Jika hari ini libur khusus karyawan, tandai libur
                                    if (!empty($ceklibur)) {
                                        $bgcolor = 'green';
                                        $textcolor = 'white';
                                        $jml_libur++;
                                        $ket = $ceklibur[0]['keterangan'];
                                    } else {
                                        // Bukan libur → cek jadwal berurutan:
                                        // 1) Jadwal by-date per karyawan
                                        $totalJamJadwal = $mapJadwalByDate[$tanggal_presensi] ?? null;

                                        // 2) Kalau kosong, cek jadwal grup by-date
                                        if ($totalJamJadwal === null) {
                                            $totalJamJadwal = $mapJadwalGrupByDate[$tanggal_presensi] ?? null;
                                        }

                                        // 3) Kalau masih kosong, cek jadwal by-day per karyawan
                                        if ($totalJamJadwal === null) {
                                            $totalJamJadwal = $mapJadwalByDay[$nama_hari] ?? null;
                                        }

                                        // 4) Kalau masih kosong, cek jadwal by-day per departemen & cabang
                                        if ($totalJamJadwal === null) {
                                            $keyDeptCabang = $d['kode_dept'] . '|' . $d['kode_cabang'];
                                            $mapDept = $jadwal_bydept[$keyDeptCabang] ?? [];
                                            $totalJamJadwal = $mapDept[$nama_hari] ?? null;
                                        }

                                        if ($totalJamJadwal !== null) {
                                            // Ada jadwal tapi tidak ada presensi sama sekali → Alpa & potong full jam kerja
                                            $jml_alfa++;
                                            $potongan_jam = $totalJamJadwal;

                                            // Untuk alpa yang belum ada di database, denda = 0
                                            $denda = 0;

                                            $ket =
                                                '<h4 style="font-weight: bold; margin-bottom:10px">Alpa</h4>
                                                <span>PJ : ' .
                                                formatAngkaDesimal($potongan_jam) .
                                                ' Jam</span>';
                                        }
                                    }

                                    // Jika ada lembur terpisah, tetap tampilkan info lembur
                                    if (!empty($ceklembur)) {
                                        $bgcolor = 'white';
                                        $textcolor = 'black';
                                        $ket_jam_lembur = '<p><span style="color:rgb(11, 153, 179)"> Lembur :' . $jml_jam_lembur . ' Jam</span></p>';
                                        $ket = $ket_jam_lembur;
                                    }
                                @endphp
                            @endif
                            @php
                                $total_denda += $denda;
                                $total_potongan_jam += $potongan_jam;
                                $total_jam_lembur += $jml_jam_lembur;

                                $bgcolor = $nama_hari == 'Minggu' ? 'orange' : $bgcolor;
                            @endphp
                            <td style="background-color:{{ $bgcolor }}; color:{{ $textcolor }}">
                                {!! $ket !!}

                            </td>
                            @php
                                $tanggal_presensi = date('Y-m-d', strtotime('+1 day', strtotime($tanggal_presensi)));
                            @endphp
                        @endwhile
                        <td style="text-align: right">{{ formatAngka($total_denda) }}</td>
                        <td style="text-align: center">{{ formatAngkaDesimal($total_potongan_jam) }}</td>
                        <td style="text-align:center">{{ formatAngkaDesimal($total_jam_lembur) }}</td>
                        <td style="text-align:center">{{ $jml_hadir }}</td>
                        <td style="text-align:center">{{ $jml_izin }}</td>
                        <td style="text-align:center">{{ $jml_sakit }}</td>
                        <td style="text-align:center">{{ $jml_alfa }}</td>
                        <td style="text-align:center">{{ $jml_libur }}</td>
                        <td style="text-align:center">{{ $jml_terlambat }}</td>
                        <td style="text-align:center">{{ $jml_tidakscanmasuk }}</td>
                        <td style="text-align:center">{{ $jml_tidakscanpulang }}</td>
                        <td style="text-align:center">{{ $jml_pulangcepat }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="margin-top: 30px; width: 350px;">
        <table border="1" cellpadding="4" cellspacing="0" style="border-collapse: collapse; width:100%;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th style="text-align:center;">Kode</th>
                    <th style="text-align:center;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align:center;">PC</td>
                    <td>Pulang Cepat</td>
                </tr>
                <tr>
                    <td style="text-align:center;">PJ</td>
                    <td>Potongan Jam</td>
                </tr>
            </tbody>
        </table>
    </div>
    <script>
        $(document).ready(function() {
            $('#formKunciLaporan').on('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin mengunci laporan ini? Denda akan disimpan ke database dan tidak akan berubah meskipun ada perubahan aturan denda.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ffc107',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Kunci Laporan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Sedang mengunci laporan',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Submit form
                        var form = $('#formKunciLaporan');
                        $.ajax({
                            url: form.attr('action'),
                            method: 'POST',
                            data: form.serialize(),
                            success: function(response) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                });
                            },
                            error: function(xhr) {
                                let message = 'Terjadi kesalahan saat mengunci laporan';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    message = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: message,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            });

            // Handler untuk batalkan kunci laporan
            $('#formBatalkanKunciLaporan').on('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin membatalkan kunci laporan ini? Denda yang sudah dikunci akan dihapus dan akan dihitung ulang berdasarkan aturan denda saat ini.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#6c757d',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Batalkan Kunci',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Sedang membatalkan kunci laporan',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Submit form
                        var form = $('#formBatalkanKunciLaporan');
                        $.ajax({
                            url: form.attr('action'),
                            method: 'POST',
                            data: form.serialize(),
                            success: function(response) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    // Reload halaman untuk refresh data
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                let message = 'Terjadi kesalahan saat membatalkan kunci laporan';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    message = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: message,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
