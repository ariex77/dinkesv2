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
        .form-label-group select,
        .form-label-group textarea {
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
        }
        
        .form-label-group textarea {
            height: 100px;
            padding-top: 25px;
            resize: none;
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
        .form-label-group textarea:focus,
        .form-label-group input:not(:placeholder-shown),
        .form-label-group select:valid,
        .form-label-group textarea:not(:placeholder-shown) {
            border-color: #32745e;
        }

        .form-label-group input:focus ~ label,
        .form-label-group select:focus ~ label,
        .form-label-group textarea:focus ~ label,
        .form-label-group input:not(:placeholder-shown) ~ label,
        .form-label-group select:valid ~ label,
        .form-label-group textarea:not(:placeholder-shown) ~ label {
            top: 5px;
            font-size: 11px;
            color: #32745e;
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

        /* Photo Profile Section */
        .profile-photo-container {
            position: relative;
            width: 100px;
            height: 100px;
            margin: 0 auto 15px auto;
        }

        .profile-photo-container img,
        .profile-photo-container .photo-bg {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #32745e;
        }

        .profile-photo-container .photo-bg {
            background-size: cover;
            background-position: center;
        }
    </style>
    <div id="header-section">
        <div class="appHeader bg-primary text-light">
            <div class="left">
                <a href="{{ route('dashboard.index') }}" class="headerButton goBack">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
            </div>
            <div class="pageTitle">Profile</div>
            <div class="right"></div>
        </div>
    </div>
    <div id="content-section">
        <div class="row" style="margin-top: 10px; padding-bottom:80px">
            <div class="col pl-3 pr-3">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="formProfile" autocomplete="off">
                    @csrf
                    @method('PUT')

                    {{-- Profile Photo --}}
                    <div style="text-align: center; margin-bottom: 15px;">
                        <div class="profile-photo-container">
                            @if (!empty($karyawan->foto) && Storage::disk('public')->exists('/karyawan/' . $karyawan->foto))
                                <div class="photo-bg" style="background-image: url({{ getfotoKaryawan($karyawan->foto) }});"></div>
                            @else
                                <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" alt="Profile Photo">
                            @endif
                        </div>
                    </div>

                    {{-- Nama Lengkap --}}
                    <div class="form-label-group">
                        <ion-icon name="person-outline" class="input-icon"></ion-icon>
                        <input type="text" name="nama_karyawan" id="nama_karyawan" class="form-control" placeholder=" " value="{{ $karyawan->nama_karyawan ?? '' }}" required>
                        <label for="nama_karyawan">Nama Lengkap</label>
                    </div>

                    {{-- No. KTP --}}
                    <div class="form-label-group">
                        <ion-icon name="card-outline" class="input-icon"></ion-icon>
                        <input type="text" name="no_ktp" id="no_ktp" class="form-control" placeholder=" " value="{{ $karyawan->no_ktp ?? '' }}" required>
                        <label for="no_ktp">No. KTP</label>
                    </div>

                    {{-- No. HP --}}
                    <div class="form-label-group">
                        <ion-icon name="call-outline" class="input-icon"></ion-icon>
                        <input type="text" name="no_hp" id="no_hp" class="form-control" placeholder=" " value="{{ $karyawan->no_hp ?? '' }}" required>
                        <label for="no_hp">No. HP</label>
                    </div>

                    {{-- Alamat --}}
                    <div class="form-label-group">
                        <ion-icon name="location-outline" class="input-icon"></ion-icon>
                        <textarea name="alamat" id="alamat" class="form-control" placeholder=" " required>{{ $karyawan->alamat ?? '' }}</textarea>
                        <label for="alamat">Alamat</label>
                    </div>

                    {{-- Username --}}
                    <div class="form-label-group">
                        <ion-icon name="at-outline" class="input-icon"></ion-icon>
                        <input type="text" name="username" id="username" class="form-control" placeholder=" " value="{{ $user->username }}" required>
                        <label for="username">Username</label>
                    </div>

                    {{-- Email --}}
                    <div class="form-label-group">
                        <ion-icon name="mail-outline" class="input-icon"></ion-icon>
                        <input type="email" name="email" id="email" class="form-control" placeholder=" " value="{{ $user->email }}" required>
                        <label for="email">Email</label>
                    </div>

                    {{-- Upload Foto --}}
                    <div class="custom-file-upload" id="fileUploadBox">
                        <input type="file" name="foto" id="foto" accept=".jpg, .jpeg, .png">
                        <label for="foto">
                            <ion-icon name="camera-outline"></ion-icon>
                            <span>Upload Foto Profil</span>
                            <div id="fileName" class="file-name"></div>
                        </label>
                    </div>

                    {{-- Submit Button --}}
                    <div class="form-group mt-3">
                        <button class="btn btn-primary w-100" id="btnSimpan" style="height: 50px; border-radius: 9px;">
                            <i class="ti ti-send me-1"></i> Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('myscript')
    <script>
        // File Upload Handling
        $('#foto').on('change', function() {
            let file = this.files[0];
            if (file) {
                 $('#fileName').text(file.name);
            } else {
                 $('#fileName').text('');
            }
        });

        $(function() {
            $("#formProfile").submit(function(e) {
                let nama_karyawan = $('input[name="nama_karyawan"]').val();
                let no_ktp = $('input[name="no_ktp"]').val();
                let no_hp = $('input[name="no_hp"]').val();
                let alamat = $('textarea[name="alamat"]').val();
                let username = $('input[name="username"]').val();
                let email = $('input[name="email"]').val();

                if (nama_karyawan == "") {
                    Swal.fire({title: "Oops!", text: 'Nama Lengkap Harus Diisi !', icon: "warning"});
                    return false;
                } else if (no_ktp == "") {
                    Swal.fire({title: "Oops!", text: 'No. KTP Harus Diisi !', icon: "warning"});
                    return false;
                } else if (no_hp == "") {
                    Swal.fire({title: "Oops!", text: 'No. HP Harus Diisi !', icon: "warning"});
                    return false;
                } else if (alamat == "") {
                    Swal.fire({title: "Oops!", text: 'Alamat Harus Diisi !', icon: "warning"});
                    return false;
                } else if (username == "") {
                    Swal.fire({title: "Oops!", text: 'Username Harus Diisi !', icon: "warning"});
                    return false;
                } else if (email == "") {
                    Swal.fire({title: "Oops!", text: 'Email Harus Diisi !', icon: "warning"});
                    return false;
                }

                buttonDisabled();
            });

            function buttonDisabled() {
                $("#btnSimpan").prop('disabled', true);
                $("#btnSimpan").html(`<div class="spinner-border spinner-border-sm text-white me-2" role="status"></div> Sedang Menyimpan..`);
            }
        });
    </script>
@endpush
