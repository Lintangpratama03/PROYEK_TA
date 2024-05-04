@extends('admin.layout.main')
@section('title', 'Cari Objek Pajak - Smart Dashboard')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>Cari Objek Pajak</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">PDL</a></li>
                    <li class="breadcrumb-item"><a href="#">OP</a></li>
                    <li class="breadcrumb-item active">Cari Objek Pajak</li>
                </ol>
            </div>
            <div class="col-sm-6">
            </div>

        </div>
    </div>
</div>
<!-- Container-fluid starts-->
<div class="container-fluid search-page">

    <div class="row">
        <div class="col-xl-12">
            <div class="mb-3 draggable">
                <div class="input-group">
                    <div class="col-xl-5">
                        <select id="kategori" name="kategori" class="form-control btn-square col-sm-12" placeholder="Pilih Kategori" disabled>
                            <!-- <optgroup label="Kategori Pencarian"> -->
                            <option value="NOP">Cari Berdasarkan NOP</option>
                            <!-- <option value="NPWPD">NPWPD</option>
                            <option value="NAMAOP">NAMA OP</option>
                            <option value="NAMAWP">NAMA WP</option> -->
                            <!-- </optgroup> -->
                        </select>
                    </div>
                    <div class="col-xl-5">
                        <div class="input-group">
                            <input class="form-control" id="search" name="search" type="text" placeholder="Input text Pencarian" aria-label="Input text Pencarian"><span class="input-group-text"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                    <div class="input-group-btn btn btn-square p-0">
                        <a class="btn btn-primary btn-square" id="btn_search" type="button" onclick="">Cari<span class="caret"></span></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>PROFIL DAN HISTORY BAYAR</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-6 col-sm-12">
                            <form>
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="inputAddress5">NPWPD</label>
                                        <input class="form-control" readonly id="npwpd" type="text">
                                    </div>
                                    <div class="mb-3">
                                        <label for="inputAddress5">NOP</label>
                                        <input class="form-control" readonly id="nop" type="text">
                                    </div>
                                    <div class="mb-3">
                                        <label for="inputAddress5">Nama Wajib Pajak</label>
                                        <input class="form-control" readonly id="nama_wp" type="text">
                                    </div>
                                    <div class="mb-3 col-sm-6">
                                        <label for="inputEmail4">Kecamatan WP</label>
                                        <input class="form-control" readonly id="kecamatan_wp" type="text">
                                    </div>
                                    <div class="mb-3 col-sm-6">
                                        <label for="inputPassword4">Kelurahan WP</label>
                                        <input class="form-control" readonly id="kelurahan_wp" type="text">
                                    </div>
                                    <div class="mb-3">
                                        <label for="inputCity">Alamat WP</label>
                                        <input class="form-control" readonly id="alamat_wp" type="text">
                                    </div>
                                    <div class="mb-3">
                                        <label for="inputAddress5">Nama Objek Pajak</label>
                                        <input class="form-control" readonly id="nama_op" type="text">
                                    </div>
                                    <div class="mb-3 col-sm-6">
                                        <label for="inputEmail4">Kecamatan OP</label>
                                        <input class="form-control" readonly id="kecamatan_op" type="text">
                                    </div>
                                    <div class="mb-3 col-sm-6">
                                        <label for="inputPassword4">Kelurahan OP</label>
                                        <input class="form-control" readonly id="kelurahan_op" type="text">
                                    </div>
                                    <div class="mb-3">
                                        <label for="inputCity">Alamat OP</label>
                                        <input class="form-control" readonly id="alamat_op" type="text">
                                    </div>
                                    <div class="mb-3">
                                        <label for="inputCity">Jenis Pajak</label>
                                        <input class="form-control" readonly id="jenis_pajak" type="text">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-xl-6 col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-history">
                                    <thead>
                                        <tr>
                                            <th>Tahun Pajak</th>
                                            <th>Masa Pajak</th>
                                            <th>Nominal Pajak </th>
                                            <th>Status Bayar</th>
                                        </tr>
                                    </thead>
                                    <tbody id="list_bayar">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- Container-fluid Ends-->
@endsection

@section('js')
<script>
    function formatRupiah(angka) {
        var options = {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 2,
        };
        var formattedNumber = angka.toLocaleString('ID', options);
        return formattedNumber;
    }




    $(document).ready(function() {
        $("#kategori").select2({
            placeholder: "Pencarian Bedasarkan..."
        });

        $(".table-history").DataTable();

        $("#btn_search").click(function() {
            var kategori = $("[name=kategori]").val();
            var search = $("[name=search]").val();

            if (search != '') {
                $.ajax({
                    url: "{{ route('pdl.op.query_search') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        "kategori": kategori,
                        "search": search,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(respon) {
                        console.log(respon)
                        $("#npwpd").val(respon.profil.npwpd)
                        $("#nop").val(respon.profil.nop)
                        $("#nama_wp").val(respon.profil.nama_wp)
                        $("#alamat_wp").val(respon.profil.alamat_wp)
                        $("#kecamatan_wp").val(respon.profil.kecamatan_wp)
                        $("#kelurahan_wp").val(respon.profil.kelurahan_wp)
                        $("#nama_op").val(respon.profil.nama_op)
                        $("#alamat_op").val(respon.profil.alamat_op)
                        $("#kecamatan_op").val(respon.profil.kecamatan_op)
                        $("#kelurahan_op").val(respon.profil.kelurahan_op)
                        $("#jenis_pajak").val(respon.profil.jenis_pajak)

                        let arrHistory = respon.history
                        // Get the table body
                        var tableBody = $('#list_bayar');
                        // Loop through the array and append rows to the table
                        tableBody.empty();
                        $.each(arrHistory, function(index, data) {
                            var row = $('<tr>');
                            row.append('<td>' + data.tahun + '</td>');
                            row.append('<td>' + data.masa_pajak + '</td>');
                            row.append('<td>' + data.pajak_terhutang + '</td>');
                            row.append('<td>' + data.status_pembayaran + '</td>');
                            tableBody.append(row);
                        });
                    }
                })
            } else {

            }
        })

    })
</script>
@endsection