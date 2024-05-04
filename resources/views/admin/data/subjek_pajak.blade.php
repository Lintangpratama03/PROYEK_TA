@extends('admin.layout.main')
@section('title', 'Subjek Pajak - Smart Dashboard')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>Subjek Pajak</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Daftar Data</a></li>
                    <li class="breadcrumb-item active">Subjek Pajak</li>
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
                                    <th>NOP</th>
                                    <th>NPWPD</th>
                                    <th>Nama Rekening</th>
                                    <th>Kode Rekening</th>
                                    <th>Nama Subjek Pajak</th>
                                    <th>Alamat Subjek Pajak</th>
                                    <!-- <th>Nama Objek Pajak</th> -->
                                    <!-- <th>Alamat Objek Pajak</th> -->
                                    <!-- <th>Tanggal Daftar</th> -->
                                    <!-- <th>Tanggal Tutup</th> -->
                                    <th>Telp Subjek Pajak</th>
                                    <th>Nama Contact Person</th>
                                    <th>Telp Contact Person</th>
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
	        ajax: '{{ route('data.datatable_subjek_pajak') }}',
	        columns: [
                {data: 'nop', name: 'nop', orderable: false, searchable: false, render : function(data, type, row, meta){
			  		return meta.row+1;
			  	}},
                {data: 'nop', name: 'nop'},
                {data: 'npwpd', name: 'npwpd'},
                {data: 'nama_rekening', name: 'nama_rekening'},
                {data: 'kode_rekening', name: 'kode_rekening'},
	            {data: 'nama_subjek_pajak', name: 'nama_subjek_pajak'},
                {data: 'alamat_subjek_pajak', name: 'alamat_subjek_pajak'},
                // {data: 'nama_objek_pajak', name: 'nama_objek_pajak'},
                // {data: 'alamat_objek_pajak', name: 'alamat_objek_pajak'},
                // {data: 'tanggal_daftar', name: 'tanggal_daftar'},
                // {data: 'tanggal_tutup', name: 'tanggal_tutup'},
                {data: 'telp_subjek_pajak', name: 'telp_subjek_pajak'},
                {data: 'nama_contact_person', name: 'nama_contact_person'},
                {data: 'telp_contact_person', name: 'telp_contact_person'},
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