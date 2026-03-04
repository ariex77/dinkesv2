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

        .btn-primary {
            background-color: #32745e !important;
            border-color: #32745e !important;
        }
    </style>
    <div id="header-section">
        <div class="appHeader bg-primary text-light">
            <div class="left">
                <a href="javascript:history.back()" class="headerButton goBack">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
            </div>
            <div class="pageTitle">Ubah Password</div>
            <div class="right"></div>
        </div>
    </div>
    <div id="content-section">
        <div class="row" style="margin-top: 10px; padding-bottom:80px">
            <div class="col pl-3 pr-3">
                <form action="{{ route('users.updatepassword', Crypt::encrypt($user->id)) }}" method="POST" id="formPassword" autocomplete="off">
                    @csrf
                    @method('PUT')

                    {{-- Username --}}
                    <div class="form-label-group">
                        <ion-icon name="at-outline" class="input-icon"></ion-icon>
                        <input type="text" name="username" id="username" class="form-control" placeholder=" " value="{{ $user->username }}" required>
                        <label for="username">Username</label>
                    </div>
                    @error('username')
                        <div class="text-danger small" style="margin-top: -5px; margin-bottom: 10px; margin-left: 5px;">
                            {{ $message }}
                        </div>
                    @enderror

                    {{-- Password Baru --}}
                    <div class="form-label-group mt-2">
                        <ion-icon name="lock-closed-outline" class="input-icon"></ion-icon>
                        <input type="password" name="passwordbaru" id="passwordbaru" class="form-control" placeholder=" " required>
                        <label for="passwordbaru">Password Baru</label>
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="form-label-group mt-2">
                        <ion-icon name="lock-closed-outline" class="input-icon"></ion-icon>
                        <input type="password" name="konfirmasipassword" id="konfirmasipassword" class="form-control" placeholder=" " required>
                        <label for="konfirmasipassword">Konfirmasi Password</label>
                    </div>

                    {{-- Show Password Checkbox --}}
                    <div class="form-group mt-2">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="show-password" onclick="tooglePassword()">
                            <label class="custom-control-label" for="show-password" style="font-size: 14px; color: #555;">Tampilkan Password</label>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="form-group mt-3">
                        <button class="btn btn-primary w-100" id="btnSimpan" style="height: 50px; border-radius: 9px;">
                            <i class="ti ti-send me-1"></i> Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('myscript')
    <script>
        function tooglePassword() {
            var x = document.getElementById("passwordbaru");
            var y = document.getElementById("konfirmasipassword");
            if (x.type === "password") {
                x.type = "text";
                y.type = "text";
            } else {
                x.type = "password";
                y.type = "password";
            }
        }
        
        $("#formPassword").submit(function() {
             var passwordbaru = $("#passwordbaru").val();
             var konfirmasipassword = $("#konfirmasipassword").val();
             
             if(passwordbaru == "") {
                 Swal.fire({title: "Oops!", text: 'Password Baru Harus Diisi !', icon: "warning"});
                 return false;
             }
             if(konfirmasipassword == "") {
                 Swal.fire({title: "Oops!", text: 'Konfirmasi Password Harus Diisi !', icon: "warning"});
                 return false;
             }
             if (passwordbaru != konfirmasipassword) {
                 Swal.fire({title: "Oops!", text: 'Password Tidak Sama !', icon: "warning"});
                 return false;
             }

             $("#btnSimpan").prop('disabled', true);
             $("#btnSimpan").html(`<div class="spinner-border spinner-border-sm text-white me-2" role="status"></div> Loading..`);
        });
    </script>
@endpush
