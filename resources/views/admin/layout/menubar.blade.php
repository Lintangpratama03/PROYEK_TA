@php
    $menu = menu_item();
    $modul = get_role();
    $uri_segement = Request::segment(1);
@endphp


<header class="main-nav">
    <div class="sidebar-user text-center"><a class="setting-primary" href="javascript:void(0)"><i
                data-feather="settings"></i></a>
        <img class="img-60 rounded-circle" src="{{ asset('assets') }}/images/dashboard/1.png" alt="">
        <div class="badge-bottom"><span class="badge badge-primary"></span></div><a href="user-profile.html">
            <h6 class="mt-3 f-14 f-w-600">{{ $sessions['nama'] }}</h6>
        </a>
        <p class="mb-0 font-roboto">{{ $sessions['displayed_group_name'] }}</p>
        <!-- <ul>
            <li><span><span class="counter">19.8</span>k</span>
                <p>Follow</p>
            </li>
            <li><span>2 year</span>
                <p>Experince</p>
            </li>
            <li><span><span class="counter">95.2</span>k</span>
                <p>Follower </p>
            </li>
        </ul> -->
    </div>
    <nav>
        <div class="main-navbar">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="mainnav">
                <ul class="nav-menu custom-scrollbar">
                    <li class="back-btn">
                        <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2"
                                aria-hidden="true"></i></div>
                    </li>
                    {{-- @php echo menu_create_limitless($menu, $modul, 'sidebar-menu', $uri_segement) @endphp --}}

                    <li class="sidebar-main-title">
                        <div>
                            <h6> Menu </h6>
                        </div>
                    </li>


                    {!! menu_create_limitless($menu, $modul, 'sidebar-menu', $uri_segement) !!}
                    {{-- 
                    <li><a class="nav-link menu-title link-nav" href="{{route('pad.index')}}"><i data-feather="pie-chart"></i><span>PAD</span></a></li>
                    <!-- <li class="dropdown"><a class="nav-link menu-title" href="javascript:void(0)"><i data-feather="activity"></i><span>PAD</span></a>
                        <ul class="nav-submenu menu-content">
                            <li><a href="{{route('pbb.penerimaan.index')}}">Target dan Realisasi PAD</a></li>
                            <li><a href="{{route('pbb.penerimaan.index')}}">Target dan Realisasi Pajak Daerah</a></li>
                            <li><a href="{{route('pbb.penerimaan.index')}}">Target dan Realisasi Retribusi Daerah</a></li>
                            <li><a href="{{route('pbb.penerimaan.index')}}">Komposisi PAD bedasarkan target</a></li>
                        </ul>
                    </li> --> --}}
                    {{-- 
                    <li class="dropdown">
                        <a class="nav-link menu-title" href="javascript:void(0)">
                            <i data-feather="home"></i><span>PBB</span>
                        </a>
                        <ul class="nav-submenu menu-content">
                            <li><a href="{{route('pbb.penerimaan.index')}}">Penerimaan</a></li>
                            <li><a href="{{route('pbb.tunggakan.index')}}">Tunggakan</a></li>
                            <!-- <li><a href="{{route('pbb.tempatbayar.index')}}">Tempat Bayar</a></li> -->
                            <li><a href="{{route('pbb.op.index')}}">Objek Pajak</a></li>
                        </ul>
                    </li>
                    <li class="dropdown"><a class="nav-link menu-title" href="javascript:void(0)"><i data-feather="archive"></i><span>PDL</span></a>
                        <ul class="nav-submenu menu-content">
                            <li><a href="{{route('pdl.penerimaan.index')}}">Penerimaan</a></li>
                            <li><a href="{{route('pdl.tunggakan.index')}}">Tunggakan</a></li>
                            <li><a href="{{route('pdl.pelaporan.index')}}">Pelaporan</a></li>
                            <li><a href="{{route('pdl.op.index')}}">Objek Pajak</a></li>
                        </ul>
                    </li>
                    <li class="dropdown"><a class="nav-link menu-title" href="javascript:void(0)"><i data-feather="file-text"></i><span>BPHTB</span></a>
                        <ul class="nav-submenu menu-content">
                            <li><a href="{{route('bphtb.penerimaan.index')}}">Penerimaan</a></li>
                            <li><a href="{{route('bphtb.op.index')}}">Objek Pajak</a></li>
                            <li><a href="{{route('bphtb.ketetapan.index')}}">Ketetapan</a></li>
                        </ul>
                    </li> 
                    --}}
                    <!-- <li class="dropdown"><a class="nav-link menu-title" href="javascript:void(0)"><i data-feather="activity"></i><span>RETRIBUSI</span></a>
                        <ul class="nav-submenu menu-content">
                            <li><a href="{{ route('retribusi.penerimaan.index') }}">Penerimaan</a></li>
                            <li><a href="{{ route('retribusi.op.index') }}">Objek Retribusi</a></li>
                        </ul>
                    </li> -->

                    {{-- <li><a class="nav-link menu-title link-nav" href="{{route('data.index')}}"><i data-feather="package"></i><span>DAFTAR DATA</span></a></li> --}}

                    <li><a class="nav-link menu-title link-nav" href="{{ route('logout') }}"><i
                                data-feather="log-out"></i><span>Logout</span></a></li>
                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>
