<?php
/**
* Plugin Name: CHFS Elementor Templates
* Plugin URI: https://creativeheads.no/
* Description: CHFS Elementor Templates is all in one solution for complete  sites, single page templates, blocks & images. This plugin offers the  library of ready templates & provides quick access to beautiful Pixabay images that can be imported in your website easily.
* Version: 3.0.17
* Author: Faisal Khan
* Author URI: https://creativeheads.no/
* Text Domain: CHFS_elementor_temp_library
*
* @package CHFS Elementor Templates
*/
/**
* Set constants.
*/
defined('ABSPATH') || die("You Can't Access this File Directly");
define('CHFS_PATH', plugin_dir_path(__FILE__));
define('CHFS_URL', plugin_dir_url(__FILE__));
define('CHFS_FILE', __FILE__);
define('CHFS_SITE_URL',get_site_url());
define('CHFS_API_URL','http://127.0.0.1:8000');
define('CHFS_API_File_Path','http://localhost/elementoradd/api');
function CHFS_getToken(){
$post = wp_remote_retrieve_body(wp_remote_post(CHFS_API_URL.'/api/login', [
'body' => [
'email' =>esc_html(get_option('CHFS_Email')),
'password' =>esc_html(get_option('CHFS_Password')),

],
'method' => 'POST',
'content-type' => 'application/json',
]));
$result=json_decode($post);

if (isset($result)) {

    if(isset($result->error)){
    return false;
    }else{  
   return $result->token;
    }

}else{

return false;
}
}

 if ( ! session_id() ) {
        session_start();

        $_SESSION["CHFS_TOKEN"]=CHFS_getToken();
    }



if (isset($_SESSION["CHFS_TOKEN"]) && !empty($_SESSION["CHFS_TOKEN"]) && $_SESSION["CHFS_TOKEN"]!=false) {
    define('CHFS_TOKEN',$_SESSION["CHFS_TOKEN"]);
}else{
 $_SESSION["CHFS_TOKEN"]=CHFS_getToken();
 define('CHFS_TOKEN',$_SESSION["CHFS_TOKEN"]);

}




add_action('wp_enqueue_scripts','wp_enqueue_scripts_CHFS');
add_action('admin_enqueue_scripts','admin_enqueue_scripts_CHFS');



function wp_enqueue_scripts_CHFS(){
wp_enqueue_script('jquery');
wp_enqueue_style('pwspk_dev_plugin', CHFS_URL."assets/css/style.css");
wp_enqueue_script('pwspk_dev_script', CHFS_URL."assets/js/custom.js", array(), '1.0.0', false);
wp_localize_script('pwspk_dev_script', 'ajax_object', array(
'ajaxurl'=> admin_url('admin-ajax.php'),
'CHFS_api_url'=>CHFS_API_URL,
'CHFS_TOKEN'=>CHFS_TOKEN,
'CHFS_API_File_Path'=>CHFS_API_File_Path,
)
);
}
function admin_enqueue_scripts_CHFS(){
wp_enqueue_style('pwspk_dev_plugin', CHFS_URL."assets/css/style.css");
}
// add_action('elementor/editor/before_enqueue_scripts', function() {
//     wp_enqueue_style('pwspk_dev_plugin', CHFS_URL."assets/css/style.css");
//     wp_enqueue_script('pwspk_dev_script', CHFS_URL."assets/js/custom.js");
// });
require_once ABSPATH . 'wp-admin/includes/import.php';
if ( ! class_exists( 'WP_Importer' ) ) {
$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
if ( file_exists( $class_wp_importer ) ) {
require_once $class_wp_importer;
}
}     
add_action('admin_menu', 'plugin_menu_chfs');
add_action('admin_menu', 'process_form_settings_chfs');
function plugin_menu_chfs(){
add_menu_page('CHFS Elementor Templates', 'CHFS Elementor Templates', 'manage_options', 'CHFS_elementor_temp_library','options_func_chfs', $icon_url = '', $position = null);
}
function options_func_chfs(){ ?>
<div class="wrap">  
    <h1>CHFS Elementor Templates</h1>
    <?php settings_errors(); ?>
    <div class="CHFSrow">
        
        <div class="CHFScol-sm-6">
            
            <form id="ajax_form" action="options.php" method="post" enctype="multipart/form-data">
                <?php settings_fields('CHFS_option_group'); ?>
                <input type="hidden" name="plugin_set_CHFS" value="plugin_set_CHFS">
                <label for="">Api Email: <input type="email" class="CHFSform-control" name="CHFS_Email" value="<?php echo esc_html(get_option('CHFS_Email')); ?>" placeholder="Email" required/></label>
                <label for="">Api Password: <input type="text" class="CHFSform-control" name="CHFS_Password" value="<?php echo esc_html(get_option('CHFS_Password')); ?>"  required placeholder="Password"></label>
                
                
                <?php submit_button(esc_html(get_option('CHFS_AUTH')));?>
                
            </form>
        </div>
        <div class="CHFScol-sm-6">
            <form id="ajax_form" action="options.php" method="post" enctype="multipart/form-data">
                <?php settings_fields('CHFS_option_group'); ?>
                <input type="hidden" name="CHFS_Enable_Templates" value="CHFS_Enable_Templates">
                <button class="CHFScHFSbtn CHFScHFSbtn-warning"><?php echo esc_html(get_option('CHFS_ENABLE_TEMP')); ?></button>
            </form>
        </div>
    </div>
</div>
<?php
}
function process_form_settings_chfs(){
register_setting('CHFS_option_group', 'CHFS_option_name' );
if(isset($_POST['action']) && current_user_can('manage_options') && isset($_POST['plugin_set_CHFS'])){
update_option('CHFS_Email', sanitize_text_field($_POST['CHFS_Email']));
update_option('CHFS_Password', sanitize_text_field($_POST['CHFS_Password']));
    $post = wp_remote_retrieve_body(wp_remote_post(CHFS_API_URL.'/api/login', [
            'body' => [
                'email' =>esc_html(get_option('CHFS_Email')),
                'password' =>esc_html(get_option('CHFS_Password')),
                
            ],
            'method' => 'POST',
            'content-type' => 'application/json',
        ]));
    $result=json_decode($post);
    
    if (isset($result->error)) {
        update_option('CHFS_AUTH', sanitize_text_field('Unauthorized Please Provide Right Credentials'));
    }else{
        update_option('CHFS_AUTH', sanitize_text_field('Authenticated Credentials'));
    }

}
if(isset($_POST['action'])  && isset($_POST['CHFS_Enable_Templates'])){
if (!file_exists(plugin_dir_path( __DIR__ ).'elementor/includes/editor-templates/global.php')) {
// echo 'no exist Elementor Global File';
update_option('CHFS_ENABLE_TEMP', sanitize_text_field('no exist Elementor Global File'));
}else{
unlink(plugin_dir_path( __DIR__ ).'elementor/includes/editor-templates/global.php');
if (!file_exists(CHFS_PATH."global.php")) {
// echo 'no exist CHFS Global File';
update_option('CHFS_ENABLE_TEMP', sanitize_text_field('no exist CHFS Global File'));
}else{
$source =CHFS_PATH."global.php";

// Store the path of destination file
$destination =plugin_dir_path( __DIR__ ).'elementor/includes/editor-templates/global.php';

// Copy the file from /user/desktop/geek.txt
// to user/Downloads/geeksforgeeks.txt'
// directory
if(!copy($source, $destination)) {
//echo "File can't be copied! \n";
update_option('CHFS_ENABLE_TEMP', sanitize_text_field("File can't be copied!"));
}
else {
    update_option('CHFS_ENABLE_TEMP', sanitize_text_field("CHFS Elementor Templates Enable Successfully"));
//echo "File has been copied! \n";
}
}
}
}
}

add_action('add_CHFS_Custom_libray_button', 'my_Add_libray_CHFS_To_Elmentor' );
function my_Add_libray_CHFS_To_Elmentor() {
echo '<button type="button" class=" btn CHFScHFSbtn CHFScHFSbtn-primary add_CHFS_Custom_libray_button CHFScHFSbtn-sm" id="add_CHFS_Custom_libray_button" onclick="CHFSFunction()">CH Library</button>';
} // The end of my_actionhook_example()

register_activation_hook(__FILE__, function(){
add_option('CHFS_Email', '');
add_option('CHFS_Password', '');
add_option('CHFS_AUTH', 'Save Api Credentials');
add_option('CHFS_ENABLE_TEMP', 'Enable Templates');
});
register_deactivation_hook(__FILE__, function(){
});
add_action('wp_footer', 'CHFS_wpshout_action_example');
function CHFS_wpshout_action_example() {
if (CHFS_TOKEN) {
$curl = curl_init();
curl_setopt_array($curl, array(
CURLOPT_URL => CHFS_API_URL.'/api/getCategories',
CURLOPT_RETURNTRANSFER => true,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => "GET",
CURLOPT_HTTPHEADER => array(
"accept: application/json",
"authorization: Bearer ".CHFS_TOKEN,
"cache-control: no-cache",
),
));
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
if ($err) {
echo "cURL Error #:" . $err;
} else {
echo $response;
}
$result=json_decode($response);
if (isset($result->message)) {
$result=[];
}else{
$result;

}
$catemp='<option value="all">All Categories</option>';
foreach ($result as $key => $value) {
$catemp.='<option value='.$value->id.'>'.$value->name.'</option>';
}

}


echo '<!-- The Modal -->
<div class="CHFSmodal CHFSfade" id="add_CHFS_Custom_libray_modal">
    <div class="CHFSmodal-dialog add_CHFS_Custom_libray_dialog CHFSmodal-lg " style="max-width: 100% !important;
        width: 100% !important;">
        <div class="CHFSmodal-content">
            
            <!-- Modal Header -->
            <div class="CHFSmodal-header">
                
                <button type="button" class="CHFSclose" data-dismiss="modal"  onclick="CHFSFunctionhide()">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="CHFSmodal-body">
                <div class="CHFSrow CHFSpy-3">
                    <div class="CHFScol-lg-4">
                        
                        <select class="CHFSform-control CFSH_CATGORY" onchange="CFSH_CATGORY(this.value)" >
                            '.$catemp.'
                        </select>
                    </div>
                    <div class="CHFScol-lg-4">
                        <select class="CHFSform-control CFSH_IS_PRO" onchange="CFSH_IS_PRO(this.value)">
                            <option value="all">All Templates</option>
                            <option value="no">Elementor</option>
                            <option value="yes">Elementor Pro</option>
                        </select>
                    </div>
                    <div class="CHFScol-lg-4">
                        <input type="text" class="CHFSform-control searchCFSH" placeholder="Search Title..." name="searchCFSH" onchange="searchCFSH(this.value)">
                    </div>
                </div>
                <div class="CHFSrow CHFSpy-4 CHFStext-center" id="loadmore_CHFS" style="overflow: scroll;height: 400px;">
                </div>
                <button type="button" style="position: relative;
                left: 45%;" class="btn CHFScHFSbtn-info CHFStext-center loadmore_CHFS_button"  onclick="CHFSFunctionLoadmore()" data-loadMoreCHFS=2>Load More </button>
                
            </div>
            
            <!-- Modal footer -->
            <div class="CHFSmodal-footer">
                <button type="button" class="btn CHFScHFSbtn-secondary CHFScHFSbtn-sm" data-dismiss="modal"
                
                onclick="CHFSFunctionhide()">Close</button>
            </div>
            
        </div>
    </div>
</div>
<div class="CHFSmodal CHFSfade" id="add_CHFS_Custom_libray_modal_view">
    <div class="CHFSmodal-dialog  CHFSmodal-lg ">
        <div class="CHFSmodal-content">
            
            <!-- Modal Header -->
            <div class="CHFSmodal-header">
                
                <button type="button" class="CHFSclose" data-dismiss="modal"  onclick="CHFSFunctionhide_view()">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="CHFSmodal-body" style="overflow: scroll;height: 400px;">
                
                <img src="" class="CHFSimg-thumbnail CHFSimg-fluid viewtemp">
                
            </div>
            
            <!-- Modal footer -->
            <div class="CHFSmodal-footer">
                <button type="button" class="btn CHFScHFSbtn-secondary CHFScHFSbtn-sm" data-dismiss="modal"
                
                onclick="CHFSFunctionhide_view()">Close</button>
            </div>
            
        </div>
    </div>
</div>
';
}


require_once plugin_dir_path( __DIR__ ).'elementor/includes/template-library/sources/base.php';
require_once plugin_dir_path( __DIR__ ).'elementor/includes/template-library/sources/local.php';

add_action("wp_ajax_frontend_action_without_file_chfs" , "frontend_action_without_file_chfs");
add_action("wp_ajax_nopriv_frontend_action_without_file_chfs" , "frontend_action_without_file_chfs");

function frontend_action_without_file_chfs(){


if(isset($_POST['tempUrlImp'])){

  $pathCHFS = parse_url($_POST['tempUrlImp'], PHP_URL_PATH);

$source_local = new Elementor\TemplateLibrary\Source_Local();
if ( ! method_exists( $source_local, 'import_template' ) ) {
$error->add( 'elementor-api-error', __( 'Elementor API unavailable.', 'elementor-json-importer' ) );
//return $error;
echo json_encode(['status'=>400]);
}


try {
$result = $source_local->import_template(basename($pathCHFS),$_POST['tempUrlImp']);
echo  json_encode(['status'=>200]);
} catch ( Exception $e ) {
//$error->add( 'elementor-import-error', __( 'Elementor import has failed.', 'elementor-json-importer' ) );
echo  json_encode(['status'=>400]);
//return $error;
}


//  finally {
// return  json_encode(['status'=>200]);
// wp_import_cleanup( $file['id'] );
// }



}
  
    wp_die();
}


