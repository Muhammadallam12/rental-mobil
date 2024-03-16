@extends('layouts.auth')

@section('title')
    Login
@endsection

@section('content')
    <div style="margin: 100px 130px 50px 130px;">
        <div class="d-flex justify-content-center align-items-center">
            <img class="" src="{{asset('assets/img/cat-logo.png')}}" alt="Logo" style="width:150px; height:auto;">
        </div>
        <h2 class="font-weight-bold mt-3">Selamat Datang</h2>
        <p class="mt-2">Silahkan login</p>
        {{-- @if(session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
        @endif --}}
        <form action="{{ route('login.store') }}" class="d-flex flex-column justify-content-center" id="custom_form" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="text" class="form-control" id="email" name="email" value="{{ old('email') }}" style="border-width: 2px;">
                {{-- @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror --}}
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" value="{{ old('password') }}" style="border-width: 2px;">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="bi bi-eye-slash" id="togglePassword"></i>
                        </span>
                    </div>
                </div>
                {{-- @error('password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror --}}
                {{-- <div class="mt-2">
                    <a href="#" class="text-small">Forgot Password?</a>
                </div> --}}
            </div>

            <div class="d-flex justify-content-center mt-3">
                <button type="submit" id="btn_submit" class="btn btn-primary w-50">Login</button>
            </div>

            {{-- <div class="mt-3 text-center">
                <span>Don't have an account? <a href="{{ route('register.index') }}" class="text-small">Register</a></span>
            </div> --}}
        </form>
    </div>
@endsection

@push('page_js')
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



    <script>
        $(document).on('click', '#btn_submit', function(e) {
            e.preventDefault();
            customFormSubmit();
        });

        function customFormSubmit() {
            $("#btn_submit").prop("disabled", true);

            let myForm = document.getElementById('custom_form');
            let formData = new FormData(myForm);

            const form = $(myForm);
            $.ajax({
                type: "POST",
                url: $('#custom_form').attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                enctype: 'multipart/form-data',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (result) {
                    if (result.success) {
                        Swal.fire(result.message, '', 'success').then((res) => {
                            if (result.redirect) {
                                window.location.replace(result.redirect);
                            }
                        });
                    } else {
                        form.find('input, select, textarea').removeClass('is-invalid');
                        form.find('.invalid-feedback').remove();
                        Swal.fire(result.message, '', 'error');
                    }

                    // showLoading(false);
                },
                error: function (xhr, err, thrownError) {
                    var errorsArray = [];

                    $(".invalid-feedback-modal").remove();

                    var data = xhr.responseJSON;
                    $.each(data.errors, function (key, v) {
                        form.find('input[name="' + key + '"]')
                            .addClass('is-invalid')
                            .after(`<div class="invalid-feedback invalid-feedback-modal float-start">` + v[0] + `</div>`);
                        form.find('select[name="' + key + '"]')
                            .addClass('is-invalid')
                            .after(`<div class="invalid-feedback invalid-feedback-modal float-start">` + v[0] + `</div>`);
                        form.find('textarea[name="' + key + '"]')
                            .addClass('is-invalid')
                            .after(`<div class="invalid-feedback invalid-feedback-modal float-start">` + v[0] + `</div>`);

                        var errorObj = {
                            key: key,
                            text: v[0]
                        };
                        errorsArray.push(errorObj);
                    });

                    if (errorsArray.length > 0) {
                        var error_html = '';
                        $.each(errorsArray, function(index, value) {
                            error_html += `
                                <li class="text-start">` + value.text + `</li>
                            `;
                        });

                        Swal.fire({
                            title: '<strong>There is something wrong</strong>',
                            icon: 'warning',
                            html: `
                                <ul class="mb-0">
                                    ` + error_html + `
                                </ul>
                            `,
                            showCloseButton: true,
                        });
                    }

                    // showLoading(false);
                }
            });

            $("#btn_submit").prop("disabled", false);
        }
        </script>
@endpush
