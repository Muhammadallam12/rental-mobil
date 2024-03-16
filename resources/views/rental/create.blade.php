@extends('layouts.main')

@section('title')
    Create Target
@endsection

@section('content')
    <div class="row">
        <div class="col-10">
            <h5>Buat Target</h5>
            <input type="text" class="" id="allRekening" value="{{$mobil}}" hidden>
            <form action="{{ route('rental.create') }}" method="post" id="custom_form" class="mt-5" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="id_rekening">Merek</label>
                            <select class="form-control" id="id_rekening" name="id_rekening">
                                <option value="" disabled selected>Pilih Jenis Rekening</option>
                                @foreach ($mobil as $mobil)
                                    <option value="{{ $mobil->id }}">{{ $mobil->merek }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="model">Model</label>
                            <input type="text" id="model" disabled class="form-control" value="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="no_plat">Nomor Plat</label>
                            <input type="text" id="no_plat" disabled class="form-control" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="tanggal_mulai">Tanggal Sewa</label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="tanggal_mulai">Tanggal Sewa</label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="jumlah_target">Target (Rp)</label>
                            <input type="number" name="jumlah_target" id="jumlah_target" class="form-control">
                        </div>
                    </div>
                </div>
                <button type="submit" id="btn_submit" class="btn btn-primary px-4 mt-2">Submit</button>
            </form>
        </div>
    </div>
@endsection

@push('page_js')
<script>

    $(document).on("change", "#id_rekening", function() {
        var id_rekening = $(this).val();
        var allRekening = JSON.parse($("#allRekening").val());

        var filteredData = allRekening.filter(function(item) {
            return item.id_rekening == id_rekening;
        });

        $("#sub_rekening").val(filteredData[0].sub_rekening);
        $("#nama_rekening").val(filteredData[0].nama_rekening);
    });

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
