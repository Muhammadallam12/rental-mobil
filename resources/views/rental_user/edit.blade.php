@extends('layouts.main')

@section('title')
    Create Target
@endsection

@section('content')
    <div class="row">
        <div class="col-10">
            <h5></h5>
            <input type="text" class="" id="allMobil" value="{{$mobil}}" hidden>
            <form action="{{route('rental.update', ['rental' => $rental->id])}}" id="custom_form" class="mt-5" enctype="multipart/form-data">
                @method('PATCH')
                @csrf
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="tanggal_mulai">Tanggal Sewa</label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ $mobil->tanggal_mulai }}" class="form-control" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="tanggal_selesai">Tanggal Pengembalian</label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ $mobil->tanggal_selesai }}" class="form-control" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="id">Merek</label>
                            <select class="form-control" id="id" name="id">
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
                            <label for="nomor_plat">Nomor Plat</label>
                            <input type="text" id="nomor_plat" disabled class="form-control" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="tarif_sewa">Harga</label>
                            <input type="text" id="tarif_sewa" disabled class="form-control" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="total_harga">Total Harga</label>
                            <input type="text" name="total_harga" id="total_harga" value="{{ old('total_harga') }}" disabled class="form-control">
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

    $(document).on("change", "#id", function() {
        var id = $(this).val();
        var allMobil = JSON.parse($("#allMobil").val());

        var filteredData = allMobil.filter(function(item) {
            return item.id == id;
        });

        $("#model").val(filteredData[0].model);
        $("#nomor_plat").val(filteredData[0].nomor_plat);
        $("#tarif_sewa").val(filteredData[0].tarif_sewa);
        console.log(nomor_plat);
    });

    $(document).on('change', '#tanggal_mulai, #tanggal_selesai', function() {
    var tanggalMulai = new Date($('#tanggal_mulai').val());
    var tanggalSelesai = new Date($('#tanggal_selesai').val());

    // Hitung selisih hari antara tanggal selesai dan tanggal mulai
    var jarakHari = Math.ceil((tanggalSelesai - tanggalMulai) / (1000 * 60 * 60 * 24));

    // Jika hasilnya negatif, atur jarakHari menjadi 0
    jarakHari = jarakHari < 0 ? 0 : jarakHari;

    console.log('Jumlah hari: ', jarakHari); // Tambahkan console.log untuk menampilkan hasil perhitungan

    // Ambil tarif_sewa dari input tersembunyi
    var tarifSewa = parseFloat($("#tarif_sewa").val());
    console.log(tarif_sewa)

    // Hitung total harga berdasarkan jarak hari dan tarif sewa
    var totalHarga = jarakHari * tarifSewa;

    // Update nilai total harga dengan hasil perhitungan
    $('#total_harga').val(totalHarga); // Atau sesuaikan dengan logika Anda untuk menetapkan harga berdasarkan jarak hari
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
