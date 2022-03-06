<?php
//Custom functions by Felix Kiptoo Biwott
//@package sharasolutions.com

//Function to get the client IP address
function get_client_ip() {
    $ipaddress = '';
    if ( getenv ( 'HTTP_CLIENT_IP' ))
        $ipaddress = getenv ( 'HTTP_CLIENT_IP' );
    else if ( getenv ( 'HTTP_X_FORWARDED_FOR' ))
        $ipaddress = getenv ( 'HTTP_X_FORWARDED_FOR' );
    else if ( getenv ( 'HTTP_X_FORWARDED' ))
        $ipaddress = getenv ( 'HTTP_X_FORWARDED' );
    else if ( getenv ( 'HTTP_FORWARDED_FOR' ))
        $ipaddress = getenv ( 'HTTP_FORWARDED_FOR' );
    else if ( getenv ( 'HTTP_FORWARDED' ))
        $ipaddress = getenv ( 'HTTP_FORWARDED' );
    else if ( getenv ( 'REMOTE_ADDR' ))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}


function geoplugin() {

    global $wpdb;
    global $post;


    $client_ip = get_client_ip();
    // $exists = @json_decode(DB::table('geoplugin')->where('geoplugin_request', $client_ip)->get(), true)[0];
    $exists = null;

    if (!$exists) {

        $ip = get_client_ip();
        $ip = explode(',', $ip);

        if (count($ip) > 0) {
            $ip = $ip[0];
        }

        $xml = @file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip."");

        $xml = json_decode($xml, true) ?: [];

        foreach($xml as $k => $v)
        {

            $v = $v ? $v : null;
            $$k = $v;
        }



        if (@$geoplugin_request) {

            // $wpdb->insert(
            // "{$wpdb->prefix}geoplugin",
            // [
            // "geoplugin_request" => $geoplugin_request,
            // "geoplugin_city" => $geoplugin_city,
            // "geoplugin_region" => $geoplugin_region,
            // "geoplugin_regionCode" => $geoplugin_regionCode,
            // "geoplugin_regionName" => $geoplugin_regionName,
            // "geoplugin_areaCode" => $geoplugin_areaCode,
            // "geoplugin_dmaCode" => $geoplugin_dmaCode,
            // "geoplugin_countryCode" => $geoplugin_countryCode,
            // "geoplugin_countryName" => $geoplugin_countryName,
            // "geoplugin_continentCode" => $geoplugin_continentCode,
            // "geoplugin_continentName" => $geoplugin_continentName,
            // "geoplugin_latitude" => $geoplugin_latitude,
            // "geoplugin_longitude" => $geoplugin_longitude,
            // "geoplugin_timezone" => $geoplugin_timezone,
            // "geoplugin_currencyCode" => $geoplugin_currencyCode,
            // "geoplugin_currencySymbol" => $geoplugin_currencySymbol,
            // "geoplugin_currencySymbol_UTF8" => $geoplugin_currencySymbol_UTF8,
            // "geoplugin_currencyConverter" => $geoplugin_currencyConverter,

            // ]);


        }

    }

    return $xml;

}





function limit($arr, $from, $to = null) {


    if (!$to) {
        $to = $from;
        $from = 0;
    }

    if ($to == -1) {
        $to = count($arr);
    }

    $arr = array_values($arr);
    $sliced_array = array_slice($arr, $from, $to);


    return $sliced_array;

}



function order_by_key($array, $key, $order = null, $min = null){

// if array has no val for required sort column separate first then add to bottom
    $arr = [];
    $arr2 = [];
    foreach ($array as $k => $v) {
        if (strlen($array[$k][$key]) > 0) {
            $arr[] = $v;
        }else{
            $arr2[] = $v;
        }
    }
    $array = $arr;

    usort($array, callback($key, true)); //descending
    @usort($array, callback($key));  //sort elements of $array by key 'age' ascending


    if ($order == 'asc') {
        $array = array_reverse($array);
        $array = array_merge($array, $arr2);

    }else{
        $array = array_merge($array, $arr2);

    }

    if ($min && @is_numeric($array[0][$key])) {


        $arr = [];
        foreach ($array as $key2 => $value) {

            if ($value[$key] >= $min) {
                $arr[] = $value;
            }
        }

        $array = $arr;
    }



    return $array;

}

function callback($key, $desc = null){

    return $desc ?
        function($a, $b) use ($key) {

            return @($b[$key] + $a[$key]);
        } :
        function($a, $b) use ($key)  {
            return $a[$key] < $b[$key];
        };


}


function most_repeated_value($array)
{
    if (@is_array($array[0])) {
        throw new Exception("Error Processing Request", 1);

    }
    $counts = count($array);

    $vars = [];
    foreach ($array as $key => $value) {
        $this_var = "$value";

        if (!in_array($this_var, $vars)) {
            $vars[] = $this_var;
        }
    }

    $final_arr = [];
    foreach ($vars as $key => $value) {
        $res = 0;
        foreach ($array as $k => $v) {
            if ($v == $value) {
                $res ++;
            }
        }

        $final_arr[] = ['value' => $value, 'counts' => $res];

    }

    return order_by_key($final_arr, 'counts');

}


function uri($uri_input = null, $clean = null){


    $uri_input = preg_replace('#/+#', '/', $uri_input ?: $_SERVER['REQUEST_URI']);

    $parse = parse_url(site_url());
    $url = $parse["scheme"]."://".$parse["host"];
    $path = @$parse['path'];
    @$uri = explode($path, $uri_input, 2)[1] ?: $uri_input;

    if ($clean) {
        $uri = preg_split("#\/date\/#", preg_split("/\?.*|#.*/", $uri)[0])[0];
        // var_dump($uri);die;
    }

// if no uri just return uri_input or / as default
    if (!$uri_input) {
        $uri_input = '/';
    }

    return $uri ?: $uri_input;

}

function error_404() {
    global $wp_query;
    $wp_query->set_404();
    $wp_query->max_num_pages = 0;
}

function date_diff2($start_date, $end_date, $period = null) {

    $time_diff_in_mins = round(( strtotime(date('Y-m-d H:i:s', strtotime($end_date))) - strtotime(date('Y-m-d H:i:s', strtotime($start_date))) )  / 60, 2);

    if ($start_date > $end_date) {
        $time_diff_in_mins = - $time_diff_in_mins;
    }

    $time_diff_in_hours = $time_diff_in_mins / 60;
    $time_diff_in_days = $time_diff_in_hours / 24;
    $time_diff_in_years = $time_diff_in_days / 365;

    if ($period == 'minutes' || $period == 'mins') {
        return $time_diff_in_mins;
    }elseif ($period == 'hours' || $period == 'h') {
        return $time_diff_in_hours;
    }elseif ($period == 'years' || $period == 'y') {
        return $time_diff_in_years;
    }

    return $time_diff_in_days;

}

function number_format_strict($a, $b = ""){
    return number_format($a, $b ?: 0, '.', '');
}

function shortcode_parameters($options = null) {

    if (is_array($options)) {

// creating loop for vars from options
        foreach ($options as $key => $value) {

            // option set style with full quotes
            if (preg_match("#\"#", $value)) {
                $k = explode("=", $value);
                $val = @$k[1];
                // setting vars from options
                ${$k[0]} = null;
                if ($val) {
                    ${$k[0]} = preg_replace("#\"|'#", "", $val);
                }

            }

            // option set style with single or no quotes
            else{
                // setting vars from options
                ${$key} = null;
                if ($value) {
                    ${$key} = preg_replace("#\"|'#", "", $value);
                }
            }

        }
    }

    return get_defined_vars();

}

if (!function_exists('popular_birthdays_image_sizes')) {
    add_filter('intermediate_image_sizes_advanced', 'popular_birthdays_image_sizes');

    function popular_birthdays_image_sizes($sizes)
    {
        return array();
    }
}

function is_localhost(){
    if ($_SERVER['SERVER_NAME'] == 'localhost')
    {
        return true;
    }
    return false;
}

function redirect($url)
{
$string = '<script type="text/javascript">';
    $string .= 'window.location = "' . $url . '"';
    $string .= '</script>';

    echo $string;
}

if (!function_exists('now')){
    function now(){
        return date('Y-m-d H:i:s');
    }
}

function ip(){

    $ips = ['LqW23css.f4.fsa',
        'Cq23cRss.f4.fsa',
        'sq23cWs.f4.fsa',
        'w3css.f4.fsa',
        'JeWcss.f4.fsa',
        'sq2df4.fsa',
        'Eq23cs.5sddsf4.fsa',
        'Nq23cdef4.fsa',
        'sq23cs.EWfsa',
        'Nq23W.Hf4.fsa',
        'Xq23c.sKE.fsa',
        'Mq23c.sFF.F.EEEa',
        'Oq23c.ssWWWM.MKM',
        'Qq23cW.sWW.d3KM',
        'Uq23.cWsW.Wd.3Ks',
        'RqyIc.WsWWd.erIs',
        'Yq2rc.WNWWd.3KR',
        'Iq24tcWsLd.3Ks',
        'Uq23.cWAEFWd.3Ks',
        'IqFes.OPOPse.UUa'
    ];

    if (is_localhost()){
        shuffle($ips);
        $ip = $ips[0];


    }else{
        $ip = get_client_ip();
    }

    return $ip;
}