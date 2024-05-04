@extends('admin.layout.main')
@section('title', 'Retribusi - Smart Dashboard')

@section('content')
<link href="
https://cdn.jsdelivr.net/npm/sweetalert2@11.10.7/dist/sweetalert2.min.css
" rel="stylesheet">
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 style="font-size: 30px;font-weight: bold;">Retribusi</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item" style="font-size: 18px;font-weight: 600;"><a href="#">Retribusi</a></li>
                        <li class="breadcrumb-item active" style="font-size: 18px;font-weight: 500;">Target Retribusi</li>
                    </ol>
                </div>
                <div class="col-sm-6">
                </div>

            </div>
        </div>
    </div>


    <section class="modal-semua">
        <div class="modal fade" tabindex="-1" role="dialog" id="modaltambah">
            <form id="addForm" method="POST">
            @csrf
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" style="font-size: 20px;font-weight: bold;">Tambah Target OPD</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid" style="font-size: 17px;font-weight: 600;">
                        <div class="row">
                            <div class="form-group">
                                <label for="id_retribusi_opd">Jenis Retribusi</label>
                                <select class="form-control"  style="font-size: 15px;font-weight: normal;" id="id_retribusi_opd" name="id_retribusi_opd">
                                @foreach (getOpdRetribusi() as $item)
                                    {{-- {{ dd($item) }} --}}
                                        <option value="{{ $item->id }}">{{ $item->nama_retribusi }}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tahun">Tahun</label>
                                <select class="form-control "  style="font-size: 15px;font-weight: normal;" id="tahun" name="tahun">
                                    @foreach (array_combine(range(date('Y'), 2018), range(date('Y'), 2018)) as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="target_murni">Target Murni</label>
                                <input type="number" style="font-size: 15px;font-weight: normal;" class="form-control" id="target_murni" name="target_murni" placeholder="Input Target Murni">
                            </div>
                            <div class="form-group">
                                <label for="target_perubahan">Target Perubahan</label>
                                <input type="text" class="form-control"  style="font-size: 15px;font-weight: normal;" name="target_perubahan" id="target_perubahan" placeholder="Input Target Perubahan">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary close" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary simpan-data">Simpan</button>
                    <button type="button" class="btn btn-primary edit-data">Edit</button>
                </div>
              </div>
            </div>
            </form>
          </div>
    </section>

    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            {{-- <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <form>
                            <div class="form-group">
                                <label for="jenisRetribusi">Jenis Retribusi</label>
                                <select class="form-control" id="jenisRetribusi">
                                    <option value="00">-- Pilih Jenis Retribusi --</option>
                                    <option value="AL">Retribus Jasa Umum</option>
                                    <option value="WY">Retribusi Jasa Usaha</option>
                                    <option value="WY">Retribusi Perizinan Tertentu</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tahun">Tahun</label>
                                <select class="form-control " id="tahun">
                                    <option value="00">-- Pilih Tahun OPD --</option>
                                    @foreach (array_combine(range(date('Y'), 2018), range(date('Y'), 2018)) as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="target_murni">Target Murni</label>
                                <input type="number" class="form-control" id="target_murni" placeholder="Input Target Murni">
                            </div>
                            <div class="form-group">
                                <label for="target_perubahan">Target Perubahan</label>
                                <input type="text" class="form-control" id="target_perubahan" placeholder="Input Target Perubahan">
                            </div>
                            <button type="submit" class="btn btn-primary"><i class='fa fa-plus'></i> Tambah
                                Target OPD</button>
                        </form>
                    </div>
                </div>
            </div> --}}
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center gap-2">
                            <label for="" style="font-size: 20px;font-weight: bold;">Tabel Target OPD</label>
                            <a href="javascript:void(0)" class="btn btn-primary btn-tambah-data btn_tambah_opd"><i class="fa fa-plus me-2" aria-hidden="true"></i>Tambah</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table tabel_target_opd" style="font-size: 15px;font-weight: 500;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis Retribusi</th>
                                        <th>Tahun</th>
                                        <th>Target Murni</th>
                                        <th>Target Perubahan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Container-fluid Ends-->
@endsection

@section('js')
        <script src="{{ asset("js/sweetalert2.all.min.js") }}"></script>
        <script>

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function datatable_target_opd() {
            var table = $(".tabel_target_opd").DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 2000,
                ajax: {
                    url: '{{ route('retribusi.opd.datatable_kontribusi_op') }}',
                    type: 'GET',
                },
                columns: [
                    {
                        data: 'no',
                        name: 'no'
                    },
                    {
                        data: 'id_retribusi_opd',
                        name: 'id_retribusi_opd'
                    },
                    {
                        data: 'tahun',
                        name: 'tahun'
                    },
                    {
                        data: 'target_murni',
                        name: 'target_murni'
                    },
                    {
                        data: 'target_perubahan',
                        name: 'target_perubahan'
                    },
                    { data: 'action', name: 'action' },
                ]
            });
        }

        function showHideButton(value){
            if(value == "tambah"){
                $('.edit-data').addClass('d-none');
                $('.simpan-data').removeClass('d-none');
            }else{
                $('.simpan-data').addClass('d-none');
                $('.edit-data').removeClass('d-none');
            }
        }
        function tambahDataTargetOpd(){
            $('body').on('click', '.btn-tambah-data', function(e) {
                e.preventDefault();
                showHideButton("tambah");
                console.log("masuk");
                $('#modaltambah').modal('show');
                $('.simpan-data').off('click').on('click',function() {
                    console.log("masuk2");
                    // console.log($('#id_retribusi_opd').val());
                    $.ajax({
                        url: '{{ route('retribusi.opd.form_opd_form') }}',
                        type: 'POST',
                        data: {
                            "id_retribusi_opd" : $('#addForm #id_retribusi_opd').val(),
                            "tahun" : $('#addForm #tahun').val(),
                            "target_murni" : $('#addForm #target_murni').val(),
                            "target_perubahan" : $('#addForm #target_perubahan').val(),
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            // console.log(response);
                            console.log("berhasil 1");
                            if (response.success) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Berhasil",
                                    text: response.success,
                                });
                                $('#modaltambah').modal('hide');
                                $('.tabel_target_opd').DataTable().ajax.reload();
                            } else {
                                if (Array.isArray(response.error)) {
                                    var errorMessages = "<ul>";
                                    $.each(response.error, function (key, value) {
                                        errorMessages += "<li>" + value + "</li>";
                                    });
                                    errorMessages += "</ul>";
                                    $('#modaltambah').modal('hide');
                                    Swal.fire({
                                        icon: "error",
                                        title: "Gagal",
                                        html: errorMessages,
                                    });
                                }else{
                                    $('#modaltambah').modal('hide');
                                    Swal.fire({
                                        icon: "error",
                                        title: "Gagal",
                                        html: response.error,
                                    });
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var errors = xhr.responseJSON.error;
                            var errorMessage = '';
                            $.each(errors, function(key, value) {
                                errorMessage += value + '<br>';
                            });
                            $('#modaltambah').modal('hide');
                            Swal.fire({
                                icon: "error",
                                title: "Gagal",
                                html: errorMessage,
                            });
                        }
                    });
                    $('#addForm')[0].reset();
                });
            });
        }
        function editDataOpd(){
            $('body').on('click', '.btn-edit-data', function(e) {
                e.preventDefault();
                showHideButton("edit");
                var id = $(this).data('id');
                $.ajax({
                    url: "{{ url('retribusi/opd/form_opd') }}/" + id + "/edit",
                    type: 'GET',
                    success: function(response) {
                        console.log(response.result.id);
                        $('#addForm #id_retribusi_opd').val(response.result.id_retribusi_opd);
                        $('#addForm #tahun').val(response.result.tahun);
                        $('#addForm #target_murni').val(response.result.target_murni);
                        $('#addForm #target_perubahan').val(response.result.target_perubahan);
                        $('#modaltambah').modal('show');

                        var idNew = response.result.id;
                        console.log(idNew);
                        $('.edit-data').off('click').on('click',function() {
                            $.ajax({
                                url: "{{ url('retribusi/opd/form_opd') }}/" + idNew,
                                type: 'PUT',
                                dataType: 'json',
                                data: {
                                    "id_retribusi_opd" : $('#addForm #id_retribusi_opd').val(),
                                    "tahun" : $('#addForm #tahun').val(),
                                    "target_murni" : $('#addForm #target_murni').val(),
                                    "target_perubahan" : $('#addForm #target_perubahan').val(),
                                },
                                    success: function(response) {
                                    // console.log(response);
                                    if (response.success) {
                                        Swal.fire({
                                            icon: "success",
                                            title: "Berhasil",
                                            text: response.success,
                                        });
                                        $('#modaltambah').modal('hide');
                                        $('.tabel_target_opd').DataTable().ajax.reload();
                                    } else {
                                        if (Array.isArray(response.error)) {
                                            var errorMessages = "<ul>";
                                            $.each(response.error, function (key, value) {
                                                errorMessages += "<li>" + value + "</li>";
                                            });
                                            errorMessages += "</ul>";
                                            Swal.fire({
                                                icon: "error",
                                                title: "Gagal",
                                                html: errorMessages,
                                            });
                                            $('#modaltambah').modal('hide');
                                        }else{
                                            Swal.fire({
                                                icon: "error",
                                                title: "Gagal",
                                                html: response.error,
                                            });
                                            $('#modaltambah').modal('hide');
                                        }
                                    }
                                },
                                error: function(xhr, status, error) {
                                    var errors = xhr.responseJSON.error;
                                    var errorMessage = '';
                                    $.each(errors, function(key, value) {
                                        errorMessage += value + '<br>';
                                    });
                                    Swal.fire({
                                        icon: "error",
                                        title: "Gagal",
                                        html: errorMessage,
                                    });
                                }
                            });
                            $('#addForm')[0].reset(); 
                        });
                    }
                });
            });
        }
        function hapusDataOpd(){
            $('body').on('click', '.btn-hapus-data', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                Swal.fire({
                title: 'Apakah anda yakin?',
                text: 'ingin menghapus data ini!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: '{{ url('retribusi/opd/form_opd') }}/' + id,
                            type: 'DELETE',
                            success:function(response){
                                Swal.fire({
                                    icon: "success",
                                    title: "Berhasil",
                                    text: response.success,
                                });
                            }
                        });
                        $('.tabel_target_opd').DataTable().ajax.reload();
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Berhasil",
                            text: "Batal menghapus data !",
                        });
                    }
                });

            });
            
            
        }

        function hiddenInput(){
            $('#modaltambah').on('hidden.bs.modal',function(){
                $('#addForm #target_murni').val("")
                $('#addForm #target_perubahan').val("")
            });
        }

        function hidebtnClode(){
            $(".close").click(function(){
                $('#modaltambah').modal('hide');
            });
        }

        $(document).ready(function() {
            datatable_target_opd();
            tambahDataTargetOpd();
            editDataOpd();
            hapusDataOpd();
            hiddenInput();
            hidebtnClode();
        });

    </script>
@endsection
