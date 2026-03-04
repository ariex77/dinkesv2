@extends('layouts.mobile.app')
@section('content')
    <style>
        #header-section {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        #content-section {
            margin-top: 70px;
            padding-top: 5px;
            position: relative;
            z-index: 1;
            padding-bottom: 80px;
        }

        /* Custom Floating Label CSS for Filter */
        .form-label-group {
            position: relative;
            margin-bottom: 5px;
        }

        .form-label-group .input-icon {
            position: absolute;
            left: 15px;
            top: 15px;
            font-size: 22px;
            color: #32745e;
            z-index: 9;
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .form-label-group input,
        .form-label-group select {
            border-radius: 9px;
            height: 50px;
            padding: 20px 15px 5px 50px;
            font-size: 15px;
            line-height: 1.5;
            background-color: transparent !important;
            border: 1px solid #32745e;
            box-shadow: none;
            width: 100%;
            display: block;
            transition: all .1s;
             -webkit-appearance: none;
             -moz-appearance: none;
             appearance: none;
        }

        .form-label-group label {
            position: absolute;
            top: 15px;
            left: 50px;
            font-size: 15px;
            color: #32745e;
            pointer-events: none;
            transition: all .2s ease-in-out;
            margin-bottom: 0;
            background: transparent;
        }

         /* Active State (Focus or Has Value) */
        .form-label-group input:focus,
        .form-label-group select:focus,
        .form-label-group input:not(:placeholder-shown),
        .form-label-group select:valid {
            border-color: #32745e;
        }

        .form-label-group input:focus ~ label,
        .form-label-group select:focus ~ label,
        .form-label-group input:not(:placeholder-shown) ~ label,
        .form-label-group select:valid ~ label {
            top: 5px;
            font-size: 11px;
            color: #32745e;
            font-weight: 500;
        }

        /* History Card Style */
        .history-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 12px;
            border: 1px solid #e0e0e0;
            overflow: hidden;
            transition: transform 0.2s;
        }
        
        .history-card:active {
            transform: scale(0.98);
        }

        .history-header {
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(50, 116, 94, 0.05);
        }

        .history-date {
            font-weight: 600;
            color: #333;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .history-date ion-icon {
            color: #32745e;
            font-size: 18px;
        }

        .history-status {
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 500;
        }
        
        .status-h { background: #32745e; color: white; } /* Hadir */
        .status-i { background: #17a2b8; color: white; } /* Izin */
        .status-s { background: #ffc107; color: #333; }  /* Sakit */
        .status-a { background: #dc3545; color: white; } /* Alpha */
        .status-c { background: #6f42c1; color: white; } /* Cuti */

        .history-content {
            padding: 15px;
        }

        .time-badge {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }
        
        .time-label {
            width: 70px;
            font-size: 12px;
            color: #666;
        }
        
        .time-value {
            font-weight: 600;
            font-size: 15px;
            color: #333;
        }

    </style>

    <div id="header-section">
        <div class="appHeader bg-primary text-light">
            <div class="left">
                <a href="{{ route('dashboard.index') }}" class="headerButton goBack">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
            </div>
            <div class="pageTitle">Histori Presensi</div>
            <div class="right"></div>
        </div>
    </div>

    <div id="content-section">
        <div class="row" style="margin-top: 10px">
            <div class="col">
                <form method="GET" action="{{ route('presensi.histori') }}" id="formHistori">
                   <div class="row">
                       <div class="col-6">
                           <div class="form-label-group">
                                <ion-icon name="calendar-outline" class="input-icon"></ion-icon>
                                <input type="text" name="dari" id="dari" class="form-control datepicker" placeholder=" " value="{{ Request('dari') }}" autocomplete="off" required>
                                <label for="dari">Dari</label>
                           </div>
                       </div>
                       <div class="col-6">
                           <div class="form-label-group">
                                <ion-icon name="calendar-outline" class="input-icon"></ion-icon>
                                <input type="text" name="sampai" id="sampai" class="form-control datepicker" placeholder=" " value="{{ Request('sampai') }}" autocomplete="off" required>
                                <label for="sampai">Sampai</label>
                           </div>
                       </div>
                   </div>
                   <div class="row mt-1">
                       <div class="col-12">
                           <button class="btn btn-primary w-100" type="submit" id="btnCari" style="height: 45px; border-radius: 9px; font-size: 14px;">
                                <ion-icon name="search-outline" class="me-1"></ion-icon> Cari Data
                           </button>
                       </div>
                   </div>
                </form>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12" id="showhistori" style="padding-left: 15px; padding-right: 15px;">
                @foreach ($datapresensi as $d)
                    <div class="card mb-1 card-hover" style="border: 1px solid #32745e; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
                        <div class="card-body p-1 d-flex align-items-center">
                            <div class="icon-container mr-1 d-flex align-items-center justify-content-center" 
                                style="width: 45px; height: 45px; border-radius: 12px; flex-shrink: 0; 
                                background-color: {{ $d->status == 'h' ? 'rgba(50, 116, 94, 0.1)' : ($d->status == 'i' ? 'rgba(30, 144, 255, 0.1)' : ($d->status == 's' ? 'rgba(255, 99, 132, 0.1)' : ($d->status == 'c' ? 'rgba(255, 159, 64, 0.1)' : 'rgba(231, 76, 60, 0.1)'))) }};">
                                @php
                                    $namahari = ['Sun' => 'Minggu', 'Mon' => 'Senin', 'Tue' => 'Selasa', 'Wed' => 'Rabu', 'Thu' => 'Kamis', 'Fri' => 'Jumat', 'Sat' => 'Sabtu'];
                                    $day_eng = date('D', strtotime($d->tanggal));
                                    $day_indo = isset($namahari[$day_eng]) ? $namahari[$day_eng] : $day_eng;
                                    $day_short = strtoupper(substr($day_indo, 0, 3));
                                    $tgl = date('d', strtotime($d->tanggal));
                                    
                                    $text_color = $d->status == 'h' ? '#32745e' : ($d->status == 'i' ? '#1e90ff' : ($d->status == 's' ? '#ff6384' : ($d->status == 'c' ? '#ff9f40' : '#e74c3c')));
                                @endphp
                                <div style="text-align: center; line-height: 1;">
                                    <span style="font-size: 10px; font-weight: 700; display: block; color: {{ $text_color }};">{{ $day_short }}</span>
                                    <span style="font-size: 16px; font-weight: 800; display: block; margin-top: 1px; color: {{ $text_color }};">{{ $tgl }}</span>
                                </div>
                            </div>
                            <div class="flex-grow-1 overflow-hidden" style="padding-left: 10px;">
                                <div class="row align-items-center">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center mb-0">
                                            <h5 class="mb-0 text-truncate" style="font-size: 14px; font-weight: 600; color: #333;">{{ DateToIndo($d->tanggal) }}</h5>
                                            <span class="badge" style="background-color: #f8f9fa; color: #666; font-weight: normal; font-size: 10px; border: 1px solid #eee;">
                                                {{ $d->nama_jam_kerja }} ({{ date('H:i', strtotime($d->jam_masuk)) }} - {{ date('H:i', strtotime($d->jam_pulang)) }})
                                            </span>
                                        </div>
                                        <div class="mb-1">
                                            @if ($d->status == 'h')
                                                @php
                                                    $jam_in_ts = strtotime($d->jam_in);
                                                    $jam_masuk_ts = strtotime($d->tanggal . ' ' . $d->jam_masuk);
                                                    $is_late = $jam_in_ts > $jam_masuk_ts;
                                                    $jam_telat = 0;
                                                    $menit_telat = 0;
                                                    $desimal_terlambat = 0;
                                                    
                                                    if ($is_late && $d->jam_in != null) {
                                                        $terlambat_selisih = $jam_in_ts - $jam_masuk_ts;
                                                        $jam_telat = floor($terlambat_selisih / 3600);
                                                        $sisa = $terlambat_selisih % 3600;
                                                        $menit_telat = floor($sisa / 60);
                                                        $desimal_terlambat = $jam_telat + round($menit_telat / 60, 2);
                                                    }
                                                    
                                                    $denda_display = 0;
                                                    $potongan_jam = 0;
                                                    $potongan_jam_terlambat = 0;
                                                    $pulangcepat = 0;
                                                    $potongan_tidak_scan = 0;
                                                    
                                                    $denda_dari_db = !empty($d->denda) ? $d->denda : null;

                                                    if ($denda_dari_db !== null) {
                                                        $denda_display = $denda_dari_db;
                                                        if ($is_late) {
                                                            if ($desimal_terlambat >= 1) {
                                                                $potongan_jam_terlambat = $desimal_terlambat > $d->total_jam ? $d->total_jam : $desimal_terlambat;
                                                            }
                                                        }
                                                    } else {
                                                        if ($is_late){
                                                            if ($desimal_terlambat < 1) {
                                                                $denda_display = hitungdenda($denda_list, $menit_telat);
                                                                $potongan_jam_terlambat = 0;
                                                            } else {
                                                                $denda_display = 0;
                                                                $potongan_jam_terlambat = $desimal_terlambat > $d->total_jam ? $d->total_jam : $desimal_terlambat;
                                                            }
                                                        }
                                                    }

                                                    $pulangcepat = hitungpulangcepat(
                                                        $d->tanggal,
                                                        $d->jam_out,
                                                        $d->jam_pulang,
                                                        $d->istirahat,
                                                        $d->jam_awal_istirahat,
                                                        $d->jam_akhir_istirahat,
                                                        $d->lintashari
                                                    );
                                                    $pulangcepat = $pulangcepat > $d->total_jam ? $d->total_jam : $pulangcepat;

                                                    if ($d->tanggal != date('Y-m-d')) {
                                                        if (empty($d->jam_out) || empty($d->jam_in)) {
                                                            $potongan_tidak_scan = $d->total_jam;
                                                        }
                                                    }

                                                    if ($potongan_tidak_scan > 0) {
                                                        $potongan_jam = $potongan_tidak_scan;
                                                    } else {
                                                        $potongan_jam = $pulangcepat + $potongan_jam_terlambat;
                                                    }

                                                    $status_potongan_row = isset($d->status_potongan) ? $d->status_potongan : $namasettings->status_potongan_jam;
                                                    
                                                    if ($status_potongan_row == 0) {
                                                        $potongan_jam = 0;
                                                        $denda_display = 0;
                                                    }
                                                @endphp
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span style="color: #555; font-size: 12px; font-weight: 500;">
                                                        {{ $d->jam_in != null ? date('H:i', strtotime($d->jam_in)) : '__:__' }}
                                                        <span style="color: #ccc; margin: 0 5px;">-</span>
                                                        {{ $d->jam_out != null ? date('H:i', strtotime($d->jam_out)) : '__:__' }}
                                                    </span>
                                                    @if ($is_late)
                                                        <span class="badge bg-danger" style="font-size: 10px;">
                                                            Telat {{ $jam_telat > 0 ? $jam_telat . 'j ' : '' }}{{ $menit_telat }}m
                                                        </span>
                                                    @else
                                                        <span class="badge bg-success" style="font-size: 10px;">Tepat Waktu</span>
                                                    @endif
                                                </div>
                                            @elseif ($d->status == 'i')
                                                <span style="color: #1e90ff; font-size: 12px;">Izin: {{ $d->keterangan_izin }}</span>
                                            @elseif ($d->status == 's')
                                                <span style="color: #ff6384; font-size: 12px;">Sakit: {{ $d->keterangan_izin_sakit }}</span>
                                            @elseif ($d->status == 'c')
                                                <span style="color: #ff9f40; font-size: 12px;">Cuti: {{ $d->keterangan_izin_cuti }}</span>
                                            @elseif ($d->status == 'a')
                                                @php
                                                    $potongan_jam = $d->total_jam;
                                                    $denda_display = !empty($d->denda) ? $d->denda : 0;
                                                    $status_potongan_row = isset($d->status_potongan) ? $d->status_potongan : $namasettings->status_potongan_jam;
                                                    if ($status_potongan_row == 0) {
                                                        $potongan_jam = 0;
                                                    }
                                                @endphp
                                                <span style="color: #e74c3c; font-size: 12px;">Alpha: Tanpa Keterangan</span>
                                            @endif
                                        </div>
                                        
                                        <div class="d-flex flex-wrap gap-1">
                                            @if ($d->status == 'h' && $d->jam_in != null)
                                                @if ($denda_display > 0)
                                                    <span class="badge bg-danger" style="font-size: 10px;">
                                                        Denda Rp. {{ number_format($denda_display) }}
                                                    </span>
                                                @endif
                                                
                                                @if ($pulangcepat > 0)
                                                    <span class="badge bg-danger" style="font-size: 10px;">
                                                        Pulang Cepat
                                                    </span>
                                                @endif

                                                @if ($potongan_jam > 0 && ($d->jam_out != null || $d->tanggal != date('Y-m-d')))
                                                    @if ($namasettings->status_potongan_jam == 1 || (isset($d->status_potongan) && $d->status_potongan == 1))
                                                        <span class="badge bg-danger" style="font-size: 10px;">
                                                            PJ: {{ number_format($potongan_jam, 2) }} Jam
                                                        </span>
                                                    @endif
                                                @endif
                                            @elseif ($d->status == 'a')
                                                @if ($denda_display > 0)
                                                    <span class="badge bg-danger" style="font-size: 10px;">
                                                        Denda Rp. {{ number_format($denda_display) }}
                                                    </span>
                                                @endif
                                                @if ($potongan_jam > 0 && ($d->jam_out != null || $d->tanggal != date('Y-m-d')))
                                                    @if ($namasettings->status_potongan_jam == 1 || (isset($d->status_potongan) && $d->status_potongan == 1))
                                                        <span class="badge bg-danger" style="font-size: 10px;">
                                                            PJ: {{ number_format($potongan_jam, 2) }} Jam
                                                        </span>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                @if ($datapresensi->isEmpty())
                    <div class="text-center mt-5" style="opacity: 0.5;">
                        <ion-icon name="file-tray-outline" style="font-size: 64px; color: #32745e;"></ion-icon>
                        <p class="mt-2">Belum ada data presensi bulan ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@push('myscript')
    <link href="https://cdn.jsdelivr.net/npm/air-datepicker@3.5.0/air-datepicker.min.css" rel="stylesheet" type="text/css">
    <script src="https://cdn.jsdelivr.net/npm/air-datepicker@3.5.0/air-datepicker.min.js"></script>
    <script>
        // Custom locale for Air Datepicker
        const localeIndo = {
            days: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
            daysShort: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
            daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb'],
            months: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            today: 'Hari ini',
            clear: 'Hapus',
            dateFormat: 'yyyy-MM-dd',
            timeFormat: 'HH:mm',
            firstDay: 1
        };

        new AirDatepicker('#dari', {
            locale: localeIndo,
            autoClose: true,
            isMobile: true,
            buttons: ['today', 'clear'],
            position: 'auto center'
        });

        new AirDatepicker('#sampai', {
            locale: localeIndo,
            autoClose: true,
            isMobile: true,
            buttons: ['today', 'clear'],
            position: 'auto center'
        });

        $('#btnCari').click(function(e) {
            // Optional: Add loading state or validation if needed
        });
    </script>
@endpush
