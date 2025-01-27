<?php
require __DIR__ . '/vendor/autoload.php';
$date = jdate();
?>
<html lang="fa" dir="rtl">

<head>
    <style>
        body {
            font-family: Vazir !important;
            direction: rtl;
        }

        .news-link {
            border-style: solid;
            border-color: lightgray;
            border-width: 0px 0px 1px 0px;
            text-decoration: none;
        }

        .news-link:hover {
            color: lightblue;
        }

        @font-face {
            font-family: Vazir;
            src: url('./fonts/Vazir.eot');
            src: url('./fonts/Vazir.eot?#iefix') format('embedded-opentype'),
                url('./fonts/Vazir.woff') format('woff'),
                url('./fonts/Vazir.ttf') format('truetype');
            font-weight: normal;
        }

        @font-face {
            font-family: Vazir;
            src: url('./fonts/Vazir-Bold.eot');
            src: url('./fonts/Vazir-Bold.eot?#iefix') format('embedded-opentype'),
                url('./fonts/Vazir-Bold.woff') format('woff'),
                url('./fonts/Vazir-Bold.ttf') format('truetype');
            font-weight: bold;
        }

        @font-face {
            font-family: Vazir;
            src: url('./fonts/Vazir-Light.eot');
            src: url('./fonts/Vazir-Light.eot?#iefix') format('embedded-opentype'),
                url('./fonts/Vazir-Light.woff') format('woff'),
                url('./fonts/Vazir-Light.ttf') format('truetype');
            font-weight: 300;
        }
    </style>
</head>
<?php
$cat = $_GET['cat'] ?? 0;
define('CAT_GLOBAL', '0');
define('CAT_SPORT', '1');
$categories = [
    CAT_GLOBAL => 'عمومی',
    CAT_SPORT => 'ورزشی',
];
?>

<body>
    <form action="" method="get">
        <div class="input-group">
            
            <select name="cat" class="form-select form-select-lg">
                <?php
                foreach ($categories as $key => $value) {
                ?>
                    <option value="<?php echo $key; ?>" <?php if ($cat == $key) {
                                                            echo 'selected';
                                                        } ?>><?php echo $value; ?></option>
                <?php
                }
                ?>
            </select>
            <button type="submit" class="btn btn-secondary btn-lg">بارگذاری خبر</button>

        </div>
    </form>
    <?php
    function get_content($URL)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $URL);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    // var_dump($_GET);
    // var_dump($_REQUEST);
    // index array
    // associative array
    // format 1:
    // $cat = 0;
    // if(array_key_exists('cat', $_GET)) {
    //     $cat = $_GET['cat'];
    // }
    // format 2:
    // $cat = array_key_exists('cat', $_GET) ? $_GET['cat'] : 0;
    // format 3:
    // $cat = 0;
    // if(isset($_GET['cat'])) {
    //     $cat = $_GET['cat'];
    // }

    switch (intval($cat)) {
        case CAT_GLOBAL: // omoumi
            $rss_urls = [
                'مهر' => 'https://www.mehrnews.com/rss',
                'فارس' => 'https://www.farsnews.ir/rss',
                // 'تسنیم' => 'https://www.tasnimnews.com/fa/rss/feed/0/8/0/',
                // 'ایرنا' => 'https://www.irna.ir/rss',
                // 'تابناک' => 'https://www.tabnak.ir/fa/rss/1',
                // 'خبرفارسی' => 'https://khabarfarsi.com/rss/top',
                // 'https://www.yjc.news/fa/rss/allnews',
                // 'ایسنا' => 'https://www.isna.ir/rss',
                // 'خبرآنلاین' => 'https://www.khabaronline.ir/rss',
                // 'مشرق' => 'https://www.mashreghnews.ir/rss',
            ];
            break;
        case CAT_SPORT: // varzeshi
            $rss_urls = [
                'فارس' => 'https://www.farsnews.ir/rss/sports',
                'ایرنا' => 'https://www.irna.ir/rss/tp/14'
            ];
            break;
    }
    ?>
    <div class="m-3">
        <?php
        foreach ($rss_urls as $name => $rss_url) {
            $obj = simplexml_load_file($rss_url);
            $item = $obj->channel->item[0];
            $title = (string) $item->title;
            $link = (string) $item->link;
            
            $time = \Morilog\Jalali\CalendarUtils::strftime('l، Y/m/d H:i', strtotime($item->pubDate)); // 1395-02-19
            $time = \Morilog\Jalali\CalendarUtils::convertNumbers($time);
            $extra = \Morilog\Jalali\Jalalian::forge(strtotime($item->pubDate))->ago();
            $extra = \Morilog\Jalali\CalendarUtils::convertNumbers($extra);
            
            if ($title) {
        ?>
                <a target="_blank" title="<?php echo $time; ?>" href="<?php echo $link; ?>" class="news-link fs-2">
                    <?php echo $title; ?>
                </a> <span class="text-muted fs-4"><?php echo $extra; ?> | <?php echo $name; ?></span></br>
        <?php
            }
        }
        ?>
    </div>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.rtl.min.css" integrity="sha384-gXt9imSW0VcJVHezoNQsP+TNrjYXoGcrqBZJpry9zJt8PCQjobwmhMGaDHTASo9N" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>