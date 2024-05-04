@extends('admin.layout.main')
@section('title', 'Retribusi - Smart Dashboard')

@section('content')
    {{-- <style>
        #upload_lampiran::before{
            content: attr("hello") !important;
        }
    </style> --}}
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
                        <li class="breadcrumb-item active" style="font-size: 18px;font-weight: 500;">Detail Realisasi OPD</li>
                    </ol>
                </div>
                <div class="col-sm-6">
                </div>

            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <label for="" style="font-size: 18px;font-weight: bold;">Detail Target Realisasi</label>
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="table-responsive">
                                @csrf
                                <table class="table tabel_detail_target_realisasi" style="font-size: 15px;font-weight: 600;">
                                    <thead>
                                        <tr>
                                            <th>bulan</th>
                                            <th>Realisasi</th>
                                            <th>Upload File</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="data_detail_target_realisasi">
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end gap-3">
                                    <a href="{{ route('retribusi.realisasi.realisasi_index') }}" class="btn btn-warning btn_back_detail_realisasi">Back</a>
                                    <button type="button" class="btn btn-primary btn_simpan_detail_realisasi" id="btnUpdateDataRealisasi">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">Lampiran</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex justify-content-center">
              <img id="img_lampiran" width="500" height="500" src="" alt="">
              <object id="pdf_viewer" width="100%" height="500"></object>
            </div>
          </div>
        </div>
      </div>

    <!-- Container-fluid Ends-->
@endsection

@section('js')
    <script src="
    https://cdn.jsdelivr.net/npm/sweetalert2@11.10.7/dist/sweetalert2.all.min.js
    "></script>
    <script src="
    https://cdn.jsdelivr.net/npm/jquery-formdata@0.1.3/jquery.formdata.min.js
    "></script>
    <script>

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function datatable_detail_realisasi() {
            var id_opd = "{{ $id_opd }}";
            // $('#data_detail_target_realisasi').empty();
            $.ajax({
                url: '{{ route('retribusi.realisasi.datatable_detail_target_realisasi') }}',
                type: 'GET',
                data: {
                    "id": id_opd,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    let isi = '';
                    $('#data_detail_target_realisasi').empty();
                    $.each(response.data, function(key, value) {
                        isi += `
                            <tr>
                                <td>
                                    <input type="text" class="form-control" style="font-size: 15px;font-weight: 500;" data-id="${value.id}" id="bulan" aria-describedby="bulan" name="bulan" placeholder="Bulan ..." value="${value.bulan.toUpperCase()}" disabled>
                                </td>
                                <td>
                                    <input type="number" class="form-control" style="font-size: 15px;font-weight: 500;" data-id="${value.id}" id="realisasi" aria-describedby="realisasi" name="realisasi" placeholder="Realisasi ..." value="${value.realisasi ? value.realisasi : 0}">
                                </td>
                                <td>
                                    <input type="file" id="upload_lampiran" data-id="${value.id}" class="form-control" id="inputGroupFile01" accept=".jpg, .jpeg">
                                </td>
                                <td>
                                    ${value.lampiran ? `
                                        <div class="btn_show_lampiran bg-primary" data-image="${value.lampiran}" data-id="${value.id}">
                                            <i class="fa fa-eye" aria-hidden="true"></i> Terupload
                                        </div>
                                    ` : ''}
                                </td>
                            </tr>
                        `;
                    });

                    $('#data_detail_target_realisasi').append(isi);
                }
            });
        }
        function showLampiran(){
            $(document).on('click', '.btn_show_lampiran', function(e) {
                e.preventDefault();
                var image = $(this).data('image');
                var fileExt = image.split('.').pop().toLowerCase();
                if(fileExt == "pdf"){
                    $('#pdf_viewer').removeClass("d-none");
                    $('#img_lampiran').addClass("d-none");
                    var pdfFile = `{{ asset('storage/realisasi/${image}') }}`
                    // var pdfViewer = document.getElementById('pdf_viewer');
                    // pdfViewer.data = pdfFile;
                    window.open(pdfFile, '_blank');
                }else{
                    $('#pdf_viewer').addClass("d-none");
                    $('#img_lampiran').removeClass("d-none");
                    $('#img_lampiran').attr('src', `{{ asset('storage/realisasi/${image}') }}`);
                    $('#exampleModal').modal("show");
                }
             
            });
        }

        function update_data_realisasi() {
            $('#btnUpdateDataRealisasi').click(function(e) {
                e.preventDefault();
                var formData = new FormData();
                $('.tabel_detail_target_realisasi tbody tr').each(function(index) {
                    var id = $(this).find('input[name="bulan"]').data('id');
                    var bulan = $(this).find('input[name="bulan"]').val().toLowerCase();
                    var realisasi = $(this).find('input[name="realisasi"]').val();
                    var fileInput = $(this).find('input[type="file"]')[0]; 
                    // console.log(fileInput.files[0]);
                    
                    formData.append('data[' + index + '][id]', id);
                    formData.append('data[' + index + '][bulan]', bulan);
                    formData.append('data[' + index + '][realisasi]', realisasi);
                    formData.append('data[' + index + '][lampiran]', fileInput.files[0]);
                });
                
                Swal.fire({
                    title: "Peringatan!",
                    text: "Apakah anda yakin ingin update data realisasi?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "OK",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('retribusi.realisasi.update_target_realisasi') }}',
                            type: 'POST', 
                            data: formData,
                            contentType: false,
                            processData: false,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                // console.log("masuk");
                                var message = response.success;
                                Swal.fire({
                                    icon: "success",
                                    title: "Berhasil",
                                    text: message,
                                });
                                datatable_detail_realisasi();
                            }
                        });
                    }
                });
            });
        }

        $(document).ready(function() {
            datatable_detail_realisasi();
            update_data_realisasi();
            showLampiran();
            $(".tabel_detail_target_realisasi").DataTable({
                paging: false // Only show page numbers, no 'Previous' and 'Next' buttons
            });
        });
    </script>
@endsection
