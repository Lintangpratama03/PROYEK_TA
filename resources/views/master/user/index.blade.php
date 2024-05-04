@extends('admin.layout.main')

@section('content')
<div class="container-fluid">
	<div class="page-header">
		<div class="row">
			<div class="col-sm-6">
				<h3 class="page-title">User (Pengguna)</h3>
				<ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Master</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0)">User (Pengguna)</a></li>
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
						<button class="btn btn-success btn-sm" type="button" onclick="create()"><i class="fa fa-plus" aria-hidden="true"></i> Tambah User</button>
					</h3>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table id="tbUser" class="table table-hover table-responsive dataTable table-bordered w-full">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Status</th>
                                    <th>Group</th>
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
                <button type="button" class="btn btn-sm btn-danger close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body " data-keyboard="false" data-backdrop="static">
                <div class="detail">
                    <div class="row">
                        <div class="col-md-6 border-right">
                            <div class="form-group row" >

                                <div class="col-md-12">
                                    <label class="form-control-label mb-0 font-weight-bold" for="group_id">User Group : </label>
                                    <span class="detail-data" id="user_group"></span>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-control-label mb-0 font-weight-bold" for="group_id">Nama :</label>
                                    <span class="detail-data" id="nama"></span>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-control-label mb-0 font-weight-bold" for="group_id">Username : </label>
                                    <span class="detail-data" id="username"></span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-input">
                    <form id="formUser" enctype="multipart/form-data">
                        @csrf
                        <input type="text" hidden class="form-control" value="" id="url_submit" name="url_submit">
                        <input type="text" hidden class="form-control" value="" id="id_user" name="id_user">
                        <div class="row">
                            <div class="col-md-6 border-right">
                                <div class="form-group row" >
                                    <div class="col-md-12">
                                        <label class="form-control-label mb-0 font-weight-bold" for="group_id">User Group <span class="text-danger">*</span></label>
                                        <select class="form-control empty"  id="group_id" name="group_id" required></select>
                                    </div>
                                </div>
    
                                <div class="form-group row" >
                                    <div class="col-md-12">
                                        <label class="form-control-label mb-0 font-weight-bold" for="nama">Nama <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control empty"  id="nama" name="nama" required>
                                    </div>
                                </div>
    
                                <div class="form-group row" >
                                    <div class="col-md-12">
                                        <label class="form-control-label mb-0 font-weight-bold" for="username">Username <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control empty"  id="username" name="username" required>
                                    </div>
                                </div>
    
                                <div class="form-group row" >
                                    <div class="col-md-12">
                                        <label class="form-control-label mb-0 font-weight-bold" for="password">Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control empty"  id="password" name="password" required>
                                    </div>
                                </div>
    
                            </div>
                            
                        </div>
                        
                        <div class="row px-20 pt-10 pb-20">
                            <div class="col-md-12 text-right actionButton">
                                <button type="button" class="btn btn-secondary close" data-dismiss="modal" id="tutup">Tutup</button>
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
<script type="text/javascript">
    
    function url(key) {
        let arr_url = {
            "datatables" : "{{route('master.user.datatables')}}",
            "store" : "{{route('master.user.store')}}",
            "show" : "{{route('master.user.show')}}",
            "edit" : "{{route('master.user.edit')}}",
            "update" : "{{route('master.user.update')}}",
            "destroy" : "{{route('master.user.destroy')}}",
            "banned" : "{{route('master.user.banned')}}",
            "unbanned" : "{{route('master.user.unbanned')}}",
            "impersonate" : "{{route('login.impersonate')}}",

            //select2
            "select2_group" : "{{route('select2group')}}",
            "select2_wp" : "",
            
        }; 

        return arr_url[key];
    }

    function formId(key) {
        let arr_form_id = {
            "main" : "#formUser"
        }; 

        return arr_form_id[key];
    }

    function modalId(key) {
        let arr_modal_id = {
            "main" : "#modalUser"
        }; 

        return arr_modal_id[key];
    }
    
    function tableId(key) {
        let arr_modal_id = {
            "main" : "#tbUser"
        }; 

        return arr_modal_id[key];
    }

    function formWajibPajak() {
        
        $(formId("main")).on("submit", function (event) {
            event.preventDefault();
            // swal.showLoading();
            let form_id = formId("main");
            let modal_id = modalId("main");
            let url_submit = $(`${form_id} input[name=url_submit]`).val();
            
            let formData = new FormData(this);
            console.log(url_submit);
            $.ajax({
                type:'POST',
                url: url_submit,
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: (data) => {
                    let status = data.response.status;  
                    let message = data.response.message;  
                    
                    if (status=="success") {
                        this.reset();
                        $(modal_id).modal("hide")
                        loadDatatable()
                        toastr.success(message);
                    }else{
                        toastr.error(message);
                    }
                    // showSwal(status,message);
                    // location.reload();
                },
                error: function(data){
                    alert('Terjadi Kesalahan Pada Server');
                }
            });
        });
    }

    function loadDatatable(){
        // console.log("datatable diload");
        let table_id = tableId("main");
        let url_get_data = url("datatables");

        if ($.fn.DataTable.isDataTable( table_id ) ) {
            $(table_id).DataTable().destroy();
        }
        // console.log("cek");
        // $(table_id).DataTable();
        $(table_id).DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url : url_get_data,
                type: 'POST',
                data: 
                    {
                        "_token": "{{csrf_token()}}"
                    }
            },
            columns: [
                { data: 'no' },
                { data: 'nama' },
                { data: 'username' },
                { data: 'is_aktif' },
                { data: 'nama_group'},
                { data: 'action' }
            ],
        });
    }

    function create(){
        // console.log("klik")
        $('#title').html('Form Tambah User (Pengguna)');
        let form_id = formId("main");
        let modal_id = modalId("main");
        let url_submit = url("store");
        // console.log(url_submit);
        $(form_id)[0].reset();

        modalUser(true,true)

        //set url submit
        $(`${form_id} input[name=url_submit]`).val(url_submit);
    }

    function show(_this){
        // console.log("masuk ke detail");
        $('#title').html('Detail User (Pengguna)');
        let url_get_data = url("show");
        let modal_id = modalId("main");
        let id_user = $(_this).data("id_user");

        $.ajax({
            url: url_get_data,
            type:"POST",
            data : {
                "_token" : "{{csrf_token()}}",
                "id_user" : id_user
            },
            success: (data) => {
                // console.log(data);
                let result = data.result;
                let id = result.id;
                let user_group = result.nama_group;
                let npwp = result.npwpd;
                let nama = result.nama;
                let username = result.username;

                // $('.detail #id_user').val(id);
                $(".detail-data").empty()
                $('.detail #user_group').append(user_group);
                $('.detail #npwp').append(npwp);
                $('.detail #nama').append(nama);
                $(".detail #username").append(username);
                
                modalUser(true,false);
            }
        })
    }

    function edit(_this){
        $('#title').html('Form Edit User (Pengguna)');
        
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
            type:"POST",
            data : {
                "_token" : "{{csrf_token()}}",
                "id_user" : id_user
            },
            success: (data) => {
                // console.log(data);
                let result = data.result;

                let id = result.id;
                let id_group = result.id_group;
                let nama_group = result.nama_group;
                let npwp = result.npwp;
                let username = result.username;
                let nama_user = result.nama;

                // console.log(lumbung_id + "  " + nama_lumbung)
                $('.form-input #id_user').val(id);
                $('.form-input #npwp').val(npwp);
                $('.form-input #nama').val(nama_user);
                $('.form-input #username').val(username);
                $('.form-input #password').val('');

                $(".form-input #group_id").select2("trigger", "select", {data: { id:id_group, text:nama_group }});
                

                modalUser(true,true);
            }
        })
    }  

    function destroy_akun(_this){
        let id_user = $(_this).data("id_user");
        let url_delete_data = url("destroy");

        swal({
            title: "Peringatan!",
            text: "Apakah anda yakin menghapus data ini?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: 'OK',
            closeOnConfirm: false
        }, function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: url_delete_data,
                    type:"POST",
                    data : {
                        "_token" : "{{csrf_token()}}",
                        "id_user" : id_user
                    },
                    success: (data) => {
                        let status = data.response.status;  
                        let message = data.response.message;  
                        // showSwal(status,message);
                        // loadDatatable()
                        if (status=="success") {
                            swal.close();
                            loadDatatable()
                            toastr.success(message);
                        }else{
                            swal.close();
                            toastr.error(message);
                        }
                    }
                })
            }
        });
    }

    function banned(_this){
        let id_user = $(_this).data("id_user");
        let url_delete_data = url("banned");

        swal({
            title: "Peringatan!",
            text: "Apakah anda yakin non-aktifkan akun ini?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: 'OK',
            closeOnConfirm: false
        }, function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: url_delete_data,
                    type:"POST",
                    data : {
                        "_token" : "{{csrf_token()}}",
                        "id_user" : id_user
                    },
                    success: (data) => {
                        let status = data.response.status;  
                        let message = data.response.message;  
                        // showSwal(status,message);
                        // loadDatatable()
                        if (status=="success") {
                            swal.close();
                            loadDatatable()
                            toastr.success(message);
                        }else{
                            swal.close();
                            toastr.error(message);
                        }
                    }
                })
            }
        });
    }

    function unbanned(_this){
        let id_user = $(_this).data("id_user");
        let url_delete_data = url("unbanned");

        swal({
            title: "Peringatan!",
            text: "Apakah anda yakin aktifkan akun ini?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: 'OK',
            closeOnConfirm: false
        }, function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: url_delete_data,
                    type:"POST",
                    data : {
                        "_token" : "{{csrf_token()}}",
                        "id_user" : id_user
                    },
                    success: (data) => {
                        let status = data.response.status;  
                        let message = data.response.message;  
                        // showSwal(status,message);
                        // loadDatatable()
                        if (status=="success") {
                            swal.close();
                            loadDatatable()
                            toastr.success(message);
                        }else{
                            swal.close();
                            toastr.error(message);
                        }
                    }
                })
            }
        });
    }

    function impersonate(_this){
        let id_user = $(_this).data('id_user');
        let url_submit = url("impersonate");
        let csrfToken = "{{csrf_token()}}";
        let data = {
            _token : csrfToken,
            id_user : id_user
        };

        $.ajax({
            url: url_submit,
            type: "POST",
            data: data,
            success: function(data) {
                let status = data.status;  
                let message = data.message;  
                
                if (status=="success") {
                    // this.reset();
                    toastr.success(message);
                    let route_redirect = data.route_redirect;  
                    document.location = route_redirect;
                }else{
                    // showSwal(status,message);
                    toastr.error(message);
                }
            },
            error: function(xhr, status, error) {
                alert('Terjadi Kesalahan Pada Server');
            }
        });
    }

    function select2Group(){
        let url_select2group = url('select2_group');
        $('#group_id').select2({
            // theme: 'bootstrap4',
            placeholder: '--- Pilih Group ---',
            ajax: {
                url: url_select2group,
                dataType: 'json',
                type: "GET",
                delay: 700,
                quietMillis: 10,
                data: function (params) {
                    return {
                        param: $.trim(params.term)
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                id: item.id,
                                text: item.nama_ditampilkan,
                            }
                        })
                    }
                }
            }
        });
    }

    function select2WajibPajak(){
        let url_select2wp = url('select2_wp');
        $('#wp_id').select2({
            // theme: 'bootstrap4',
            placeholder: '--- Pilih Wajib Pajak ---',
            ajax: {
                url: url_select2wp,
                dataType: 'json',
                type: "GET",
                delay: 700,
                quietMillis: 10,
                data: function (params) {
                    return {
                        param: $.trim(params.term)
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                id: item.id,
                                text: `(${item.npwp}) ${item.nama}`,
                            }
                        })
                    }
                }
            }
        });
    }

    function modalUser(isShow,isActionButtonEnable){
        // console.log("modal klik")
        let modal_id = modalId("main");
        if (isShow) {
            $(modal_id).modal("show")
        }else{
            $(modal_id).modal("hide")
        }
        // $(modal_id).modal({
        //     backdrop: 'static',
        //     keyboard: false, // to prevent closing with Esc button (if you want this too)
        //     show: isShow
        // })

        if (isActionButtonEnable) {
            $(`${modal_id} .form-input`).show();
            $(`${modal_id} .detail`).hide();
        }else{
            $(`${modal_id} .detail`).show();
            $(`${modal_id} .form-input`).hide();
        }
    }

    function hidebtnClode(){
        $(".close").click(function(){
            $('#modalUser').modal('hide');
        });
    }
    $(document).ready(function() {
        loadDatatable();
        formWajibPajak();
        select2Group();
        select2WajibPajak();
        hidebtnClode();
    });

</script>
@endsection
