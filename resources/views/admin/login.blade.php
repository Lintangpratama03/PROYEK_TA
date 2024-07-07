<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Smart Dashboard Kota Madiun">
    <meta name="keywords" content="Smart Dashboard, Kota Madiun, web app">
    <meta name="author" content="agsatu">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="icon" href="{{ asset('assets') }}/images/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets') }}/images/favicon.png" type="image/x-icon">
    <title>SMART DASHBOARD - KOTA SENYUM</title>
    <!-- Google font-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet">
    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/fontawesome.css">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/icofont.css">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/themify.css">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/flag-icon.css">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/feather-icon.css">
    <!-- Plugins css start-->
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/bootstrap.css">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/style.css">
    <link id="color" rel="stylesheet" href="{{ asset('assets') }}/css/color-1.css" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/responsive.css">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/sweetalert2.css">
</head>

<body>
    <!-- Loader starts-->
    <div class="loader-wrapper">
        <div class="theme-loader">
            <div class="loader-p"></div>
        </div>
    </div>
    <!-- Loader ends-->
    <!-- page-wrapper Start-->
    <div class="container-fluid">
        <div class="row">
            <!-- <div class="col-xl-5"><img class="bg-img-cover bg-center" src="{{ asset('assets') }}/images/admin/logo_kota_login.png" alt="looginpage"></div> -->
            <div class="col-xl-12 p-0">
                <div class="login-card">
                    <form id="formLogin" method="post" enctype="multipart/form-data"
                        class="theme-form login-form needs-validation" novalidate="">
                        @csrf
                        <div class="logo-wrapper text-center"><a href="#"><img class="img-fluid" width="50%"
                                    src="{{ asset('images') }}/logo1.png" alt=""></a></div>
                        <hr>
                        <h5>SMART DASHBOARD KOTA SENYUM</h5>
                        <h6>Silahkan Login</h6>
                        <div class="form-group">
                            <label>Username</label>
                            <div class="input-group"><span class="input-group-text"><i class="icon-user"></i></span>
                                <input class="form-control" name="login[username]" type="text" required=""
                                    placeholder="Test">
                                <div class="invalid-tooltip">Please enter proper email.</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <div class="input-group"><span class="input-group-text"><i class="icon-lock"></i></span>
                                <input class="form-control" type="password" name="login[password]" required=""
                                    placeholder="*********">
                                <div class="invalid-tooltip">Please enter password.</div>
                                <div class="show-hide"><span class="show"> </span></div>
                            </div>
                        </div>
                        <!-- <div class="form-group">
              <div class="checkbox">
                <input id="checkbox1" type="checkbox">
                <label class="text-muted" for="checkbox1">Remember password</label>
              </div><a class="link" href="forget-password.html">Forgot password?</a>
            </div> -->
                        <div class="form-group">
                            <button class="btn btn-primary btn-block" type="submit">Sign in</button>
                        </div>
                        <!-- <div class="login-social-title">
              <h5>Sign in with</h5>
            </div>
            <div class="form-group">
              <ul class="login-social">
                <li><a href="https://www.linkedin.com/login" target="_blank"><i data-feather="linkedin"></i></a></li>
                <li><a href="https://www.linkedin.com/login" target="_blank"><i data-feather="twitter"></i></a></li>
                <li><a href="https://www.linkedin.com/login" target="_blank"><i data-feather="facebook"></i></a></li>
                <li><a href="https://www.instagram.com/login" target="_blank"><i data-feather="instagram"> </i></a></li>
              </ul>
            </div>
            <p>Don't have account?<a class="ms-2" href="sign-up.html">Create Account</a></p>
            -->
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>

    <!-- latest jquery-->
    <script src="{{ asset('assets') }}/js/jquery-3.5.1.min.js"></script>
    <!-- feather icon js-->
    <script src="{{ asset('assets') }}/js/icons/feather-icon/feather.min.js"></script>
    <script src="{{ asset('assets') }}/js/icons/feather-icon/feather-icon.js"></script>
    <!-- Sidebar jquery-->
    <script src="{{ asset('assets') }}/js/sidebar-menu.js"></script>
    <script src="{{ asset('assets') }}/js/config.js"></script>
    <!-- Bootstrap js-->
    <script src="{{ asset('assets') }}/js/bootstrap/popper.min.js"></script>
    <script src="{{ asset('assets') }}/js/bootstrap/bootstrap.min.js"></script>
    <!-- Plugins JS start-->
    <script src="{{ asset('assets') }}/js/sweet-alert/sweetalert.min.js"></script>
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="{{ asset('assets') }}/js/script.js"></script>
    <!-- login js-->
    <!-- Plugin used-->

    <script>
        function swal_notif(title, text, type) {
            swal.fire(
                title,
                text,
                type
            )
        }

        function formLogin() {
            let form_id = '#formLogin';
            let url_submit = "{{ url('login/doLogin') }}";

            $(form_id).on("submit", function(event) {
                event.preventDefault();

                let formData = new FormData(this);
                // console.log(url_submit);
                $.ajax({
                    type: 'POST',
                    url: url_submit,
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        // console.log(data);
                        let status = data.status;
                        let message = data.message;

                        if (status == "success") {
                            // this.reset();
                            swal("Sukses", message, status);
                            let route_redirect = data.route_redirect;
                            document.location = route_redirect;
                        } else {
                            swal("Gagal", message, status);
                            // location.reload();
                        }
                    },
                    error: function(data) {
                        alert('Terjadi Kesalahan Pada Server');
                    }

                });
            });
        }

        $(document).ready(function() {
            formLogin();
        });
    </script>
</body>

</html>
