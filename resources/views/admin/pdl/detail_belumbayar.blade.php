@extends('admin.layout.main')
@section('title', 'Detail OP Belum Lapor - Smart Dashboard')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>Detail OP Belum Lapor</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">PDL</a></li>
                    <li class="breadcrumb-item"><a href="#">Pelaporan</a></li>
                    <li class="breadcrumb-item active">Detail OP Belum Lapor</li>
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
        <div class="col-xl-12">
            <div class="col-xl-12">
                <div class="card o-hidden">
                    <div class="card-header pb-0">
                        <h6>Detail Objek Pajak Belum Lapor</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-detail-belum-lapor">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NOP</th>
                                    <th>NPWPD</th>
                                    <th>Nama Objek Pajak</th>
                                    <th>Alamat OP</th>
                                    <th>Nama Wajib Pajak</th>
                                    <th>Alamat WP</th>
                                    <th>Jenis Pajak</th>
                                    <th>Tahun Pajak</th>
                                    <th>Masa Pajak</th>
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

    function formatRupiah(angka){
        var options = {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 2,
        };
        var formattedNumber = angka.toLocaleString('ID', options);
        return formattedNumber;
    }
 

    function table_detail_tunggakan(){
       let nop = "{{$nop}}";
       var table = $(".table-detail-belum-lapor").DataTable({
            "dom": 'lfrtip',
			processing: true,
	        serverSide: true,
	        responsive: true,
	        searchDelay: 2000,
            ajax: {
                url: '{{ route('pdl.pelaporan.datatable_detail_belumbayar') }}',
                type: 'GET',
                data: {
                  "nop":nop,
                }
            },
	        columns: [
                {data: 'nop', name: 'nop', orderable: false, searchable: false, render : function(data, type, row, meta){
			  		return meta.row+1;
			  	}},
                {data: 'nop', name: 'nop'},
	            {data: 'npwpd', name: 'npwpd'},
                {data: 'nama_objek_pajak', name: 'nama_objek_pajak'},
	            {data: 'alamat_objek_pajak', name: 'alamat_objek_pajak'},
                {data: 'nama_subjek_pajak', name: 'nama_subjek_pajak'},
                {data: 'alamat_subjek_pajak', name: 'alamat_subjek_pajak'},
                {data: 'nama_rekening', name: 'nama_rekening'},
	            {data: 'tahun', name: 'tahun'},
	            {data: 'bulan', name: 'bulan'}
	        ],
            // order: [[0, 'desc']],
		});
    }


	$(document).ready(function(){

        table_detail_tunggakan();
	})
</script>
@endsection