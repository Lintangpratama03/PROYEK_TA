<?php

// use Session;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

function getSession()
{

    $result = Session::get("user_app");

    return $result;
}

function getOldSession()
{

    $result = Session::get("old_user_app");

    return $result;
}

function get_role()
{
    $session = Session::get('user_app');
    $id_grup = decrypt($session['group_id']);
    $master_menu = DB::table("auth.menu")->get();
    $get_modul = DB::table("auth.user_menu")->where("id_group", $id_grup)->get();

    $master_menu = collect($master_menu)->map(function ($x) {
        return (array) $x;
    })->toArray();
    $get_modul = collect($get_modul)->map(function ($x) {
        return (array) $x;
    })->toArray();
    if (sizeof($get_modul) > 0) {
        $idModul = array_reduce($get_modul, function ($carry, $item) {
            $carry[] = $item['child_id'];
            return $carry;
        });
    } else {
        $idModul = [];
    }

    $modul = [];
    foreach ($master_menu as $key => $value) {
        $modul["MENU_" . $value['id']] = in_array($value['id'], $idModul) ? '11111' : '00000';
    }
    //print_r($idModul);exit();
    // $this->session->set_userdata('modul', $modul);
    return $modul;
}

function get_url_akses()
{
    $session =  Session::get("user_app");
    $urlFull = url()->full();
    $baseUrl = url('/');
    $urlPath = ltrim(str_replace($baseUrl, '', $urlFull), '/');
    $state = false;

    $user_group = DB::table('auth.user_group')->where('nama_group', $session['group_name'])->first();
    $d_akses = DB::table("auth.user_menu AS um")->join("auth.menu AS m1", function ($join) {
        $join->on("m1.id", "um.child_id")
            ->on("m1.parent", "um.parent_id");
    })->leftjoin("auth.menu AS m2", "m2.id", "m1.parent")
        ->where("id_group", $user_group->id)
        ->where("m1.url", $urlPath)
        ->orderby("m1.id", "asc")
        ->select(DB::raw("um.*, m1.name, m1.icon, m2.name AS parent_menu,m1.url"));

    if (is_null($d_akses->first())) {
        $state = true;
        return $state;
    }

    return $state;
}

function get_akses($url)
{
    $session = Session::get('user_app');
    $id = decrypt($session['group_id']);

    $d_akses = DB::table("auth.user_menu AS um")->join("auth.menu AS m1", function ($join) {
        $join->on("m1.id", "um.child_id")
            ->on("m1.parent", "um.parent_id");
    })->leftjoin("auth.menu AS m2", "m2.id", "m1.parent")
        ->where("id_group", $id)
        ->where("m1.url", $url)
        ->orderby("m1.id", "asc")
        ->select(DB::raw("um.*, m1.name, m1.icon, m2.name AS parent_menu"));
    $count = $d_akses->get()->count();

    return $count;
}

function exclude_menu()
{
    $arr = ["profil"];

    return $arr;
}

function menu_item()
{
    $data = DB::table("auth.menu")->orderby("auth.menu.urutan")->orderby("auth.menu.parent")->orderby("auth.menu.id")->select("auth.menu.*")->get();
    // hold all references
    $ref = [];
    // hold all menu items
    $items = [];

    // loop over the result
    foreach ($data as $key => $value) {
        // Assign by reference
        $this_ref = &$ref[$value->id];

        // add the menu parent
        $this_ref['id']         = $value->id;
        $this_ref['url']         = ($value->url != "" && $value->url != null) ? $value->url : "#";
        $this_ref['name']         = $value->name;
        $this_ref['icon']         = $value->icon;
        $this_ref['parent']     = $value->parent;
        $this_ref['tipe_site']     = '1';
        $this_ref['urutan']     = $value->urutan;
        $this_ref['modul']         = "MENU_" . $value->id;

        // if there is no parent push it into items array()
        if ($value->parent == 0) {
            $items[$value->id] = &$this_ref;
        } else {
            $ref[$value->parent]['child'][$value->id] = &$this_ref;
        }
    }

    return $items;
}

function menu_create_limitless($items, $modul, $class = 'sidebar-menu', $segment)
{
    $html = "";
    foreach ($items as $key => $value) {
        if ((isset($modul[$value['modul']]) && preg_match("/1\w{3}/", $modul[$value['modul']], $output_array))) {

            if (array_key_exists('child', $value)) {
                $html .= "";
            }

            $a_styled = "";
            if (strtolower($segment) === strtolower($value['name'])) {
                $a_styled .= "color : #FFF; background : #1e282c";
            }
            $url = ($value['url'] == "#") ? 'javascript:void(0)' : url($value['url']);
            $html .= "
            <li class='dropdown'>
                <a class='nav-link menu-title' href='" . $url . "'>
                    <i data-feather='$value[icon]'></i><span>$value[name]</span>";


            if (array_key_exists('child', $value)) {
                $html .= "<span class='site-menu-arrow'></span> 
                        </a>
                        <div class='nav-submenu menu-content'>
                            <div class='site-menu-scroll-wrap is-list'>
                                <div>
                                    <div>
                                        <ul class='site-menu-sub site-menu-normal-list'>";

                $html .= menu_create_limitless($value['child'], $modul, 'treeview-menu', $segment);
                $html .= "</a></ul>";
            }

            $html .= "</a></li>";
        }
    }

    // dd($html);

    return $html;
}

function get_menu()
{
    $d_data = DB::table("auth.menu as menu")->leftjoin("auth.menu as parent", "parent.id", "menu.parent")
        ->select(DB::raw("menu.*, parent.name AS parent_menu"));
    return $d_data;
}

function _menuselect($id)
{
    $d_menu = DB::table("auth.user_menu")->where("id_group", $id)->pluck("child_id")->toArray();
    // dd($d_menu);
    $d_data = DB::table("auth.menu")->whereNotIn("id", $d_menu)->get();

    $arr = array();
    $arr[] .= "<option value=''>-- Pilih --</option>";
    foreach ($d_data as $d) {
        $arr[] = "<option value='" . $d->id . "'>" . $d->name . ' [ ' . $d->url . ' ]' . "</option>";
    }
    return $arr;
}

function _retribusiselect($id)
{
    $d_menu = DB::table("data.retribusi_opd")->where("id_opd", $id)->pluck("id_retribusi")->toArray();
    //dd($d_menu);
    $d_data = DB::table("data.retribusi")->whereNotIn("id", $d_menu)->get();

    $arr = array();
    $arr[] .= "<option value=''>-- Pilih --</option>";
    foreach ($d_data as $d) {
        $arr[] = "<option value='" . $d->id . "'>" . $d->nama_retribusi . "</option>";
    }
    return $arr;
}

function arrayOfRole()
{
    $roles = array(
        "1" => "superadmin",
        "2" => "wp",
        "3" => "bendahara",
        "4" => "ppat",
        "5" => "bphtb",
        "6" => "peneliti_bphtb",
        "7" => "kabid",
        "8" => "waris"
    );
    return $roles;
}

function WhoAmI($roleCode)
{
    $data = arrayOfRole();
    return isset($data[$roleCode]) ? $data[$roleCode] : null;
}

function RoleId()
{
    $sessions = getSession();
    return decrypt($sessions["group_id"]);
}

function WpId()
{
    $sessions = getSession();
    return decrypt($sessions["id_wp"]);
}

function getNamaHari($id)
{
    $day = [
        "0" => "Minggu",
        "1" => "Senin",
        "2" => "Selasa",
        "3" => "Rabu",
        "4" => "Kamis",
        "5" => "Jumat",
        "6" => "Sabtu"
    ];
    return isset($day[$id]) ? $day[$id] : null;
}

function tgl_full($tgl, $jenis)
{
    $hari_h = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu");
    $tg = date("d", strtotime($tgl));
    $bln = date("m", strtotime($tgl));
    $bln2 = date("m", strtotime($tgl));
    $thn = date("Y", strtotime($tgl));
    $thn2 = date("y", strtotime($tgl));
    $bln_h = array('01' => "Januari", "02" => "Februari", "03" => "Maret", "04" => "April", "05" => "Mei", "06" => "Juni", "07" => "Juli", "08" => "Agustus", "09" => "September", "10" => "Oktober", "11" => "November", "12" => "Desember");
    $bln = $bln_h[$bln];
    $hari = $hari_h[date("w", strtotime($tgl))];

    $jam = date('H');
    $menit = date('i');
    $detik = date('s');

    $get_jam = date("H", strtotime($tgl));
    $get_menit = date("i", strtotime($tgl));
    $get_detik = date("s", strtotime($tgl));

    $zero_jam = '00';
    $zero_menit = '00';
    $zero_detik = '00';

    if ($jenis == '0') {
        $print = $tg . ' ' . $bln . ' ' . $thn;
    } elseif ($jenis == '1') {
        $print = $hari . ', ' . $tg . ' ' . $bln . ' ' . $thn;
    } elseif ($jenis == '2') {
        $print = $thn . '-' . $bln2 . '-' . $tg;
    } elseif ($jenis == '3') {
        $print = $tg . "/" . $bln2;
    } elseif ($jenis == '4') {
        $print = strtotime($tgl);
    } elseif ($jenis == '5') {
        $print = $thn . "-" . $bln2 . "-" . $tg . " " . $jam . ":" . $menit . ":" . $detik;
    } elseif ($jenis == '6') {
        $print = $thn . "-" . $bln2 . "-" . $tg . " " . $get_jam . ":" . $get_menit . ":" . $get_detik;
    } elseif ($jenis == '7') {
        $print = $thn . "-" . $bln2 . "-" . $tg . " " . $zero_jam . ":" . $zero_menit . ":" . $zero_detik;
    } elseif ($jenis == '98') {
        $print = $tg . "-" . $bln2 . "-" . $thn;
    } elseif ($jenis == '99') {
        $print = $thn . "-" . $bln2 . "-" . $tg;
    } elseif ($jenis == 'hari') {
        $print = $hari;
    } elseif ($jenis == 'bulan') {
        $print = $bln2;
    } elseif ($jenis == '8') {
        $print = $tg . "-" . $bln2 . "-" . $thn . " " . $get_jam . ":" . $get_menit . ":" . $get_detik;
    } elseif ($jenis == '10') {
        $print = $tg . '/' . $bln2 . '/' . $thn . ' ' . $jam . ':' . $menit;
    } elseif ($jenis == '11') {
        $print = $thn . '/' . $bln2 . '/' . $tg;
    } elseif ($jenis == '13') {
        $print = $get_jam . ":" . $get_menit;
    } elseif ($jenis == '14') {
        $print = $tg . '/' . $bln2 . '/' . $thn . ' ' . $get_jam . ":" . $get_menit;
    } elseif ($jenis == '15') {
        $print = $tg . $bln2 . $thn2;
    } elseif ($jenis == '16') {
        $print = $tg . " " . $bln . " " . $thn . " " . $get_jam . ":" . $get_menit . ":" . $get_detik;
    } else {
        $print = $tg . '-' . $bln2 . '-' . $thn;
    }
    return $print;
}

function angka_koma($string)
{
    $str = str_replace(",", ".", $string);
    return $str;
}

function getRawQuery($builder)
{
    $query = str_replace(array('?'), array('\'%s\''), $builder->toSql());
    $query = vsprintf($query, $builder->getBindings());

    $query = str_replace("\t", "", $query);
    $query = str_replace("\n", "", $query);

    return $query;
}

function sendWa($nomor_wa, $message)
{
    // $nomor_wa = "085156864654";
    // $message = "hello there";
    // dd($nomor_wa);
    $curl = curl_init();
    $token = "Dyrk02gvqcl7GKFbykDGHyALtaIb2XSfFl8OyBOxw7vlnFu7qTzPcewsyopZQEgi";
    $random = true;
    $payload = [
        "data" => [
            [
                'phone' => $nomor_wa,
                'message' => $message,
            ]
        ]
    ];
    curl_setopt(
        $curl,
        CURLOPT_HTTPHEADER,
        array(
            "Authorization: $token",
            "Content-Type: application/json"
        )
    );
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($curl, CURLOPT_URL,  "https://jogja.wablas.com/api/v2/send-message");
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

    $result = curl_exec($curl);
    $result = json_decode($result);

    if (isset($result->status)) {
        $status = $result->status;
    } else {
        $status = false;
    }

    return $status;
}

function terbilang($nilai, $lang = "ID")
{
    $result = "";
    $a = "";
    if ($lang == "ID") {
        if ($nilai < 0) {
            return 'minus ' . terbilang(-$nilai, "ID");
        }

        $nilai = abs($nilai);
        $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " " . $huruf[$nilai];
        } else if ($nilai < 20) {
            $temp = terbilang($nilai - 10, "ID") . " belas";
        } else if ($nilai < 100) {
            $temp = terbilang($nilai / 10, "ID") . " puluh" . terbilang($nilai % 10, "ID");
        } else if ($nilai < 200) {
            $temp = " seratus" . terbilang($nilai - 100, "ID");
        } else if ($nilai < 1000) {
            $temp = terbilang($nilai / 100, "ID") . " ratus" . terbilang($nilai % 100, "ID");
        } else if ($nilai < 2000) {
            $temp = "seribu" . terbilang($nilai - 1000, "ID");
        } else if ($nilai < 1000000) {
            $temp = terbilang($nilai / 1000, "ID") . " ribu" . terbilang($nilai % 1000, "ID");
        } else if ($nilai < 1000000000) {
            $temp = terbilang($nilai / 1000000, "ID") . " juta" . terbilang($nilai % 1000000, "ID");
        } else if ($nilai < 1000000000000) {
            $temp = terbilang($nilai / 1000000000, "ID") . " miliar" . terbilang(fmod($nilai, 1000000000), "ID");
        } else if ($nilai < 1000000000000000) {
            $temp = terbilang($nilai / 1000000000000, "ID") . " triliun" . terbilang(fmod($nilai, 1000000000000), "ID");
        }
        $result = $temp;

        return $result;
    } else {
        // $hyphen      = '-';
        $hyphen      = ' ';
        // $conjunction = ' and ';
        $conjunction = ' ';
        $separator   = ' ';
        $negative    = 'minus ';
        $decimal     = ' point ';
        $dictionary  = array(
            0                   => 'zero',
            1                   => 'one',
            2                   => 'two',
            3                   => 'three',
            4                   => 'four',
            5                   => 'five',
            6                   => 'six',
            7                   => 'seven',
            8                   => 'eight',
            9                   => 'nine',
            10                  => 'ten',
            11                  => 'eleven',
            12                  => 'twelve',
            13                  => 'thirteen',
            14                  => 'fourteen',
            15                  => 'fifteen',
            16                  => 'sixteen',
            17                  => 'seventeen',
            18                  => 'eighteen',
            19                  => 'nineteen',
            20                  => 'twenty',
            30                  => 'thirty',
            40                  => 'fourty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand',
            1000000             => 'million',
            1000000000          => 'billion',
            1000000000000       => 'trillion',
            1000000000000000    => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($nilai)) {
            $result = '';
            return $result;
        }

        if ($nilai < 0) {
            return 'minus ' . terbilang(-$nilai, "EN");
        }

        if (($nilai >= 0 && (int) $nilai < 0) || (int) $nilai < 0 - PHP_INT_MAX) {
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            $result = '';
            return $result;
        }

        if ($nilai < 0) {
            $result = $negative . terbilang(abs($nilai), "EN");
        }

        $string = $fraction = null;
        if (strpos($nilai, '.') !== false) {
            list($nilai, $fraction) = explode('.', $nilai);
        }

        switch (true) {
            case $nilai < 21:
                $string = $dictionary[$nilai];
                break;
            case $nilai < 100:
                $tens   = ((int) ($nilai / 10)) * 10;
                $units  = $nilai % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $nilai < 1000:
                $hundreds  = $nilai / 100;
                $remainder = $nilai % 100;
                $string    = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . terbilang($remainder, "EN");
                }
                break;
            default:
                $baseUnit     = pow(1000, floor(log($nilai, 1000)));
                $numBaseUnits = (int) ($nilai / $baseUnit);
                $remainder    = $nilai % $baseUnit;
                $string       = terbilang($numBaseUnits, "EN") . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= terbilang($remainder, "EN");
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        $result = $string;

        return $result;
    }
}

function replaceformatRP(String $str)
{
    $cleanStr = str_replace('.', '', $str);
    $cleanStr = str_replace('Rp ', '', $cleanStr);
    $cleanStr = str_replace(',', '.', $cleanStr);
    $cleanStr = round($cleanStr);
    return $cleanStr;
}

function nullableVal($val)
{
    if ($val == null || $val == 'undefined' || $val == 'null' || $val == '')
        return null;
    return $val;
}

function convertDateToDbFormat($orgDate)
{
    $orgDate = str_replace('/', '-', $orgDate);
    $newDate = date("Y-m-d", strtotime($orgDate));
    return $newDate;
}

function getJatuhTempo()
{
    $currentDate = Carbon::now();
    $nextMonth = $currentDate->addMonths(1)->endOfMonth();
    $lastDateNextMonth = $nextMonth->format('Y-m-d');

    return $lastDateNextMonth;
}

function fileExtensionCheck($type)
{
    $type = strtolower($type);
    $arr_type = [
        "png" => true,
        "jpg" => true,
        "jpeg" => true,
        "pdf" => true,
    ];

    if (isset($arr_type[$type])) {
        $result = $arr_type[$type];
    } else {
        $result = false;
    }

    return $result;
}
