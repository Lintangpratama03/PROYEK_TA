@extends('admin.layout.main')
@section('title', 'Penerimaan Bedasarkan Tahun SPPT - Smart Dashboard')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>Penerimaan Bedasarkan Tahun SPPT</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Daftar Data</a></li>
                    <li class="breadcrumb-item active">Penerimaan Bedasarkan Tahun SPPT</li>
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
                                    <th>Tahun SPPT</th>
                                    <th>Tahun Bayar</th>
                                    <th>Bulan Bayar</th>
                                    <th>Nominal Pokok</th>
                                    <th>Nominal Denda</th>
                                    <th>Nominal Terima</th>
                                    <th>Jumlah NOP</th>
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
	        ajax: '{{ route('data.datatable_penerimaan_tahun_sppt') }}',
	        columns: [
                {data: 'tahun_sppt', name: 'tahun_sppt', orderable: false, searchable: false, render : function(data, type, row, meta){
			  		return meta.row+1;
			  	}},
	            {data: 'tahun_sppt', name: 'tahun_sppt'},
                {data: 'tahun_bayar', name: 'tahun_bayar'},
                {data: 'bulan_bayar', name: 'bulan_bayar'},
                {data: 'nominal_pokok', name: 'nominal_pokok'},
                {data: 'nominal_denda', name: 'nominal_denda'},
                {data: 'nominal_terima', name: 'nominal_terima'},
                {data: 'nop', name: 'nop'},
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