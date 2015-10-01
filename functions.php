<?php

define('TM_DIR', get_template_directory(__FILE__));
define('TM_URL', get_template_directory_uri(__FILE__));

require_once TM_DIR.'/parser.php';

function add_style()
{

}

function add_script()
{


}

add_action('wp_enqueue_scripts', 'add_style');
add_action('wp_enqueue_scripts', 'add_script');

wp_localize_script('jquery', 'myajax',
    array(
        'url' => admin_url('admin-ajax.php')
    )
);

//Стили для админки
function add_admin_style(){
    wp_enqueue_style( 'my-bootstrap-style', get_template_directory_uri() . '/css/bootstrap.css', array(), '1');
    wp_enqueue_style( 'my-admin-style', get_template_directory_uri() . '/css/admin_style.css', array(), '1');
    wp_enqueue_script( 'jq', get_template_directory_uri() .'/js/jquery-2.1.4.min.js', array(), '1');
    wp_enqueue_script( 'my-bootstrap-script', get_template_directory_uri() . '/js/bootstrap.js', array(), '1');
    wp_enqueue_script( 'my-admin-script', get_template_directory_uri() . '/js/admin.js', array(), '1');
}

add_action( 'admin_enqueue_scripts', 'add_admin_style' );

function admin_menu(){
    add_menu_page( 'Настройка главного блока', 'Главный блок', 'manage_options', 'mainpage', 'mainpage' );
}
add_action('admin_menu', 'admin_menu');

//ЗАГРУЗИТЬ УЖЕ ПОЛУЧЕННЫЕ БАННЕРА
function mainpage(){
    global $wpdb;

    if (function_exists('wp_enqueue_media')) {
        wp_enqueue_media();
    } else {
        wp_enqueue_style('thickbox');
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
    }

    // получаем данные о всех баннерах
    $slide = getDataFromDb("mainpageslides");
    $topbanner = getDataFromDb("topbanner");
    $botbanner = getDataFromDb("botbanner");
    $leftbanner = getDataFromDb("leftbanner");
    $rightbanner = getDataFromDb("rightbanner");
    $bigbanner = getDataFromDb("bigbanner");

    //формируем блоки с заполненными данными
    //слайды
    $slides = "";
    foreach($slide as $item){

        $slides .= ' <div class="col-lg-12 slide" data-num="'.$item['id'].'">
                            <p>
                                <input type="text" placeholder="Ссылка на событие" name="slide-link" class="slide-link" value="'.$item['link'].'">
                            </p>

                            <p>
                                <button class="btn btn-info media-upload">Выбрать изображение</button>
                                <img src="'.$item['img'].'" alt="" class="media">
                                <input type="hidden" class="media-img" name="slide-img" value="'.$item['img'].'">
                            </p>
                            <p>
                                <button class="btn btn-success save-slide">Сохранить слайд</button>
                                <button class="btn btn-warning add-slide">Добавить слайд</button>';

                                 if($item['id'] != 1){
                                     $slides .= '  <button class="btn btn-danger del-slide" data-num="'.$item['id'].'">Удалить слайд</button> ';
                                 }
        $slides .= '   </p>
                        </div>';
    }
    //верхний баннер
    $topbanners = '<div class="col-lg-12 top-banner">
                            <p>
                                <input type="text" placeholder="Ссылка на событие" name="top-banner-link" value="'.$topbanner[0]['link'].'">
                            </p>

                            <p>
                                <button class="btn btn-info media-upload">Выбрать изображение</button>
                                <img src="'.$topbanner[0]['img'].'" alt="" class="media">
                                <input type="hidden" name="top-banner-img" class="media-img" value="'.$topbanner[0]['img'].'">
                            </p>

                            <p>
                                <button class="btn btn-success save-top-banner">Сохранить</button>
                            </p>
                        </div>';
    //нижний баннер
    $botbanners = '<div class="col-lg-12 bot-banner">
                            <p>
                                <input type="text" placeholder="Ссылка на событие" name="bot-banner-link" value="'.$botbanner[0]['link'].'">
                            </p>

                            <p>
                                <button class="btn btn-info media-upload">Выбрать изображение</button>
                                <img src="'.$botbanner[0]['img'].'" alt="" class="media">
                                <input type="hidden" name="bot-banner-img" class="media-img" value="'.$botbanner[0]['img'].'">
                            </p>

                            <p>
                                <button class="btn btn-success save-bot-banner">Сохранить</button>
                            </p>
                        </div>';
    //левый баннер
    $leftbanners = '<div class="col-lg-12 left-banner">
                            <p>
                                <input type="text" placeholder="Ссылка на событие" name="left-banner-link" value="'.$leftbanner[0]['link'].'">
                            </p>

                            <p>
                                <button class="btn btn-info media-upload">Выбрать изображение</button>
                                <img src="'.$leftbanner[0]['img'].'" alt="" class="media">
                                <input type="hidden" name="left-banner-img" class="media-img" value="'.$leftbanner[0]['img'].'">
                            </p>

                            <p>
                                <button class="btn btn-success save-left-banner">Сохранить</button>
                            </p>
                        </div>';
    //правый баннер
    $rightbanners = '<div class="col-lg-12 right-banner">
                            <p>
                                <input type="text" placeholder="Ссылка на событие" name="right-banner-link" value="'.$rightbanner[0]['link'].'">
                            </p>

                            <p>
                                <button class="btn btn-info media-upload">Выбрать изображение</button>
                                <img src="'.$rightbanner[0]['img'].'" alt="" class="media">
                                <input type="hidden" name="right-banner-img" class="media-img" value="'.$rightbanner[0]['img'].'">
                            </p>

                            <p>
                                <button class="btn btn-success save-right-banner">Сохранить</button>
                            </p>
                        </div>';
    //Баннер
    $bigbanners = '<div class="col-lg-12 big-banner">
                            <p>
                                <input type="text" placeholder="Ссылка на событие" name="big-banner-link" value="'.$bigbanner[0]['link'].'">
                            </p>

                            <p>
                                <button class="btn btn-info media-upload">Выбрать изображение</button>
                                <img src="'.$bigbanner[0]['img'].'" alt="" class="media">
                                <input type="hidden" name="big-banner-img" class="media-img" value="'.$bigbanner[0]['img'].'">
                            </p>

                            <p>
                                <button class="btn btn-success save-big-banner">Сохранить</button>
                            </p>
                        </div>';

    $parser = new Parser();
    $parser->parse(TM_DIR."/views/mainpage.php",array('template_url'=> get_template_directory_uri(),
        'slides' => $slides,
        'top' => $topbanners,
        'bot' => $botbanners,
        'left' => $leftbanners,
        'right' => $rightbanners,
        'big' => $bigbanners,
        ), true);
}

function prn($content)
{
    echo '<pre style="background: lightgray; border: 1px solid black; padding: 2px">';
    print_r($content);
    echo '</pre>';
}

function my_pagenavi()
{
    global $wp_query;
    $big = 999999999; // уникальное число для замены
    $args = array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big))
    , 'format' => ''
    , 'current' => max(1, get_query_var('paged'))
    , 'total' => $wp_query->max_num_pages
    );
    $result = paginate_links($args);
    // удаляем добавку к пагинации для первой страницы
    $result = str_replace('/page/1/', '', $result);
    echo $result;
}

function excerpt_readmore($more)
{
    return '... <br><a href="' . get_permalink($post->ID) . '" class="readmore">' . 'Читать далее' . '</a>';
}

add_filter('excerpt_more', 'excerpt_readmore');
if (function_exists('add_theme_support'))
    add_theme_support('post-thumbnails');

add_action('wp_ajax_choose_main', 'choose_main');
add_action('wp_ajax_load_main', 'load_main');
add_action('wp_ajax_save_slide', 'save_slide');
add_action('wp_ajax_update_slide', 'update_slide');
add_action('wp_ajax_delete_slide', 'delete_slide');
add_action('wp_ajax_save_top_banner', 'save_top_banner');
add_action('wp_ajax_save_bot_banner', 'save_bot_banner');
add_action('wp_ajax_save_left_banner', 'save_left_banner');
add_action('wp_ajax_save_right_banner', 'save_right_banner');
add_action('wp_ajax_save_big_banner', 'save_big_banner');

function choose_main(){
    global $wpdb;

    if(isset($_POST['num']) && !empty($_POST['num'])){
        $num = $_POST['num'];
        $wpdb->update('loginpage',array('num' => $num),array('id' => 1));
    }

    return load_main();
    die();
}

//сохранение нового слайде
function save_slide(){
    global $wpdb;

    if(!empty($_POST['link']) && !empty($_POST['img'])){
        $wpdb->insert('mainpageslides', array('link' => $_POST['link'], 'img' => $_POST['img']));
    }
    die();
}

//обновление слайда
function update_slide(){
    global $wpdb;
    //prn($_POST);
    if(!empty($_POST['link']) && !empty($_POST['img'])){
        $wpdb->update('mainpageslides', array('link' => $_POST['link'], 'img' => $_POST['img']), array('id' => $_POST['num']));
    }
    die();
}

//Удаление слайда
function delete_slide(){
    global $wpdb;
    //prn($_POST);
    $wpdb->delete('mainpageslides', array('id' => $_POST['num']));
    die();
}

//Текущий блок на странице входа
function load_main(){
    global $wpdb;
    $current_num = $wpdb->get_results("SELECT * FROM `loginpage` WHERE id = 1");
   // prn( $current_num[0]->num);
    echo $current_num[0]->num;
    die();
}

//сохранение верхнего баннера
function save_top_banner(){
    global $wpdb;

    if(!empty($_POST['link']) && !empty($_POST['img'])){
        $wpdb->update('topbanner',array('link' => $_POST['link'], 'img' => $_POST['img']),array('id' => 1));
    }
    die();
}

//сохранение нижнего баннера
function save_bot_banner(){
    global $wpdb;

    if(!empty($_POST['link']) && !empty($_POST['img'])){
        $wpdb->update('botbanner',array('link' => $_POST['link'], 'img' => $_POST['img']),array('id' => 1));
    }
    die();
}

//сохранение левого баннера
function save_left_banner(){
    global $wpdb;

    if(!empty($_POST['link']) && !empty($_POST['img'])){
        $wpdb->update('leftbanner',array('link' => $_POST['link'], 'img' => $_POST['img']),array('id' => 1));
    }
    die();
}

//сохранение правого баннера
function save_right_banner(){
    global $wpdb;

    if(!empty($_POST['link']) && !empty($_POST['img'])){
        $wpdb->update('rightbanner',array('link' => $_POST['link'], 'img' => $_POST['img']),array('id' => 1));
    }
    die();
}

//сохранение баннера
function save_big_banner(){
    global $wpdb;

    if(!empty($_POST['link']) && !empty($_POST['img'])){
        $wpdb->update('bigbanner',array('link' => $_POST['link'], 'img' => $_POST['img']),array('id' => 1));
    }
    die();
}

//получение всех изображений по названию таблицы
function getDataFromDb($tableName){
    global $wpdb;

    $data = $wpdb->get_results("SELECT * FROM `$tableName`", ARRAY_A);

    return $data;
}