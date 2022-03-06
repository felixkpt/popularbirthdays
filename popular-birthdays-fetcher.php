<?php
use Goutte\Client;

use \Symfony\Component\DomCrawler\Crawler;
require_once (__DIR__ . "/fabpot_goutte/vendor/autoload.php");

class PopularBirthdaysSource
{

    public function getLinks($url) {

        $client = new Client();

        try {
            $crawler = $client->request('GET', $url);

        }
    catch (Exception $e) {
        return 'Caught exception: '. $e->getMessage(). "<br>";
        }

//        var_dump($crawler);
//die;

        $title = $crawler->filter('h3.page-title')->each(function ($node) {
            return $node->text();
        })[0];

        $title = trim(preg_replace("# Birthdays$#", '', $title));

        // Get the people post links
        $links = $crawler->filter('.people-list > .row > a')->each(function ($node) {
            $hrefs = [];
            // lets check if current node link doesnt exist in links array
            if (!in_array($node->attr('href'), $hrefs) && strlen($node->attr('href')) > 10)
            {
                return $hrefs[] = $node->attr('href');
            }
        });

        if (!$links){
            $links = $crawler->filter('.row > a.face.person-item.clearfix')->each(function ($node) {
                $hrefs = [];
                // lets check if current node link doesnt exist in links array
                if (!in_array($node->attr('href'), $hrefs) && strlen($node->attr('href')) > 10)
                {
                    return $hrefs[] = $node->attr('href');
                }
            });
        }


        shuffle($links);

        return ['links' => $links, 'title' => $title];

    }

    public function getImages($url) {

        $client = new Client();
        $crawler = $client->request('GET', $url);

        $_SESSION['crawler'] = $crawler;

        // Get the people post links

        $element_count = $crawler->filter('.famous-slider')->count();
        $element_contents = array();

        if ($element_count == 1) {
            return $images_links = $crawler->filter('.famous-slider img')->each(function ($node) {
                $srcs = [];
                // lets check if current node link doesnt exist in links array
                if (!in_array($node->attr('src'), $srcs))
                {
                    return $srcs[] = $node->attr('src');
                }
            });
        }else{
            $element_count = $crawler->filter('.col-sm-5.col-md-4.col-lg-4 .img1')->count();

            if ($element_count == 1){
                return $images_links = $crawler->filter('.col-sm-5.col-md-4.col-lg-4 .img1 img')->each(function ($node) {
                    $srcs = [];
                    // lets check if current node link doesnt exist in links array
                    if (!in_array($node->attr('src'), $srcs))
                    {
                        return $srcs[] = $node->attr('src');
                    }
                });
            }

            return 'No images found.';

        }


    }

    public function getMainInfo($url) {

        $client = new Client();
        $crawler = $_SESSION['crawler'] ?? $client->request('GET', $url);

        // Get the people post links

        $element_count = $crawler->filter('.main-info')->count();
        $element_contents = array();

        if ($element_count == 1) {
//            return $images_links = $crawler->filter('.main-info h1')->each(function ($node) {
//                $temps = [];
//                var_dump($node->html());
//                // lets check if current node link doesnt exist in links array
////                if (!in_array($node->attr('src'), $temps))
////                {
////                    return $temps[] = $node->attr('src');
////                }
//            });

            $name =  $crawler->filter('.main-info h1')->each(function ($node, $i) {

                return explode('<div', $node->html())[0];

            })[0];

            $title =  $crawler->filter('.main-info h1 div[class="person-title"]')->each(function ($node, $i) {

                if ($node->text())
                    return $node->text();
            })[0];

//            var_dump($name, $title);

            $stats =  $crawler->filter('.stats .main-stats')->each(function ($node, $i) {

                $stats =  new stdClass;

                $html = $node->html();

//                getting stats bio Dateofbirth, Birthplace, Age, Birth Sign
                $crawler2 = new Crawler($html);

                $stats_inner = $crawler2->filter('.stat.box')->each(function ($node, $i) {

                    $html = $node->html();

                    $crawler3 = new Crawler($html);
                    return $stats_inner = $crawler3->filter('a')->each(function ($node, $i) {
                        return $node->html();
                    });


                });

//                Getting DOB;
                $year = $month = $date = '';
                foreach ($stats_inner[0] as $stat) {

                    if (strlen($stat) < 10){
                        $year = $stat;
                    }else{
                        $crawler4 = new Crawler($stat);
                        // will remove all span nodes inside .second nodes
                        $month = $crawler4->filter('span')->each(function ($node) {
                            return $node->text();
                        })[0];

                        preg_match_all('!\d+!', $stat, $matches);

                        $date = $matches[0][0];
                    }

                }

                $month = date('m', strtotime($month));
                $date = $year.'-'.$month.'-'.$date;

                $stats->date_of_birth = date('Y-m-d', strtotime($date));
//var_dump($date,$stats->date_of_birth);die;

                //                Getting BIRTHPLACE;
                $city = $birth_place = '';
                foreach ($stats_inner[1] as $key => $stat) {

                    if ($key == 0){
                        $city = trim($stat);
                    }else{
                        $birth_place = trim($stat);
                    }
                }
//                var_dump($city, $birth_place);
                $stats->city = $city;
                $stats->birth_place = $birth_place;


                //                Getting BIRTH SIGN;
                $birth_sign = '';
                foreach ($stats_inner[3] as $key => $stat) {

                    $birth_sign = $stat;
                }
//                var_dump($birth_sign);
                $stats->birth_sign = $birth_sign;

                return $stats;

            })[0];

//            var_dump($stats);

//                Getting article headers
            $article_headers =  $crawler->filter('.row .bio')->each(function ($node, $i) {

                $html = $node->html();

                $crawler2 = new Crawler($html);

                return $crawler2->filter('h2')->each(function($node) {
                    return $node->text();
                });

            })[0];
//            var_dump($article_headers);die;


//            Getting article contents
            $article_content =  $crawler->filter('.row .bio')->each(function ($node, $i) {

                $html = $node->html();

                $crawler2 = new Crawler($html);

                return $crawler2->filter('p')->each(function($node) {
                    return wp_strip_all_tags($node->html());
                });

            })[0];


//if $article_headers && $article_content get $member_of

            if ($article_headers && $article_content) {

//                 Content headers modification
                $find = ['/About/ui', '/Before Fame/ui', '/Trivia/ui', '/Family Life/ui', '/Associated With/ui'];
                $replacement = ['About '.$name, 'Early life', 'Trivia', 'Family of '.$name, 'Close associates of '.$name];
                $article_headers = preg_replace($find, $replacement, $article_headers);

//                 Content Body modification
                require_once 'article-rewriter.php';
//                 $new_content = [];
//                 foreach ($article_content as $cont) {
//                     $new_content[] = article_rewriter($cont);
//                 }


            }

//             var_dump($article_headers);

            return ['name' => $name, 'title' => $title, 'stats' => $stats, 'article_headers' => $article_headers, 'article_content' => $article_content];

        }else{
            return 'Main info not found.';
        }

    }



}


