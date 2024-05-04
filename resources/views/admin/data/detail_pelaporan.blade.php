@extends('admin.layout.main')
@section('title', 'Detail Pelaporan PDL - Smart Dashboard')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>Detail Pelaporan PDL</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Daftar Data</a></li>
                    <li class="breadcrumb-item active">Detail Pelaporan PDL</li>
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
                                    <th>Tahun</th>
                                    <th>Bulan</th>
                                    <th>NOP</th>
                                    <th>NPWPD</th>
                                    <th>Nama Rekening</th>
                                    <th>Kode Rekening</th>
                                    <th>Nominal</th>
                                    <th>Nama WP</th>
                                    <th>Alamat WP</th>
                                    <th>Nama OP</th>
                                    <th>Alamat OP</th>
                                    <th>Tanggal Ketetapan</th>
                                    <th>Masa Awal</th>
                                    <th>Masa Akhir</th>
                                    <th>Tanggal Jatuh Tempo</th>
                                    <th>Tanggal Bayar</th>
                                    <th>Status Lapor</th>
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
	        ajax: '{{ route('data.datatable_detail_pelaporan') }}',
	        columns: [
                {data: 'tahun', name: 'tahun', orderable: false, searchable: false, render : function(data, type, row, meta){
			  		return meta.row+1;
			  	}},
	            {data: 'tahun', name: 'tahun'},
                {data: 'bulan', name: 'bulan'},
                {data: 'nop', name: 'nop'},
                {data: 'npwpd', name: 'npwpd'},
                {data: 'nama_rekening', name: 'nama_rekening'},
                {data: 'kode_rekening', name: 'kode_rekening'},
	            {data: 'nominal_ketetapan', name: 'nominal_ketetapan'},
                {data: 'nama_subjek_pajak', name: 'nama_subjek_pajak'},
                {data: 'alamat_subjek_pajak', name: 'alamat_subjek_pajak'},
                {data: 'nama_objek_pajak', name: 'nama_objek_pajak'},
                {data: 'alamat_objek_pajak', name: 'alamat_objek_pajak'},
                {data: 'tanggal_ketetapan', name: 'tanggal_ketetapan'},
                {data: 'masa_awal', name: 'masa_awal'},
                {data: 'masa_akhir', name: 'masa_akhir'},
                {data: 'tanggal_jatuh_tempo', name: 'tanggal_jatuh_tempo'},
                {data: 'tanggal_bayar', name: 'tanggal_bayar'},
                {data: 'status_lapor', name: 'status_lapor'},
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