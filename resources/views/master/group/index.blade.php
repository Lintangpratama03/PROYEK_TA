@extends('admin.layout.main')
@section('title', 'Manajamen Group - Smart Dashboard')

@section('content')
<div class="container-fluid">
	<div class="page-header">
		<div class="row">
			<div class="col-sm-6">
				<h3>Manajemen Group</h3>
				<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="#">Home</a></li>
				<li class="breadcrumb-item">Setting</li>
				<li class="breadcrumb-item active">Manajemen Group</li>
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
						<button type="button" class="btn btn-primary" id="btn_tambah"><i class='fa fa-plus'></i> Tambah Group</button>
					</h3>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table dtTable">
							<thead>
								<tr>
									<th>No.</th>
									<th>Nama Group</th>
									<th>Nama Ditampilkan</th>
									<th></th>
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
	            	<label class="col-form-label col-lg-3">Nama Group</label>
	            	<div class="col-lg-9">
	            		<input type="text" name="popup_grup" class="form-control">
	            	</div>
	            </div>
                <div class="form-group row">
	            	<label class="col-form-label col-lg-3">Nama Ditampilkan</label>
	            	<div class="col-lg-9">
	            		<input type="text" name="popup_nama_ditampilkan" class="form-control">
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
	var table;
	$(document).ready(function(){


		table = $(".dtTable").DataTable({
			processing: true,
	        serverSide: true,
	        responsive: true,
	        searchDelay: 2000,
	        ajax: "{{ route('group.get_data') }}",
	        columns: [
	            {data: 'nama', name: 'nama', orderable: false, searchable: false, render : function(data, type, row, meta){
			  		return meta.row+1;
			  	}},
	            {data: 'nama', name: 'nama'},
                {data: 'nama_ditampilkan', name: 'nama_ditampilkan'},
	            {data: 'aksi', name: 'aksi', orderable: false, searchable: false}
	        ],
		});
	})

	$("#btn_tambah").click(function(){
		clear_input();
		$("#modal_title").text("Tambah");
		$("#examplemodal").modal('show');
	})

	function clear_input(){
		$("[name=popup_id]").val('');
		$("[name=popup_grup]").val('');
        $("[name=popup_nama_ditampilkan]").val('');
	}

	$("#btn_simpan").click(function(){
		var id = $("[name=popup_id]").val();
		var nama = $("[name=popup_grup]").val();

		if(nama != ''){
			$.ajax({
				url : "{{ route('group.simpan') }}",
				type : "POST",
				dataType : "json",
				data : $("#form_modal").serialize(),
				headers : {
	        		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		      	},
		      	success : function(respon){
		      		table.ajax.reload();
		      		$("#examplemodal").modal('hide');
		      	}
			})
		}else{

		}

	})

	function edit(this_){
		var data_id = $(this_).data("id");

		var id = $("#table_id"+data_id).val();
		var name = $("#table_nama"+data_id).val();
        var nama_ditampilkan = $("#table_nama_ditampilkan"+data_id).val();

		$("[name=popup_id]").val(id);
		$("[name=popup_grup]").val(name);
        $("[name=popup_nama_ditampilkan]").val(nama_ditampilkan);

		$("#examplemodal").modal("show");
	}

	function hapus(this_){
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
					url: "{{ route('group.hapus') }} ",
					type: 'post',
					dataType : 'json',
					data: {id : id},
						headers : {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
					success: function(respon){ 
						console.log(respon.status);

						if(respon.status == 1){
							swal("Data Berhasil Dihapus", {
								icon: "success",
							});
							table.ajax.reload();
						}else{
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


	function trigger(value){
		$(value).html("<option value=''> -- Pilih -- </option>");
		$(value).val("").trigger("change");
	}
</script>
@endsection