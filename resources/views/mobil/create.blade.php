@extends('layouts.main')

@section('title')
    Create Mobil
@endsection

@section('content')
    <div class="row">
        <div class="col-10">
            <h5>Tambah Mobil</h5>
            <form action="{{ route('mobil.store') }}" method="post" class="mt-5" id="custom_form" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    {{-- <div class="col-6">
                        <div class="form-group">
                            <label for="jenis_rekening">Jenis Rekening</label>
                            <input type="text" name="jenis_rekening" id="jenis_rekening" value="{{ old('jenis_rekening') }}" class="form-control">
                        </div>
                    </div> --}}
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="merek">Merek</label>
                            <input type="text" name="merek" id="merek" value="{{ old('merek') }}" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="model">Model</label>
                            <input type="text" name="model" id="model" value="{{ old('model') }}" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="nomor_plat">Nomor Plat</label>
                            <input type="text" name="nomor_plat" id="nomor_plat" value="{{ old('nomor_plat') }}" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="tarif_sewa">Tarif Sewa</label>
                            <input type="number" name="tarif_sewa" id="tarif_sewa" value="{{ old('tarif_sewa') }}" class="form-control">
                        </div>
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="stok">Stok</label>
                            <input type="number" name="stok" id="stok" value="{{ old('stok') }}" class="form-control">
                        </div>
                    </div>
                </div> --}}
                <button type="submit" id="btn_submit" class="btn btn-primary px-4 mt-2">Submit</button>
            </form>
        </div>
    </div>
@endsection

@push('page_js')
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
