<?php
function custom_content() {

	global $post;
    global $wpdb;

    $horoscope_dates = [
        'Aquarius' => ['01-20','02-18'],
        'Pisces' => ['02-19','03-20'],
        'Aries' => ['03-21','04-19'],
        'Taurus' => ['04-20','05-20'],
        'Gemini' => ['05-21','06-20'],
        'Cancer' => ['06-21','07-22'],
        'Leo' => ['07-23','08-22'],
        'Virgo' => ['08-23','09-22'],
        'Libra' => ['09-23','10-22'],
        'Scorpio' => ['10-23','11-21'],
        'Sagittarius' => ['11-22','12-21'],
        'Capricorn' => ['12-22','01-19']
    ];

    $param1 = get_query_var( 'param1' );
    $horoscope = ucfirst(strtolower($param1));

    $horoscope_date = $horoscope_dates[$horoscope];
    $horoscope_date_min = $horoscope_date[0];
    $horoscope_date_max = $horoscope_date[1];

    $regexp = $horoscope_date_min."$";

    $items = @$wpdb->get_results("SELECT * from {$wpdb->prefix}birthday_stats where birth_sign like '$horoscope' order by id desc");

    (array) $arr = [];
    foreach ($items as $item){

        if (strlen($item->date_of_birth) > 6){
            $month_date = date('m-d', strtotime($item->date_of_birth));

//            if ($month_date >= $horoscope_date_min && $month_date <= $horoscope_date_max){
                $arr[] = $item;
//            }
        }

    }

    $title = ucfirst($param1) . ' birthdays';

    $limit = 50;
    $arr = json_decode(json_encode($arr), true);
            $arr = filter_trash($arr);
            $items = limit(order_by_key($arr, 'views'), $limit * 2);
    $items = add_attachments($items, $limit, 1);

    ob_start();
    ?>
<div class="container shadow-lg my-3">

    <div class="row px-2">
        <h2 id="horoscope-dates"><span class="color">Horoscope Dates</span> And Information</h2>

        <div class="col-12 p-1 bg-light"><a href="<?= site_url();  ?>/astrology/aries/">Aries</a> -  March 21 to April 19</div>
        <div class="col-12 p-1"><a href="<?= site_url();  ?>/astrology/taurus/">Taurus</a> - April 20 - May 20</div>
        <div class="col-12 p-1 bg-light"><a href="<?= site_url();  ?>/astrology/gemini/">Gemini</a> - May 21 - June 21</div>
        <div class="col-12 p-1"><a href="<?= site_url();  ?>/astrology/cancer/">Cancer</a> - June 21 - July 22</div>
        <div class="col-12 p-1 bg-light"><a href="<?= site_url();  ?>/astrology/leo/">Leo</a> - July 23 -August 22</div>
        <div class="col-12 p-1"><a href="<?= site_url();  ?>/astrology/virgo/">Virgo</a> - August 23 - September 22</div>
        <div class="col-12 p-1 bg-light"><a href="<?= site_url();  ?>/astrology/libra/">Libra</a> - September 23 - October 22</div>
        <div class="col-12 p-1"><a href="<?= site_url();  ?>/astrology/scorpio/">Scorpio</a> - October 23 - November 21</div>
        <div class="col-12 p-1 bg-light"><a href="<?= site_url();  ?>/astrology/sagittarius/">Sagittarius</a> - November 22 - December 21</div>
        <div class="col-12 p-1"><a href="<?= site_url();  ?>/astrology/capricorn/">Capricorn</a> - December 22 - January 19</div>
        <div class="col-12 p-1 bg-light"><a href="<?= site_url();  ?>/astrology/aquarius/">Aquarius</a> - January 20 - February 18</div>
        <div class="col-12 p-1"><a href="<?= site_url();  ?>/astrology/pisces/">Pisces</a> -  February 19- March 20</div>
    </div>

</div>
<?php
    $append = ob_get_clean();

    article(get_defined_vars(), $append);

}

include_once get_theme_file_path().'/single.php';
