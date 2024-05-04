@extends('admin.layout.main')
@section('title', 'Manajamen Menu - Smart Dashboard')

@section('content')
<div class="container-fluid">
	<div class="page-header">
		<div class="row">
			<div class="col-sm-6">
				<h3>Manajemen Menu</h3>
				<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="#">Home</a></li>
				<li class="breadcrumb-item">Setting</li>
				<li class="breadcrumb-item active">Manajemen Menu</li>
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
						<button type="button" class="btn btn-primary" id="btn_tambah"><i class='fa fa-plus'></i> Tambah Menu</button>
					</h3>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table dtTable" id="tabel-menu" >
							<thead>
								<tr>
									<th>ID</th>
									<th>MENU</th>
									<th>LINK</th>
									<th>ICON</th>
									<th>PARENT MENU</th>
									<!-- <th>TIPE SITE</th> -->
									<th>URUTAN</th>
									<!-- <th>GAMBAR BACKGROUND</th> -->
									<th>AKSI</th>
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
            <form id="form_modal" method="post" enctype="multipart/form-data">
            <input type="hidden" name="popup_id">
            <div class="modal-body" id="modal_body">
            	<div class="form-group row">
	            	<label class="col-form-label col-lg-3">Menu</label>
	            	<div class="col-lg-9">
	            		<input type="text" name="popup_name" class="form-control">
	            	</div>
	            </div>
	            <div class="form-group row">
	            	<label class="col-form-label col-lg-3">Url</label>
	            	<div class="col-lg-9">
	            		<input type="text" name="popup_url" placeholder="isi # jika Menu Parent" class="form-control">
	            	</div>
	            </div>
	            <div class="form-group row">
	            	<label class="col-form-label col-lg-3">Icon</label>
	            	<div class="col-lg-9">
	            		<input type="text" name="popup_icon" class="form-control">
	            	</div>
	            </div>
	            <!-- <div class="form-group row">
	            	<label class="col-form-label col-lg-3">Tipe Site</label>
	            	<div class="col-lg-9">
	            		<select name="popup_aktif" class="form-control" style="width: 100%">
	            			<option value="1">1</option>
	            			<option value="0">0</option>
	            		</select>
	            	</div>
	            </div> -->
	            <div class="form-group row">
	            	<label class="col-form-label col-lg-3">Urutan</label>
	            	<div class="col-lg-9">
	            		<input type="number" name="popup_urutan" class="form-control">
	            	</div>
	            </div>
	            <div class="form-group row">
	            	<label class="col-form-label col-lg-3">Parent</label>
	            	<div class="col-lg-9">
	            		<select id="popup_parent" name="popup_parent" class="form-control" style="width: 100%">
	            			<option value="0">Parent Menu</option>
	            			@foreach($data['menu'] as $d)
	            			<option value="{{$d->id}}">{{($d->parent==0)?$d->name:'['.$d->parent_menu.'] '.$d->name}}</option>
	            			@endforeach
	            		</select>
	            	</div>
	            </div>
				<!-- <div class="form-group row">
	            	<label class="col-form-label col-lg-3">Gamabar Background</label>
	            	<div class="col-lg-9">
						<input id="foto_background" name="foto_background" class="form-control" type="file" accept="image/*" data-plugin="dropify" data-default-file=""/>
						<p class="help-block">Tidak Wajib Diisi * Disarankan menggunakan gambar dengan format landscape</p>
					</div>
	            </div> -->
				
            </div>
            <div class="modal-footer" id="modal_footer">
            	<button type="submit" class="btn btn-primary" id="btn_simpan">Simpan</button>
            </div>
            </form>
        </div>
	</div>
</div>

<div class="modal fade modal-fill-in" id="modal_show_background" aria-hidden="false" aria-labelledby="modal_show_background" role="dialog" tabindex="-1">
	<div class="modal-dialog modal-simple">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title" id="modal_show_backgroundModalTitle">Gambar Background Menu</h4>
			</div>
			<div class="modal-body">
				<img id="show_image" class="img-fluid" alt="">
			</div>
		</div>
	</div>
</div>

@endsection

@section('js')
<!-- <script src="{{ asset('js/fancybox.min.js')}}"></script> -->
<script type="text/javascript">
	var tabelModul;
  	var modalModul = $("#modal-modul");
  	var formModul = $("#form-modul");

  	var dataForm = {};
  	var url;
	$(document).ready(function(){
		
		$("#popup_parent").select2();
		// $("[name=popup_prov], [name=popup_kab], [name=popup_kec], [name=popup_kel], [name=popup_aktif]").select2({
		// 	dropdownParent: $("#examplemodal")
		// });

		// initTabelModul();

		table = $(".dtTable").DataTable({
			processing: true,
	        serverSide: true,
	        responsive: true,
	        searchDelay: 2000,
	        ajax: '{{ route('menu.get_data') }}',
	        columns: [
	            {data: 'nama', name: 'nama', orderable: false, searchable: false, render : function(data, type, row, meta){
			  		return meta.row+1;
			  	}},
	            {data: 'menu', name: 'menu'},
	            {data: 'link', name: 'link'},
	            {data: 'icon', name: 'icon'},
	            {data: 'parent', name: 'parent'},
	            // {data: 'tipe', name: 'tipe'},
	            {data: 'urutan', name: 'urutan'},
				// {data: 'background', name: 'background'},
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
		$("[name=popup_name]").val('');
		$("[name=popup_url]").val('');
		$("[name=popup_icon]").val('');
		$("[name=popup_urutan]").val('');
		$("[name=popup_parent]").val('').trigger("change");
		// $("[name=popup_aktif]").val('1').trigger("change");
	}

	function edit(id){
		$("[name=popup_id]").val(id);
		$("[name=popup_name]").val($("#table_nama"+id).val());
		$("[name=popup_url]").val($("#table_url"+id).val());
		$("[name=popup_icon]").val($("#table_icon"+id).val());
		$("[name=popup_urutan]").val($("#table_urutan"+id).val());
		$("[name=popup_parent]").val($("#table_parent"+id).val()).trigger("change");
		// $("[name=popup_aktif]").val($("#table_tipe"+id).val()).trigger("change");

		$("#modal_title").text("Edit");
		$("#examplemodal").modal('show');
	}

	$("#form_modal").on("submit", function (event) {
		event.preventDefault();
		
		let formData = new FormData(this);
		var nama 	= $("[name=popup_name]").val();
		var url 	= $("[name=popup_url]").val();
		var icon 	= $("[name=popup_icon]").val();
		var urutan 	= $("[name=popup_urutan]").val();
		var parent 	= $("[name=popup_parent]").val();
		if(nama != '' && url != '' &&  icon != '' && urutan != '' && parent != ''){
			$.ajax({
				url : "{{ route('menu.simpan') }}",
				type : "POST",
				cache:false,
				contentType: false,
				processData: false,
				data : formData,
				headers : {
		        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		      	},
		      	success : function(respon){
					console.log(respon);
					// let status = respon.status;  

					if (respon.status == 1) {
		      			table.ajax.reload();
		      			$("#examplemodal").modal("hide");
						  toastr.success("Berhasil Menambahkan Menu");
					}else if (respon.status == 2) {
		      			table.ajax.reload();
		      			$("#examplemodal").modal("hide");
						  toastr.success("Berhasil Mengubah Menu");
					}else{
		      			table.ajax.reload();
		      			$("#examplemodal").modal("hide");
						  toastr.error("Gagal Menambahkan Menu");
					}
					
		      	}
			})
		}
	})

	function hapus(id){
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
					url: "{{ route('menu.hapus') }} ",
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

	function show_image_background(_this){
		let image = $(_this).data('image');
		$("#modal_show_background").modal('show');
		document.getElementById("show_image").src = "{{asset("images_background_menu")}}/"+image;
	}
</script>
@endsection