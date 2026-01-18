@extends('layouts.mobile.app')
@section('content')
    <style>
        .avatar {
            position: relative;
            width: 2.5rem;
            height: 2.5rem;
            cursor: pointer;
        }

        /* Tambahkan style untuk header dan content */
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
        }

        .historicard {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 10px;
            background-color: white;
        }

        .historicontent {
            display: flex;
            padding: 15px;
            align-items: center;
        }

        .iconpresence {
            flex-shrink: 0;
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            background-color: #e2f3f9; /* Blue tint */
            border-radius: 50%;
            color: #0d6efd;
        }

        .historidetail1 {
            flex-grow: 1;
        }
        
        .datepresence h4 {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }

        .timepresence {
            font-size: 12px;
            color: #666;
            margin-top: 2px;
            display: block;
        }

        .historidetail2 {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            min-width: 80px;
        }

        .historidetail2 h4 {
            margin: 0;
            font-size: 14px;
            font-weight: 700;
        }
        
        .status-active { color: #198754; font-weight: bold; } /* Green */
        .status-inactive { color: #dc3545; font-weight: bold; } /* Red */
    </style>

    <div id="header-section">
        <div class="appHeader bg-primary text-light">
            <div class="left">
                <a href="{{ route('shortcut.index') }}" class="headerButton goBack">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
            </div>
            <div class="pageTitle">Riwayat Kontrak Kerja</div>
            <div class="right"></div>
        </div>
    </div>

    <div id="content-section">
        <div class="row overflow-scroll" style="height: 100vh; padding-bottom: 100px;">
            <div class="col">
                @if ($kontraks->isEmpty())
                    <div class="alert alert-warning d-flex align-items-center" style="margin: 15px;">
                        <ion-icon name="information-circle-outline" style="font-size: 24px;" class="mr-2"></ion-icon>
                        <p style="font-size: 14px; margin-bottom: 0; margin-left: 10px;">Belum ada data kontrak kerja</p>
                    </div>
                @else
                    @foreach ($kontraks as $d)
                        @php
                            $statusClass = $d->status_kontrak == 1 ? 'status-active' : 'status-inactive';
                            $statusText = $d->status_kontrak == 1 ? 'Aktif' : 'Non-Aktif';
                        @endphp
                        <div class="card historicard mb-2 mx-2">
                            <div class="historicontent">
                                <div class="iconpresence">
                                    <ion-icon name="document-text-outline" style="font-size: 32px; color: #0d6efd"></ion-icon>
                                </div>
                                <div class="historidetail1">
                                    <div class="datepresence">
                                        <h4>No. {{ $d->no_kontrak }}</h4>
                                        <span class="timepresence">
                                            Masa Kerja: <br>
                                            <strong>{{ \Carbon\Carbon::parse($d->dari)->translatedFormat('d M Y') }}</strong> s/d 
                                            <strong>{{ \Carbon\Carbon::parse($d->sampai)->translatedFormat('d M Y') }}</strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="historidetail2">
                                    <span class="{{ $statusClass }}" style="font-size: 12px; margin-bottom: 5px;">{{ $statusText }}</span>
                                    <a href="{{ route('kontrak.show', Crypt::encrypt($d->id)) }}" class="btn btn-sm btn-primary mt-1" style="padding: 2px 8px; font-size: 10px; color: white;">
                                        <ion-icon name="eye-outline"></ion-icon> Lihat
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection
