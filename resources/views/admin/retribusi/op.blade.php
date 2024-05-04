@extends('admin.layout.main')
@section('title', 'Objek Retribusi - Smart Dashboard')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>Objek Retribusi</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Retribusi</a></li>
                    <li class="breadcrumb-item active">Objek Retribusi</li>
                </ol>
            </div>
            <div class="col-sm-6">
            </div>

        </div>
    </div>
</div>
<!-- Container-fluid starts-->
<div class="container-fluid chart-widget">
    <div class="row">
        <div class="col-xl-4">
            <div class="card o-hidden border-0">
                <div class="bg-success b-r-4 card-body">
                <div class="media static-top-widget">
                    <div class="align-self-center text-center"><i data-feather="shopping-bag"></i></div>
                    <div class="media-body"><span class="m-0">Jumlah Objek Retribusi</span>
                    <h4 class="mb-0 counter">656</h4><i class="icon-bg" data-feather="shopping-bag"></i>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="card o-hidden">
                <div class="card-header pb-0">
                    <h6>Kontribusi Terbesar Objek Retribusi</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-kontribusi-op">
                            <thead>
                                <tr>
                                    <th>Tahun SPPT</th>
                                    <th>Jenis Retribusi</th>
                                    <th>Objek Retribusi</th>
                                    <th>Nominal</th>
                                </tr>
                            </thead>
                        </table>			
					</div>
                </div>
            </div>
        </div>
        <!-- <div class="col-xl-8">
            <div class="col-xl-12">
                <div class="card o-hidden">
                    <div class="card-header pb-0">
                        <h6>Pembayaran Paling Awal</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-pembayaran-awal">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal Pembayaran</th>
                                        <th>NOP</th>
                                        <th>Wajib Pajak</th>
                                        <th>Alamat OP</th>
                                    </tr>
                                </thead>
                            </table>			
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-12">
                <div class="card o-hidden">
                    <div class="card-header pb-0">
                        <h6>Pembayaran Paling Tinggi</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-pembayaran-tinggi">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NOP</th>
                                        <th>Wajib Pajak</th>
                                        <th>Alamat OP</th>
                                        <th>Nominal</th>
                                    </tr>
                                </thead>
                            </table>			
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

    </div>

    <!-- <div class="row">
        <div class="card o-hidden">
            <div class="card-header pb-0">
                <h6>Objek Pajak per Wilayah</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-op-wilayah">
                        <thead>
                        <tr>
                            <th>Kecamatan</th>
                            <th>Kelurahan</th>
                            <th>Jumlah OP </th>
                            <th>Nominal</th>
                        </tr>
                        
                        </thead>
                    </table>			
                </div>
            </div>
        </div>
    </div> -->

</div>
<!-- Container-fluid Ends-->
@endsection

@section('js')
<script>

    function formatRupiah(angka){
        var options = {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 2,
        };
        var formattedNumber = angka.toLocaleString('ID', options);
        return formattedNumber;
    }
 

    function table_kontribusi_op(){
       var table = $(".table-kontribusi-op").DataTable({
            "dom": 'rtip',
            paging: false,
			processing: true,
	        serverSide: true,
	        responsive: true,
	        searchDelay: 2000,
	        ajax: '{{ route('retribusi.op.datatable_kontribusi_op') }}',
	        columns: [
	            {data: 'tahun', name: 'tahun'},
                {data: 'nama_rekening', name: 'nama_rekening'},
	            {data: 'nama_objek_pajak', name: 'nama_objek_pajak'},
                {data: 'kontribusi', name: 'kontribusi'}
	        ],
            order: [[0, 'desc']],
		});
    }

    function table_pembayaran_awal(){
       var table = $(".table-pembayaran-awal").DataTable({
            "dom": 'rtip',
			processing: true,
	        serverSide: true,
	        responsive: true,
	        searchDelay: 2000,
	        ajax: '{{ route('pbb.op.datatable_pembayaran_awal') }}',
	        columns: [
                {data: 'tgl_bayar', name: 'tgl_bayar', orderable: false, searchable: false, render : function(data, type, row, meta){
			  		return meta.row+1;
			  	}},
	            {data: 'tgl_bayar', name: 'tgl_bayar'},
	            {data: 'nop', name: 'nop'},
                {data: 'wp', name: 'wp'},
	            {data: 'alamat_op', name: 'alamat_op'}
	        ],
            order: [[1, 'asc']],
		});
    }

    function table_pembayaran_tinggi(){
       var table = $(".table-pembayaran-tinggi").DataTable({
            "dom": 'rtip',
			processing: true,
	        serverSide: true,
	        responsive: true,
	        searchDelay: 2000,
	        ajax: '{{ route('pbb.op.datatable_pembayaran_tinggi') }}',
	        columns: [
                {data: 'nop', name: 'nop', orderable: false, searchable: false, render : function(data, type, row, meta){
			  		return meta.row+1;
			  	}},
	            {data: 'nop', name: 'nop'},
                {data: 'wp', name: 'wp'},
	            {data: 'alamat_op', name: 'alamat_op'},
                {data: 'nominal', name: 'nominal'}
	        ],
            order: [[4, 'desc']],
		});
    }

    function table_op_wilayah(){
       var table = $(".table-op-wilayah").DataTable({
            "dom": 'rtip',
			processing: true,
	        serverSide: true,
	        responsive: true,
	        searchDelay: 2000,
	        ajax: '{{ route('pbb.op.datatable_op_wilayah') }}',
	        columns: [
	            {data: 'kecamatan', name: 'kecamatan'},
                {data: 'kelurahan', name: 'kelurahan'},
	            {data: 'nop', name: 'nop'},
	            {data: 'nominal', name: 'nominal'}
	        ],
            order: [[0, 'asc'],[1, 'asc']],
		});
    }


	$(document).ready(function(){

        table_kontribusi_op();
        table_pembayaran_awal();
        table_pembayaran_tinggi();
        table_op_wilayah();

	})
</script>
@endsection