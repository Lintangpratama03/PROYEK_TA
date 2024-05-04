@extends('admin.layout.main')
@section('title', 'Objek Pajak PDL - Smart Dashboard')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>Objek Pajak PDL</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">PDL</a></li>
                    <li class="breadcrumb-item active">Objek Pajak</li>
                    <li class="breadcrumb-item active">Detail Pendaftaran dan Penutupan OP</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- Container-fluid starts-->
<div class="container-fluid chart-widget">
    <div class="row">
        <div class="col-xl-12">
            <div class="col">
                <div class="card o-hidden">
                    <div class="card-header pb-0">
                        <h6>Detail Pendaftaran dan Penutupan OP</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-detail">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="text-align: center;">NO</th>
                                        <th rowspan="2" style="text-align: center;">NOP</th>
                                        <th rowspan="2" style="text-align: center;">NPWPD</th>
                                        <th rowspan="2" style="text-align: center;">Nama Rekening</th>
                                        <th rowspan="2" style="text-align: center;">Nama Subjek Pajak</th>
                                        <th rowspan="2" style="text-align: center;">Alamat Subjek Pajak</th>
                                        <th rowspan="2" style="text-align: center;">Nama Objek Pajak</th>
                                        <th rowspan="2" style="text-align: center;">Alamat Objek Pajak</th>
                                        <th rowspan="2" style="text-align: center;">Tanggal Daftar</th>
                                        <th rowspan="2" style="text-align: center;">Tanggal Tutup</th>
                                        <th colspan="2" style="text-align: center;">Contact Person</th>
                                    </tr>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Nomor Telepon</th>
                                    </tr>
                                </thead>
                            </table>			
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
    function table_detail_daftar_tutup(){
        let kategori = "{{$kategori}}";
        let bulan = "{{$bulan}}";
        let tahun = "{{$tahun}}";
       var table = $(".table-detail").DataTable({
            "dom": 'rtip',
			processing: true,
	        serverSide: true,
	        responsive: true,
	        searchDelay: 2000,
            ajax: {
                url: '{{ route('pdl.op.datatable_detail_daftar_tutup_op') }}',
                type: 'GET',
                data: {
                  "kategori":kategori,
                  "bulan":bulan,
                  "tahun":tahun
                }
            },
	        columns: [
	            {data: 'no', name: 'no'},
                {data: 'nop', name: 'nop'},
	            {data: 'npwpd', name: 'npwpd'},
	            {data: 'nama_rekening', name: 'nama_rekening'},
                {data: 'nama_subjek_pajak', name: 'nama_subjek_pajak'},
                {data: 'alamat_subjek_pajak', name: 'alamat_subjek_pajak'},
	            {data: 'nama_objek_pajak', name: 'nama_objek_pajak'},
	            {data: 'alamat_objek_pajak', name: 'alamat_objek_pajak'},
                {data: 'tanggal_daftar', name: 'tanggal_daftar'},
                {data: 'tanggal_tutup', name: 'tanggal_tutup'},
	            {data: 'nama_contact_person', name: 'nama_contact_person'},
	            {data: 'telp_contact_person', name: 'telp_contact_person'}
	        ],
            order: [[0, 'asc'],[1, 'asc']],
		});
    }

    
	$(document).ready(function(){
        table_detail_daftar_tutup();
	})
</script>
@endsection