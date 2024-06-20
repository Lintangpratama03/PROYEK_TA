@extends('admin.layout.main')
@section('title', 'Update Akun - Smart Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Akun</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Update</a></li>
                        <li class="breadcrumb-item active">Akun</li>
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
            <div class="col-xl-7">
                <div class="row draggable">
                    <div class="card o-hidden">
                        <div class="card-header pb-0">
                            <h6>Informasi Akun</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="block block-rounded block-themed">
                                        <div class="block-content pb-3">
                                            <div class="row">
                                                <input value="{{ $id }}" type="hidden" class="form-control"
                                                    id="id" name="id">
                                                <div class="col-lg-3 col-xl-3">
                                                    <div class="mb-4">
                                                        <label class="form-label" for="nama">Nama</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="mb-4">
                                                        <input type="text" class="form-control" id="inputnama"
                                                            name="inputnama" placeholder="Masukkan Nama ....">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-xl-3">
                                                    <div class="mb-4">
                                                        <label class="form-label" for="username">Username</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="mb-4">
                                                        <input type="text" class="form-control" id="username"
                                                            name="username" placeholder="Masukkan Username ...." readonly>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-xl-3">
                                                    <div class="mb-4">
                                                        <label class="form-label" for="email">Email</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="mb-4">
                                                        <input type="text" class="form-control" id="email"
                                                            name="email" placeholder="Masukkan Email ....">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-xl-3">
                                                    <div class="mb-4">
                                                        <label class="form-label" for="group">Nama Group</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="mb-4">
                                                        <input type="text" class="form-control" id="group"
                                                            name="group" placeholder="Masukkan Group ....">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-primary btn-update-info" data-toggle="layout"
                                        data-action="header_search_off">
                                        <i class="fa fa-check-circle opacity-50 me-1"></i> Update Informasi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-5">
                <div class="row draggable">
                    <div class="card o-hidden">
                        <div class="card-header pb-0">
                            <h6>Update Akun</h6>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12">
                                <div class="block block-rounded block-themed">
                                    <div class="block-content pb-3">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="col-lg-12">
                                                    <label class="form-label" for="example-text-input">Password
                                                        Lama</label>
                                                    <div class="mb-4">
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-key"></i>
                                                            </span>
                                                            <input type="password" class="form-control"
                                                                placeholder="Masukkan Password Lama ..." id="password-lama"
                                                                name="password-lama">
                                                            <button type="button"
                                                                class="btn btn-outline-secondary toggle-password">
                                                                <i class="fa fa-eye"></i> Show
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <label class="form-label" for="example-text-input">Password
                                                        Baru</label>
                                                    @php
                                                        $session = Session::get('user_app');
                                                        $id = decrypt($session['group_id']);
                                                        $id_user = decrypt($session['user_id']);
                                                        $data = DB::table('auth.user_group')
                                                            ->select('*')
                                                            ->where('id', $id)
                                                            ->first();
                                                    @endphp
                                                    <input type="text" class="form-control d-none"
                                                        value="{{ $id_user }}" id="id_user_password">
                                                    <div class="mb-4">
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-key"></i>
                                                            </span>
                                                            <input type="password" class="form-control"
                                                                placeholder="Masukkan Password Baru ..."
                                                                id="password-baru" name="password-baru">
                                                            <button type="button"
                                                                class="btn btn-outline-primary toggle-password">
                                                                <i class="fa fa-eye"></i> Show
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <label class="form-label" for="example-text-input">Konfirmasi
                                                        Password</label>
                                                    <div class="mb-4 input-group">
                                                        <span class="input-group-text">
                                                            <i class="fa fa-key"></i>
                                                        </span>
                                                        <input type="password" class="form-control"
                                                            placeholder="Konfirmasi password ..." id="konfirmasi-password"
                                                            name="konfirmasi-password">
                                                        <button type="button"
                                                            class="btn btn-outline-primary toggle-password">
                                                            <i class="fa fa-eye"></i> Show
                                                        </button>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-primary btn-update-akun"
                                                    data-toggle="layout" data-action="header_search_off">
                                                    <i class="fa fa-check-circle opacity-50 me-1"></i> Update Password
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
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

    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script>
        function getProfile() {
            var id = $('#id').val();
            $.ajax({
                url: "/login/profile/" + id,
                type: 'GET',
                dataType: "json",
                success: function(response) {
                    if (response.result) {
                        $('#inputnama').val(response.result.nama);
                        $('#username').val(response.result.username);
                        $('#email').val(response.result.email);
                        $('#group').val(response.result.group_name);
                    }
                }
            });
        }

        function updateInfo() {
            $('body').on('click', '.btn-update-info', function(e) {
                e.preventDefault();
                var id = $('#id').val();
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                var nama = $('#inputnama').val();
                var email = $('#email').val();
                var group = $('#group').val();

                // Prompt for password using SweetAlert2
                Swal.fire({
                    title: 'Konfirmasi Identitas',
                    html: '<input type="password" id="swal-input-password" class="swal2-input" placeholder="Masukkan password Anda">',
                    showCancelButton: true,
                    confirmButtonText: 'Submit',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        const password = document.getElementById('swal-input-password').value;
                        return {
                            password: password
                        };
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Proceed with AJAX request
                        $.ajax({
                            url: "/login/update_info/" + id,
                            type: 'POST',
                            data: {
                                nama: nama,
                                email: email,
                                group: group,
                                password: result.value
                                .password, // Pass password from SweetAlert2 input
                                _token: csrfToken
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: 'Sukses!',
                                        text: 'Informasi berhasil diperbarui',
                                        icon: 'success',
                                        timer: 5000,
                                    });
                                } else {
                                    var errorMessages = "<ul>";
                                    $.each(response.error, function(key, value) {
                                        errorMessages += "<li>" + value + "</li>";
                                    });
                                    errorMessages += "</ul>";
                                    Swal.fire({
                                        icon: "error",
                                        title: "Gagal",
                                        html: errorMessages,
                                    });
                                }
                            }
                        });
                    }
                });
            });
        }


        function updatePassword() {
            $('body').on('click', '.btn-update-akun', function(e) {
                e.preventDefault();
                var iduser = $('#id_user_password').val();
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                var passwordLama = $('#password-lama').val();
                var newPassword = $('#password-baru').val();
                var confirmPassword = $('#konfirmasi-password').val();

                if (newPassword.trim() === '') {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Password baru harus diisi',
                        icon: 'error',
                        timer: 5000,
                    });
                    return;
                }

                if (newPassword !== confirmPassword) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Password tidak sama',
                        icon: 'error',
                        timer: 5000,
                    });
                    return;
                }

                $.ajax({
                    url: "/login/update_pass/" + iduser,
                    type: 'POST',
                    data: {
                        password_lama: passwordLama,
                        password_baru: newPassword,
                        _token: csrfToken
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Sukses!',
                                text: 'Password berhasil diperbarui',
                                icon: 'success',
                                timer: 5000,
                            });

                            $('#password-baru').val('');
                            $('#konfirmasi-password').val('');
                        } else {
                            if (response.error && response.error.includes('Password lama salah')) {
                                Swal.fire({
                                    icon: "error",
                                    title: "Gagal",
                                    text: "Password lama salah",
                                });
                            } else {
                                var errorMessages = "<ul>";
                                $.each(response.error, function(key, value) {
                                    errorMessages += "<li>" + value + "</li>";
                                });
                                errorMessages += "</ul>";
                                Swal.fire({
                                    icon: "error",
                                    title: "Gagal",
                                    html: errorMessages,
                                });
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: "error",
                            title: "Gagal",
                            text: "Terjadi kesalahan saat memproses permintaan",
                        });
                    }
                });
            });
        }

        $(document).on('click', '.toggle-password', function() {
            $(this).toggleClass('show-password');
            var input = $(this).siblings('input');
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                $(this).html('<i class="fa fa-eye-slash"></i> Hide');
            } else {
                input.attr('type', 'password');
                $(this).html('<i class="fa fa-eye"></i> Show');
            }
        });
        $(document).ready(function() {
            getProfile();
            updatePassword();
            updateInfo();
        })
    </script>
@endsection
