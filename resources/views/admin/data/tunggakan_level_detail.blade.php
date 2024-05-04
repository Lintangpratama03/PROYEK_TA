@extends('admin.layout.main')
@section('title', 'Detail Tunggakan Berdasarkan Level - Smart Dashboard')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>Detail Tunggakan Berdasarkan Level</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Daftar Data</a></li>
                    <li class="breadcrumb-item active">Detail Tunggakan Berdasarkan Level</li>
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
                    <!-- <div class="card-header pb-0">
                        <h6>Tunggakan Bedasarkan Level</h6>
                    </div> -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Level</th>
                                    <th>Kecamatan</th>
                                    <th>Kelurahan</th>
                                    <th>NOP</th>
                                    <th>NPWPD</th>
                                    <th>Nama Subjek Pajak</th>
                                    <th>Alamat Subjek Pajak</th>
                                    <th>Alamat Objek Pajak</th>
                                    <th>Jumlah Tahun Tunggakan</th>
                                    <th>Nominal</th>
                                    <th>Sumber Data</th>
                                    <th>Tanggal Update</th>
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
 

    function Datatable(){
       var table = $(".datatable").DataTable({
            "dom": 'lfrtip',
			processing: true,
	        serverSide: true,
	        responsive: true,
	        searchDelay: 2000,
	        ajax: '{{ route('data.datatable_tunggakan_level_detail') }}',
	        columns: [
                {data: 'level', name: 'level', orderable: false, searchable: false, render : function(data, type, row, meta){
			  		return meta.row+1;
			  	}},
	            {data: 'level', name: 'level'},
                {data: 'kecamatan', name: 'kecamatan'},
                {data: 'kelurahan', name: 'kelurahan'},
                {data: 'nop', name: 'nop'},
                {data: 'npwpd', name: 'npwpd'},
                {data: 'nama_subjek_pajak', name: 'nama_subjek_pajak'},
                {data: 'alamat_subjek_pajak', name: 'alamat_subjek_pajak'},
                {data: 'alamat_objek_pajak', name: 'alamat_objek_pajak'},
                {data: 'jumah_tahun', name: 'jumah_tahun'},
                {data: 'nominal', name: 'nominal'},
                {data: 'sumber_data', name: 'sumber_data'},
                {data: 'tanggal_update', name: 'tanggal_update'}
	        ],
            // order: [[0, 'desc']],
		});
    }


	$(document).ready(function(){

        Datatable();
	})
</script>
@endsection