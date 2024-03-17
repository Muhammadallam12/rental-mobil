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
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="name" name="name" style="border-width: 2px;">
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-lg-6 mb-1">
                    <label for="alamat" class="form-label">Alamat</label>
                    <input type="text" class="form-control" id="alamat" name="alamat" value="{{ old('alamat') }}" style="border-width: 2px;">
                    @error('alamat')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 mb-1">
                    <label for="no_telp" class="form-label">Nomor Telephone</label>
                    <input type="text" class="form-control" id="no_telp" name="no_telp" value="{{ old('no_telp') }}" style="border-width: 2px;">
                    @error('no_telp')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-lg-6 mb-1">
                    <label for="no_sim" class="form-label">Nomor SIM</label>
                    <input type="text" class="form-control" id="no_sim" name="no_sim" value="{{ old('no_sim') }}" style="border-width: 2px;">
                    @error('no_sim')
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
