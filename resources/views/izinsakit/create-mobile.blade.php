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
        }

        /* Custom Floating Label CSS */
        .form-label-group {
            position: relative;
            margin-bottom: 5px; /* Compact spacing */
        }

        .form-label-group .input-icon {
            position: absolute;
            left: 15px;
            top: 15px; /* Align with top padding of input */
            font-size: 22px;
            color: #32745e;
            z-index: 9;
            transition: all 0.3s ease;
            pointer-events: none; /* Ensure clicks pass through to input */
        }

        .form-label-group input,
        .form-label-group select,
        .form-label-group textarea {
            border-radius: 9px;
            height: 50px; /* Consistent height */
            padding: 20px 15px 5px 50px; /* Left padding increased for icon */
            font-size: 15px;
            line-height: 1.5;
            background-color: transparent !important; /* Transparent background */
            border: 1px solid #32745e; /* Green border */
            box-shadow: none;
            width: 100%;
            display: block;
            transition: all .1s;
        }
        
        .form-label-group textarea {
            height: 100px;
            padding-top: 25px; /* More padding top for textarea */
            resize: none;
        }

        .form-label-group label {
            position: absolute;
            top: 15px;
            left: 50px; /* Aligned with text start (after icon) */
            font-size: 15px;
            color: #32745e; /* Green label */
            pointer-events: none;
            transition: all .2s ease-in-out;
            margin-bottom: 0;
            background: transparent;
        }

        /* Active State (Focus or Has Value) */
        .form-label-group input:focus,
        .form-label-group select:focus,
        .form-label-group textarea:focus,
        .form-label-group input:not(:placeholder-shown),
        .form-label-group select:valid,
        .form-label-group textarea:not(:placeholder-shown) {
            border-color: #32745e; /* Theme color */
        }

        .form-label-group input:focus ~ label,
        .form-label-group select:focus ~ label,
        .form-label-group textarea:focus ~ label,
        .form-label-group input:not(:placeholder-shown) ~ label,
        .form-label-group select:valid ~ label,
        .form-label-group textarea:not(:placeholder-shown) ~ label {
            top: 5px;
            font-size: 11px;
            color: #32745e; /* Theme color */
            font-weight: 500;
        }
        
        /* Disabled Input Style */
        .form-label-group input:disabled {
            background-color: rgba(50, 116, 94, 0.05) !important;
            color: #32745e;
        }

        /* Custom File Upload (Dashed Box) */
        .custom-file-upload {
            border: 2px dashed #32745e;
            border-radius: 9px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            margin-bottom: 5px;
            transition: all 0.3s ease;
            background: rgba(50, 116, 94, 0.05);
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100px;
        }
        
        .custom-file-upload:hover {
            background: rgba(50, 116, 94, 0.1);
            border-color: #2a6350;
        }
        
        .custom-file-upload input[type="file"] {
            display: none;
        }
        
        .custom-file-upload label {
            cursor: pointer;
            display: block;
            color: #32745e;
            margin: 0;
            width: 100%;
        }
        
        .custom-file-upload ion-icon {
            font-size: 32px;
            margin-bottom: 5px;
            color: #32745e;
        }
        
        .file-name {
            font-size: 12px;
            color: #32745e;
            margin-top: 5px;
            font-weight: 500;
        }

    </style>
    <div id="header-section">
        <div class="appHeader bg-primary text-light">
            <div class="left">
                <a href="{{ route('dashboard.index') }}" class="headerButton goBack">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
            </div>
            <div class="pageTitle">Izin Sakit</div>
            <div class="right"></div>
        </div>
    </div>
    <div id="content-section">
        <div class="row" style="margin-top: 10px">
            <div class="col pl-3 pr-3">
                <form action="{{ route('izinsakit.store') }}" method="POST" id="formIzin" enctype="multipart/form-data" autocomplete="off">
                    @csrf

                    <div class="form-label-group">
                        <ion-icon name="calendar-outline" class="input-icon"></ion-icon>
                        <input type="text" name="dari" id="dari" class="form-control" placeholder=" " required>
                        <label for="dari">Dari Tanggal</label>
                    </div>

                    <div class="form-label-group">
                        <ion-icon name="calendar-outline" class="input-icon"></ion-icon>
                        <input type="text" name="sampai" id="sampai" class="form-control" placeholder=" " required>
                        <label for="sampai">Sampai Tanggal</label>
                    </div>

                    <div class="form-label-group">
                        <ion-icon name="calculator-outline" class="input-icon"></ion-icon>
                        <input type="text" name="jml_hari" id="jml_hari" class="form-control" placeholder=" " readonly>
                        <label for="jml_hari">Jumlah Hari</label>
                    </div>
                    
                    <div class="custom-file-upload" id="fileUploadBox">
                        <input type="file" name="sid" id="sid" accept=".png, .jpg, .jpeg, .pdf">
                        <label for="sid">
                            <ion-icon name="cloud-upload-outline"></ion-icon>
                            <span>Upload Surat Dokter (SID)</span>
                            <div id="fileName" class="file-name"></div>
                        </label>
                    </div>

                    <div class="form-label-group">
                        <ion-icon name="document-text-outline" class="input-icon"></ion-icon>
                        <textarea name="keterangan" id="keterangan" class="form-control" placeholder=" " required></textarea>
                        <label for="keterangan">Keterangan</label>
                    </div>

                    <div class="form-group mt-3">
                        <button class="btn btn-primary w-100" id="btnSimpan" style="height: 50px; border-radius: 9px;">
                            <i class="ti ti-send me-1"></i> Kirim Izin
                        </button>
                    </div>
                </form>
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

        const batasi_hari_izin = "{{ $general_setting->batasi_hari_izin }}";
        const jml_hari_izin_max = "{{ $general_setting->jml_hari_izin_max }}";

        function hitungHari(startDate, endDate) {
            if (startDate && endDate) {
                var start = new Date(startDate);
                var end = new Date(endDate);
                var timeDifference = end - start + (1000 * 3600 * 24);
                var dayDifference = timeDifference / (1000 * 3600 * 24);
                return dayDifference;
            } else {
                return 0;
            }
        }

        new AirDatepicker('#dari', {
            locale: localeIndo,
            autoClose: true,
            isMobile: true,
            buttons: ['today', 'clear'],
            position: 'auto center',
            onSelect: ({date, formattedDate, datepicker}) => {
                let sampai = $('#sampai').val();
                let jmlhari = hitungHari(formattedDate, sampai);
                $('#jml_hari').val(jmlhari);
            }
        });

        new AirDatepicker('#sampai', {
            locale: localeIndo,
            autoClose: true,
            isMobile: true,
            buttons: ['today', 'clear'],
            position: 'auto center',
            onSelect: ({date, formattedDate, datepicker}) => {
                let dari = $('#dari').val();
                let jmlhari = hitungHari(dari, formattedDate);
                $('#jml_hari').val(jmlhari);
            }
        });

        // File Upload Handling
        $('#sid').on('change', function() {
            let file = this.files[0];
            if (file) {
                 $('#fileName').text(file.name);
            } else {
                 $('#fileName').text('');
            }
        });

        $("#formIzin").submit(function(e) {
            let dari = $('#dari').val();
            let sampai = $('#sampai').val();
            let jml_hari = $('#jml_hari').val();
            let keterangan = $('#keterangan').val();
            let sid = $('#sid').val();

            if (dari == "" || sampai == "") {
                Swal.fire({title: "Oops!", text: 'Periode Izin Harus Diisi !', icon: "warning"});
                return false;
            } else if (jml_hari == "") {
                 Swal.fire({title: "Oops!", text: 'Jumlah Hari Harus Diisi !', icon: "warning"});
                 return false;
            } else if (sampai < dari) {
                 Swal.fire({title: "Oops!", text: 'Periode Izin Tidak Valid !', icon: "warning"});
                 return false;
            } else if (hitungHari(dari, sampai) > jml_hari_izin_max && batasi_hari_izin == 1) {
                 Swal.fire({title: "Oops!", text: 'Maksimal Izin ' + jml_hari_izin_max + ' Hari !', icon: "warning"});
                 return false;
            } else if (sid == "") {
                 Swal.fire({title: "Oops!", text: 'Surat Dokter Harus Diupload !', icon: "warning"});
                 return false;
            } else if (keterangan == "") {
                 Swal.fire({title: "Oops!", text: 'Keterangan Harus Diisi !', icon: "warning"});
                 return false;
            }
            
            buttonDisabled();
        });

        function buttonDisabled() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`<div class="spinner-border spinner-border-sm text-white me-2" role="status"></div> Loading..`);
        }
    </script>
@endpush
