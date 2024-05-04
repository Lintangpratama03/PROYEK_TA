@extends('admin.layout.main')
@section('title', 'Kelola OPD - Smart Dashboard')
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="page-title">Organisasi Perangkat Daerah</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Retribusi</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">OPD</a></li>
                        <li class="breadcrumb-item active">Data</li>
                    </ol>
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
                            <button class="btn btn-success btn-sm" type="button" onclick="create()"><i class="fa fa-plus"
                                    aria-hidden="true"></i> Tambah OPD</button>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tbUser"
                                class="table table-hover table-responsive dataTable table-bordered w-full">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Username</th>
                                        <th>Status</th>
                                        <th>Group</th>
                                        <th>Retribusi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-slide-from-bottom" id="modalUser" aria-hidden="true" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="title"></h4>
                </div>

                <div class="modal-body " data-keyboard="false" data-backdrop="static">
                    <div class="detail">
                        <div class="row">
                            <div class="col-md-6 border-right">
                                <div class="form-group row">

                                    <div class="col-md-12">
                                        <label class="form-control-label mb-0 font-weight-bold" for="group_id">User Group :
                                        </label>
                                        <span class="detail-data" id="user_group"></span>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-control-label mb-0 font-weight-bold" for="group_id">Nama
                                            :</label>
                                        <span class="detail-data" id="nama"></span>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-control-label mb-0 font-weight-bold" for="group_id">Username :
                                        </label>
                                        <span class="detail-data" id="username"></span>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-control-label mb-0 font-weight-bold" for="group_id">Keterangan :
                                        </label>
                                        <span class="detail-data" id="keterangan"></span>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-control-label mb-0 font-weight-bold" for="group_id">Alamat OPD :
                                        </label>
                                        <span class="detail-data" id="alamat_opd"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-input">
                        <form id="formUser" enctype="multipart/form-data">
                            @csrf
                            <input type="text" hidden class="form-control" value="" id="url_submit"
                                name="url_submit">
                            <input type="text" hidden class="form-control" value="" id="id_user" name="id_user">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="form-control-label mb-0 font-weight-bold" for="nama">Nama
                                                <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control empty" id="nama" name="nama"
                                                required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="form-control-label mb-0 font-weight-bold" for="username">Username
                                                <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control empty" id="username" name="username"
                                                required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="form-control-label mb-0 font-weight-bold" for="alamat">Alamat
                                                <span class="text-danger">*</span></label>
                                            <textarea class="form-control empty" id="alamat" name="alamat" required></textarea>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="form-control-label mb-0 font-weight-bold"
                                                for="keterangan">Keterangan
                                                <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control empty" id="keterangan"
                                                name="keterangan" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label class="form-control-label mb-0 font-weight-bold"
                                                for="password">Password
                                                <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control empty" id="password"
                                                name="password" required>
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <div class="row px-20 pt-10 pb-20">
                                <div class="col-md-12 text-right actionButton">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                        id="tutup">Tutup</button>
                                    <button type="submit" class="btn btn-primary" id="btnSave">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    {{--  --}}
    <script src="{{ asset("js/sweetalert2.all.min.js") }}"></script>
    <script type="text/javascript">
        $('#tutup').on('click', function() {
            let modal_id = modalId("main");
            $(modal_id).modal('hide');
        });

        function url(key) {
            let arr_url = {
                "datatables": "{{ route('retribusi.kelola-opd.datatables') }}",
                "store": "{{ route('retribusi.kelola-opd.store') }}",
                "show": "{{ route('retribusi.kelola-opd.show') }}",
                "edit": "{{ route('retribusi.kelola-opd.edit') }}",
                "update": "{{ route('retribusi.kelola-opd.update') }}",
                "banned": "{{ route('retribusi.kelola-opd.banned') }}",
                "unbanned": "{{ route('retribusi.kelola-opd.unbanned') }}",
            };

            return arr_url[key];
        }

        function formId(key) {
            let arr_form_id = {
                "main": "#formUser"
            };

            return arr_form_id[key];
        }

        function modalId(key) {
            let arr_modal_id = {
                "main": "#modalUser"
            };

            return arr_modal_id[key];
        }

        function tableId(key) {
            let arr_modal_id = {
                "main": "#tbUser"
            };

            return arr_modal_id[key];
        }

        function formWajibPajak() {

            $(formId("main")).on("submit", function(event) {
                event.preventDefault();
                // swal.showLoading();
                let form_id = formId("main");
                let modal_id = modalId("main");
                let url_submit = $(`${form_id} input[name=url_submit]`).val();

                let formData = new FormData(this);
                console.log(url_submit);
                $.ajax({
                    type: 'POST',
                    url: url_submit,
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        let status = data.response.status;
                        let message = data.response.message;

                        if (status == "success") {
                            this.reset();
                            $(modal_id).modal("hide")
                            loadDatatable()
                            toastr.success(message);
                        } else {
                            toastr.error(message);
                        }
                        // showSwal(status,message);
                        // location.reload();
                    },
                    error: function(data) {
                        alert('Terjadi Kesalahan Pada Server');
                    }
                });
            });
        }

        function loadDatatable() {
            // console.log("datatable diload");
            let table_id = tableId("main");
            let url_get_data = url("datatables");

            if ($.fn.DataTable.isDataTable(table_id)) {
                $(table_id).DataTable().destroy();
            }
            // console.log("cek");
            // $(table_id).DataTable();
            $(table_id).DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: url_get_data,
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}"
                    }
                },
                columns: [{
                        data: 'no'
                    },
                    {
                        data: 'nama'
                    },
                    {
                        data: 'username'
                    },
                    {
                        data: 'is_aktif'
                    },
                    {
                        data: 'nama_group'
                    },
                    {
                        data: 'retribusi'
                    },
                    {
                        data: 'action'
                    }
                ],
            });
        }

        function create() {
            // console.log("klik")
            $('#title').html('Form Tambah OPD');
            let form_id = formId("main");
            let modal_id = modalId("main");
            let url_submit = url("store");
            // console.log(url_submit);
            $(form_id)[0].reset();

            modalUser(true, true)

            //set url submit
            $(`${form_id} input[name=url_submit]`).val(url_submit);
        }

        function show(_this) {
            //console.log("masuk ke detail");
            $('#title').html('Detail OPD');
            let url_get_data = url("show");
            let modal_id = modalId("main");
            let id_user = $(_this).data("id_user");

            $.ajax({
                url: url_get_data,
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id_user": id_user
                },
                success: (data) => {
                    // console.log(data);
                    let result = data.result;
                    let id = result.id;
                    let user_group = result.nama_group;
                    let ket_opd = result.ket_opd;
                    let nama = result.nama;
                    let username = result.username;
                    let alamat_opd = result.alamat_opd;

                    // $('.detail #id_user').val(id);
                    $(".detail-data").empty()
                    $('.detail #user_group').append(user_group);
                    $('.detail #keterangan').append(ket_opd);
                    $('.detail #nama').append(nama);
                    $(".detail #username").append(username);
                    $(".detail #alamat_opd").append(alamat_opd);

                    modalUser(true, false);
                }
            })
        }

        function edit(_this) {
            $('#title').html('Form Edit OPD');

            let form_id = formId("main");
            let form_class = ".form-input";
            let modal_id = modalId("main");
            let url_get_data = url("edit");
            let url_submit = url("update");

            let id_user = $(_this).data("id_user");
            // set url submit
            $(`${form_id} input[name=url_submit]`).val(url_submit);

            $.ajax({
                url: url_get_data,
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id_user": id_user
                },
                success: (data) => {
                    console.log(data);
                    let result = data.result;

                    let id = result.id;
                    let username = result.username;
                    let nama_user = result.nama;
                    let alamat_opd = result.alamat_opd;
                    let ket_opd = result.ket_opd;

                    // console.log(lumbung_id + "  " + nama_lumbung)
                    $('.form-input #id_user').val(id);
                    $('.form-input #nama').val(nama_user);
                    $('.form-input #keterangan').val(ket_opd);
                    $('.form-input #username').val(username);
                    $('.form-input #alamat').val(alamat_opd);
                    $('.form-input #password').val('');

                    modalUser(true, true);
                }
            })
        }

        function banned(_this) {
            let id_user = $(_this).data("id_user");
            let url_delete_data = url("banned");

            Swal.fire({
                title: "Peringatan!",
                text: "Apakah anda yakin non-aktifkan akun ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "OK",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url_delete_data,
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id_user": id_user
                        },
                        success: (data) => {
                            let status = data.response.status;
                            let message = data.response.message;
                            if (status == "success") {
                                Swal.fire("Berhasil!", message, "success").then(() => {
                                    loadDatatable();
                                });
                            } else {
                                Swal.fire("Gagal!", message, "error");
                            }
                        }
                    });
                }
            });
        }

        function unbanned(_this) {
            let id_user = $(_this).data("id_user");
            let url_delete_data = url("unbanned");

            Swal.fire({
                title: "Peringatan!",
                text: "Apakah anda yakin aktifkan akun ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "OK",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url_delete_data,
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id_user": id_user
                        },
                        success: (data) => {
                            let status = data.response.status;
                            let message = data.response.message;
                            if (status == "success") {
                                Swal.fire("Berhasil!", message, "success").then(() => {
                                    loadDatatable();
                                });
                            } else {
                                Swal.fire("Gagal!", message, "error");
                            }
                        }
                    });
                }
            });
        }

        function modalUser(isShow, isActionButtonEnable) {
            // console.log("modal klik")
            let modal_id = modalId("main");
            if (isShow) {
                $(modal_id).modal("show")
            } else {
                $(modal_id).modal("hide")
            }

            if (isActionButtonEnable) {
                $(`${modal_id} .form-input`).show();
                $(`${modal_id} .detail`).hide();
            } else {
                $(`${modal_id} .detail`).show();
                $(`${modal_id} .form-input`).hide();
            }
        }

        $(document).ready(function() {
            loadDatatable();
            formWajibPajak();
            select2Group();
            select2WajibPajak();

        });

        function detail(_this) {
            let id_opd = $(_this).data("id_opd");
            let url = "{{ route('retribusi.kelola-opd.detail', ['id_opd' => ':id_opd']) }}";
            url = url.replace(':id_opd', id_opd);
            window.location.href = url;
        }
    </script>
@endsection
