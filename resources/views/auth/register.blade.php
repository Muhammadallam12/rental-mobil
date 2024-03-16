@extends('layouts.auth')

@section('title')
    Register
@endsection

@section('content')
    <div style="margin: 30px 130px 0px 130px;">
        <h2 class="font-weight-bold text-center">Pendaftaran Akun Member</h2>
        <form class="mt-5" action="{{ route("register.store") }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-6 mb-1">
                    <label for="nama" class="form-label">Name</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}" style="border-width: 2px;">
                    @error('nama')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-lg-6 mb-1">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" style="border-width: 2px;">
                    @error('username')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
    
            <div class="row">
                <div class="col-lg-6 mb-1">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" class="form-control" id="password" value="{{ old('password') }}" style="border-width: 2px;">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="bi bi-eye-slash" id="togglePassword"></i>
                            </span>
                        </div>
                    </div>
                    @error('password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-lg-6 mb-1">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" name="email" value="{{ old('email') }}" class="form-control" id="email" style="border-width: 2px;">
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
    
            <div class="row">
                <div class="col-lg-6 mb-1">
                    <label for="no_hp" class="form-label">No Hp</label>
                    <input type="number" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" style="border-width: 2px;">
                    @error('no_hp')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-lg-6 mb-1">
                    <label for="tanggal_lahir" class="form-label">Tanggal lahir</label>
                    <input type="date" class="form-control" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" id="tanggal_lahir" style="border-width: 2px;">
                    @error('tanggal_lahir')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 mb-1">
                    <label for="jenis_kelamin" class="form-label">Jenis kelamin</label>
                    <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" value="{{ old('jenis_kelamin') }}" style="border-width: 2px;">
                        <option value=""></option>
                        <option value="laki-laki">Laki-laki</option>
                        <option value="perempuan">Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-lg-6 mb-1">
                    <label for="no_ktp" class="form-label">No KTP</label>
                    <input type="number" class="form-control" id="no_ktp" name="no_ktp" value="{{ old('no_ktp') }}" style="border-width: 2px;">
                    @error('no_ktp')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 mb-1">
                    <label for="profilePicture" class="form-label">Foto profil</label>
                    <input type="file" name="foto" class="form-control" id="profilePicture" accept="image/*" style="border-width: 2px;">
                    @error('foto')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-lg-6 mb-1">
                    <img src="{{ asset('assets/img/empty-image.png') }}" alt="Preview" id="imagePreview" class="mt-2" style="max-width: 150px; max-height: 150px;">
                </div>
            </div>
    
            <div class="d-flex justify-content-start mt-4">
                <button type="submit" class="btn btn-primary w-50">Submit</button>
            </div>
        </form>
    </div>
@endsection

@push('page_js')
    <script>
        $(document).ready(function () {
            const defaultImage = $('#imagePreview');
            defaultImage.css({
                'max-width': '150px',
                'max-height': '150px'
            });

            $('#profilePicture').change(function (e) {
                const fileInput = e.target;
                const previewImage = $('#imagePreview');

                if (fileInput.files && fileInput.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        previewImage.attr('src', e.target.result);
                        previewImage.css({
                            'max-width': '150px',
                            'max-height': '150px'
                        });
                    };

                    reader.readAsDataURL(fileInput.files[0]);
                }
            });

            // Memeriksa apakah ada kesalahan pada elemen 'foto'
            const fotoError = '{{ $errors->first('foto') }}';
            if (fotoError) {
                // Jika ada kesalahan, atur kembali pratinjau gambar ke gambar default
                $('#imagePreview').attr('src', '{{ asset('assets/img/empty-image.png') }}');
            }
        });
    </script>
    
    <script>
        $(document).ready(function(){
            $('#togglePassword').click(function(){
                var passwordField = $('#password');
                var icon = $('#togglePassword');

                if (passwordField.attr('type') === 'password') {
                    passwordField.attr('type', 'text');
                    icon.removeClass('bi-eye-slash').addClass('bi-eye');
                } else {
                    passwordField.attr('type', 'password');
                    icon.removeClass('bi-eye').addClass('bi-eye-slash');
                }
            });
        });
    </script>
@endpush        