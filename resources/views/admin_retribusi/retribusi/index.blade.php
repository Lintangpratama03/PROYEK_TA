@extends('admin.layout.main')
@section('title', 'Kelola Retribusi - Smart Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Kelola Retribusi</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Retribusi</a></li>
                        <li class="breadcrumb-item">Kelola Retribusi</li>
                        <li class="breadcrumb-item active">Data</li>
                    </ol>
                </div>
                <div class="col-sm-6">
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="">
                            <button type="button" class="btn btn-primary" id="btn_tambah"><i class='fa fa-plus'></i> Tambah
                                Retribusi</button>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table dtTable">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Retribusi</th>
                                        <th>Keterangan</th>
                                        <th>Kode Rekening</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="examplemodal" aria-hidden="true" aria-labelledby="examplemodal" role="dialog">
        <div class="modal-dialog modal-simple modal-top modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_title"></h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form_modal" autocomplete="off">
                    <input type="hidden" name="popup_id">
                    <div class="modal-body" id="modal_body">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Nama Retribusi</label>
                            <div class="col-lg-9">
                                <input type="text" name="popup_nama" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Kode Rekening</label>
                            <div class="col-lg-9">
                                <input type="text" name="popup_kode_rekening" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3">Jenis Retribusi</label>
                            <div class="col-lg-9">
                                <select name="popup_keterangan" id="jenis" class="form-control btn-square">
                                    <!-- <option value="">-- Filter Rekening --</option> -->
                                    @foreach (getJenisRetribusi() as $item)
                                        <option value="{{ $item->nama }}">{{ $item->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" id="modal_footer">
                        <button type="button" class="btn btn-primary" id="btn_simpan">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script type="text/javascript">
        $('#btn-close-modal').on('click', function() {
            $('#examplemodal').modal('hide');
        });
        var table;
        $(document).ready(function() {


            table = $(".dtTable").DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 2000,
                ajax: "{{ route('retribusi.kelola-retribusi.get_data') }}",
                columns: [{
                        data: 'nama',
                        name: 'nama',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan'
                    },
                    {
                        data: 'kode_rekening',
                        name: 'kode_rekening'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    }
                ],
            });
        })

        $("#btn_tambah").click(function() {
            clear_input();
            $("#modal_title").text("Retribusi");
            $("#examplemodal").modal('show');
        })

        function clear_input() {
            $("[name=popup_id]").val('');
            $("[name=popup_nama]").val('');
            $("[name=popup_keterangan]").val('');
            $("[name=popup_kode_rekening]").val('');
        }

        $("#btn_simpan").click(function() {
            var id = $("[name=popup_id]").val();
            var nama = $("[name=popup_nama]").val();
            var kode_rekening = $("[name=popup_kode_rekening]").val();

            if (nama != '') {
                $.ajax({
                    url: "{{ route('retribusi.kelola-retribusi.simpan') }}",
                    type: "POST",
                    dataType: "json",
                    data: $("#form_modal").serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(respon) {
                        table.ajax.reload();
                        $("#examplemodal").modal('hide');
                    }
                })
            } else {

            }

        })

        function edit(this_) {
            var data_id = $(this_).data("id");

            var id = $("#table_id" + data_id).val();
            var name = $("#table_nama" + data_id).val();
            var keterangan = $("#table_keterangan" + data_id).val();
            var kode_rekening = $("#table_kode_rekening" + data_id).val();

            $("[name=popup_id]").val(id);
            $("[name=popup_nama]").val(name);
            $("[name=popup_keterangan]").val(keterangan);
            $("[name=popup_kode_rekening]").val(kode_rekening);

            $("#examplemodal").modal("show");
        }

        function hapus(this_) {
            var id = $(this_).data("id");
            swal({
                    title: "Peringatan Hapus Data",
                    text: "Anda yakin akan menghapus data ini ?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {

                        $.ajax({
                            url: "{{ route('retribusi.kelola-retribusi.hapus') }} ",
                            type: 'post',
                            dataType: 'json',
                            data: {
                                id: id
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(respon) {
                                console.log(respon.status);

                                if (respon.status == 1) {
                                    swal("Data Berhasil Dihapus", {
                                        icon: "success",
                                    });
                                    table.ajax.reload();
                                } else {
                                    swal("Data Gagal Dihapus", {
                                        icon: "error",
                                    });
                                }
                            }
                        })

                    } else {
                        swal("Hapus data dibatalkan");
                    }
                })
        }


        function trigger(value) {
            $(value).html("<option value=''> -- Pilih -- </option>");
            $(value).val("").trigger("change");
        }
    </script>
@endsection
