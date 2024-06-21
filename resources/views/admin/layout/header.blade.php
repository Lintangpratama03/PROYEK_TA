<div class="page-main-header">
    <div class="main-header-right row m-0">
        <div class="main-header-left">
            <div class="logo-wrapper"><a href="#"><img class="img-fluid" width="100%"
                        src="{{ asset('images') }}/logo2.jpg" alt=""></a></div>
            <div class="dark-logo-wrapper d-flex align-items-center"><a href="#">
                    {{-- <img class="img-fluid" width="100%"
                        src="{{ asset('images') }}/logo2.jpg" alt=""> --}}
                    <h2 style="font-size: 18px ;color:#FFF;font-weight: bold;">SmartDashboard</h2>
                </a></div>
            <div class="toggle-sidebar"><i class="status_toggle middle" data-feather="align-center"
                    id="sidebar-toggle"></i></div>
        </div>
        <div class="left-menu-header col">
            <ul>
                <li>
                    <h2 id="header_top_judul">Badan Pendapatan Daerah Kota Senyum</h2>
                </li>
            </ul>
        </div>
        <div class="nav-right col pull-right right-menu p-0">
            <ul class="nav-menus">
                <li class="onhover-dropdown p-0">
                    <button class="btn btn-primary-light" type="button"><a
                            href="{{ route('download-user-manual') }}"><i data-feather="book-open"></i>User
                            Manual</a></button>
                </li>
                <li><a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()"><i
                            data-feather="maximize"></i></a></li>
                <li class="onhover-dropdown p-0">
                    <button class="btn btn-primary-light" type="button" id="logout-button"><i
                            data-feather="log-out"></i>Log out</button>
                    <form id="logout-form" action="{{ route('logout') }}" method="GET" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
        <div class="d-lg-none mobile-toggle pull-right w-auto"><i data-feather="more-horizontal"></i></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('logout-button').addEventListener('click', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Apakah anda yakin ingin logout?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, yakin',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    });
</script>
