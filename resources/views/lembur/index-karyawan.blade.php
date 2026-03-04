@extends('layouts.mobile.app')
@section('content')
    <style>
        :root {
            --primary-color: #003d9e;
            --primary-light: #e0e9f5;
            --success-color: #28a745;
            --success-light: #d4edda;
            --danger-color: #dc3545;
            --danger-light: #f8d7da;
            --warning-color: #ffc107;
            --warning-light: #fff3cd;
        }

        /* Modern Header */
        #header-section {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        /* 
           We use the standard .appHeader from the main layout. 
           No custom .header-content needed here to ensure consistency. 
        */

        #content-section {
            margin-top: 70px;
            padding: 0 16px;
            padding-bottom: 100px;
        }

        /* Filter Section */
        .filter-card {
            background: #fff;
            border-radius: 16px;
            padding: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
            margin-bottom: 20px;
            border: 1px solid #f0f0f0;
        }

        .form-group-custom {
            margin-bottom: 0;
            position: relative;
        }

        .input-icon-wrapper {
            position: relative;
        }

        .input-icon-wrapper ion-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: var(--primary-color);
        }

        .custom-input {
            width: 100%;
            height: 48px;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 0 16px 0 44px;
            font-size: 14px;
            color: #333;
            transition: all 0.3s ease;
        }

        .custom-input:focus {
            background: #fff;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 61, 158, 0.1);
            outline: none;
        }

        /* Card Styles */
        .history-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 16px 20px;
            margin-bottom: 16px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
            border: 1px solid #f5f5f5;
            transition: transform 0.2s;
            position: relative;
            overflow: hidden;
        }

        .history-card:active {
            transform: scale(0.98);
        }

        .card-header-custom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            border-bottom: 1px dashed #eee;
            padding-bottom: 10px;
        }

        .date-display {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .date-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 18px;
        }

        .date-text {
            display: flex;
            flex-direction: column;
        }

        .day-name {
            font-size: 14px;
            font-weight: 700;
            color: #333;
        }

        .full-date {
            font-size: 11px;
            color: #888;
        }

        /* Status Badges */
        .status-badge {
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .status-pending {
            background: var(--warning-light);
            color: #d39e00;
        }

        .status-success {
            background: var(--success-light);
            color: var(--success-color);
        }

        .status-danger {
            background: var(--danger-light);
            color: var(--danger-color);
        }

        .card-body-custom {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 10px;
        }

        .time-box {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .time-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #555;
            font-weight: 500;
        }

        .time-item ion-icon {
            font-size: 16px;
            opacity: 0.7;
        }
        
        .duration-badge {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            color: #555;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .keterangan-text {
            font-size: 13px;
            color: #777;
            margin-top: 12px;
            line-height: 1.5;
            background: #f9f9f9;
            padding: 10px;
            border-radius: 8px;
            border-left: 3px solid var(--primary-color);
        }

        /* FAB */
        .fab-container {
            position: fixed;
            bottom: 30px;
            right: 20px;
            z-index: 1001;
        }

        .fab-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #003d9e 0%, #0056b3 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 30px;
            box-shadow: 0 8px 25px rgba(0, 61, 158, 0.4);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: none;
            text-decoration: none;
        }

        .fab-btn:active {
            transform: scale(0.9);
            box-shadow: 0 4px 15px rgba(0, 61, 158, 0.3);
        }

        /* Delete action */
        .delete-btn {
            position: absolute;
            top: 16px;
            right: 16px;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff0f0;
            color: var(--danger-color);
            border-radius: 8px;
            font-size: 16px;
            opacity: 0;
            transition: all 0.3s ease;
            cursor: pointer;
            z-index: 2;
        }

        .history-card:hover .delete-btn {
            opacity: 1;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            opacity: 0.7;
        }

        .empty-icon {
            font-size: 64px;
            color: #ccc;
            margin-bottom: 16px;
        }
    </style>

    <!-- Header -->
    <div id="header-section">
        <div class="appHeader bg-primary text-light">
            <div class="left">
                <a href="{{ route('dashboard.index') }}" class="headerButton goBack">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
            </div>
            <div class="pageTitle">Lembur</div>
            <div class="right"></div>
        </div>
    </div>

    <div id="content-section">
        <!-- Filter Card -->
        <div class="filter-card">
            <form action="{{ route('lembur.index') }}" method="GET" id="filterForm">
                <div class="row g-2">
                    <div class="col-6">
                        <div class="form-group-custom">
                            <div class="input-icon-wrapper">
                                <ion-icon name="calendar-outline"></ion-icon>
                                <input type="text" class="custom-input flatpickr-date" name="dari" 
                                    placeholder="Dari" id="datePicker" value="{{ Request('dari') }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group-custom">
                            <div class="input-icon-wrapper">
                                <ion-icon name="calendar-outline"></ion-icon>
                                <input type="text" class="custom-input flatpickr-date" name="sampai" 
                                    placeholder="Sampai" id="datePicker2" value="{{ Request('sampai') }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <button class="btn btn-primary w-100" style="border-radius: 12px; height: 48px; font-weight: 600;">
                            <ion-icon name="search-outline" class="me-1"></ion-icon> Filter Data
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Transaction List -->
        <div class="transactions">
            @forelse ($lembur as $d)
                @php
                    $start = strtotime($d->lembur_mulai);
                    $end = strtotime($d->lembur_selesai);
                    $diff = $end - $start;
                    $hours = floor($diff / 3600);
                    $minutes = floor(($diff % 3600) / 60);
                    $duration = $hours . " Jam " . ($minutes > 0 ? $minutes . " Menit" : "");
                    
                    // Status Config
                    $statusClass = 'status-pending';
                    $statusText = 'Pending';
                    $statusIcon = 'time-outline';
                    
                    if ($d->status == 1) {
                        $statusClass = 'status-success';
                        $statusText = 'Disetujui';
                        $statusIcon = 'checkmark-circle-outline';
                    } elseif ($d->status == 2) {
                        $statusClass = 'status-danger';
                        $statusText = 'Ditolak';
                        $statusIcon = 'close-circle-outline';
                    }
                @endphp

                <div class="history-card">
                    <!-- Delete Button for Pending Items -->
                    @if($d->status == 0)
                    <form method="POST" action="{{ route('lembur.delete', Crypt::encrypt($d->id)) }}" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <div class="delete-btn delete-confirm">
                            <ion-icon name="trash-outline"></ion-icon>
                        </div>
                    </form>
                    @endif

                    <div class="card-header-custom">
                        <div class="date-display">
                            <div class="date-icon">
                                <ion-icon name="calendar-clear-outline"></ion-icon>
                            </div>
                            <div class="date-text">
                                <span class="day-name">{{ date('l', strtotime($d->tanggal)) }}</span>
                                <span class="full-date">{{ DateToIndo($d->tanggal) }}</span>
                            </div>
                        </div>
                        <div class="status-badge {{ $statusClass }}">
                            <ion-icon name="{{ $statusIcon }}"></ion-icon>
                            {{ $statusText }}
                        </div>
                    </div>

                    <div class="card-body-custom">
                        <div class="time-box">
                            <div class="time-item">
                                <ion-icon name="play-circle-outline" style="color: var(--success-color)"></ion-icon>
                                {{ date('H:i', strtotime($d->lembur_mulai)) }}
                            </div>
                            <div class="time-item">
                                <ion-icon name="stop-circle-outline" style="color: var(--danger-color)"></ion-icon>
                                {{ date('H:i', strtotime($d->lembur_selesai)) }}
                            </div>
                        </div>
                        <div class="duration-badge">
                            <ion-icon name="hourglass-outline"></ion-icon>
                            {{ $duration }}
                        </div>
                    </div>

                    @if(!empty($d->keterangan))
                    <div class="keterangan-text">
                        "{{ $d->keterangan }}"
                    </div>
                    @endif
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">
                        <ion-icon name="documents-outline"></ion-icon>
                    </div>
                    <h4>Belum ada data</h4>
                    <p>Belum ada riwayat lembur yang ditemukan untuk filter ini.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Floating Action Button -->
    <div class="fab-container">
        <a href="{{ route('lembur.create') }}" class="fab-btn">
            <ion-icon name="add-outline"></ion-icon>
        </a>
    </div>

@endsection

@push('myscript')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Delete Confirmation
        $(".delete-confirm").click(function(e) {
            var form = $(this).closest("form");
            e.preventDefault();
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Data ajuan lembur ini akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        });

        // Date Picker Configuration
        const indonesianLocale = {
            firstDayOfWeek: 1,
            weekdays: {
                shorthand: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                longhand: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']
            },
            months: {
                shorthand: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                longhand: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
            }
        };

        const config = {
            dateFormat: 'Y-m-d',
            allowInput: false,
            monthSelectorType: 'static',
            disableMobile: "true",
            locale: indonesianLocale,
            theme: "material_blue"
        };

        flatpickr("#datePicker", config);
        flatpickr("#datePicker2", config);
    </script>
@endpush
