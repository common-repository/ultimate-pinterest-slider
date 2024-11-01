<?php
/*
Plugin Name: Ultimate Pinterest Slider
Plugin URI: http://ultimatesocialwidgets.com/wpdemo/
Description: Ultimate Pinterest Slider is awesome tool for your websites. Enjoy the limitless fun with pinterest using our Pinterest Slider.
Author: Twitter Widget
Version: 1.0
Author URI: http://ultimatesocialwidgets.com/wpdemo/
*/

class RealPinterestSlider{

    public $options;

    public function __construct() {
        //you can run delete_option method to reset all data
        //delete_option('real_pin_plugin_options');
        $this->options = get_option('real_pin_plugin_options');
        $this->real_pin_register_settings_and_fields();
    }

    public static function add_real_pin_tools_options_page(){
        add_options_page('Ultimate Pinterest Slider', 'Ultimate Pinterest Slider', 'administrator', __FILE__, array('RealPinterestSlider','real_pin_tools_options'));
    }

    public static function real_pin_tools_options(){
?>
<div class="wrap">
    <?php screen_icon(); ?>
    <h2>Pinterest Slider Configuration</h2>
    <form method="post" action="options.php" enctype="multipart/form-data">
        <?php settings_fields('real_pin_plugin_options'); ?>
        <?php do_settings_sections(__FILE__); ?>
        <p class="submit">
            <input name="submit" type="submit" class="button-primary" value="Save Changes"/>
        </p>
    </form>
</div>
<?php
    }
    public function real_pin_register_settings_and_fields(){
        register_setting('real_pin_plugin_options', 'real_pin_plugin_options',array($this,'real_pin_validate_settings'));
        add_settings_section('real_pin_main_section', 'Settings', array($this,'real_pin_main_section_cb'), __FILE__);
        //Start Creating Fields and Options
        //sidebar image
       // add_settings_field('sidebarImage', 'Sidebar Image', array($this,'sidebarImage_settings'),__FILE__,'real_pin_main_section');
         //pageURL
        add_settings_field('pageURL', 'Pinterest Profile Name', array($this,'pageURL_settings'), __FILE__,'real_pin_main_section');
        //marginTop
        add_settings_field('marginTop', 'Margin Top', array($this,'marginTop_settings'), __FILE__,'real_pin_main_section');
       //alignment option
         add_settings_field('alignment', 'Alignment Position', array($this,'position_settings'),__FILE__,'real_pin_main_section');
        //width
        add_settings_field('width', 'Width', array($this,'width_settings'), __FILE__,'real_pin_main_section');
        //height
        add_settings_field('height', 'Height', array($this,'height_settings'), __FILE__,'real_pin_main_section');
        //streams options
        add_settings_field('scale', 'Image Width', array($this,'scale_settings'),__FILE__,'real_pin_main_section');

        //jQuery options

    }
    public function real_pin_validate_settings($plugin_options){
        return($plugin_options);
    }
    public function real_pin_main_section_cb(){
        //optional
    }



    //pageURL_settings
    public function pageURL_settings() {
        if(empty($this->options['pinterest_username'])) $this->options['pinterest_username'] = "pinterest";
        echo "<input name='real_pin_plugin_options[pinterest_username]' type='text' value='{$this->options['pinterest_username']}' />";
    }
    //marginTop_settings
    public function marginTop_settings() {
        if(empty($this->options['marginTop'])) $this->options['marginTop'] = "100";
        echo "<input name='real_pin_plugin_options[marginTop]' type='text' value='{$this->options['marginTop']}' />";
    }
    //alignment_settings
    public function position_settings(){
        if(empty($this->options['alignment'])) $this->options['alignment'] = "left";
        $items = array('left','right');
        echo "<select name='real_pin_plugin_options[alignment]'>";
        foreach($items as $item){
            $selected = ($this->options['alignment'] === $item) ? 'selected = "selected"' : '';
            echo "<option value='$item' $selected>$item</option>";
        }
        echo "</select>";
    }
    //connection_settings
    public function connection_settings() {
        if(empty($this->options['connection'])) $this->options['connection'] = "10";
        echo "<input name='real_pin_plugin_options[connection]' type='text' value='{$this->options['connection']}' />";
    }
    //width_settings
    public function width_settings() {
        if(empty($this->options['width'])) $this->options['width'] = "350";
        echo "<input name='real_pin_plugin_options[width]' type='text' value='{$this->options['width']}' />";
    }
    //height_settings
    public function height_settings() {
        if(empty($this->options['height'])) $this->options['height'] = "400";
        echo "<input name='real_pin_plugin_options[height]' type='text' value='{$this->options['height']}' />";
    }
    //image_scale_settings
     public function scale_settings() {
        if(empty($this->options['scale'])) $this->options['scale'] = "80";
        echo "<input name='real_pin_plugin_options[scale]' type='text' value='{$this->options['scale']}' />";
    }




    // put jQuery settings before here
}
add_action('admin_menu', 'real_pin_trigger_options_function');

function real_pin_trigger_options_function(){
    RealPinterestSlider::add_real_pin_tools_options_page();
}

add_action('admin_init','real_pin_trigger_create_object');
function real_pin_trigger_create_object(){
    new RealPinterestSlider();
}
add_action('wp_footer','real_pin_add_content_in_footer');
function real_pin_add_content_in_footer(){

    $o = get_option('real_pin_plugin_options');
    extract($o);
$total_height=$height-120;
$total_width=$width+40;
$print_pinterest = '';
$print_pinterest .= '<a data-pin-do="embedUser" href="http://pinterest.com/'.$pinterest_username.'/"
data-pin-scale-width="'.$scale.'" data-pin-scale-height="'.$total_height.'"
data-pin-board-width="'.$total_width.'"></a>';
$imgURL = plugins_url('assets/pinterest-icon.png', __FILE__);
?>
<?php if($alignment=='left'){?>
<div id="real_pinterest_display">
    <div id="pboxpin" style="left: -<?php echo trim($width+10);?>px; top: <?php echo $marginTop;?>px; z-index: 10000; height:<?php echo trim($height);?>px;">
        <div id="pboxpin2" style="text-align: left;width:<?php echo trim($width);?>px;height:<?php echo trim($height);?>;">
            <a class="open" id="plink" href="#"></a><img style="top: 0px;right:-50px;" src="<?php echo $imgURL;?>" alt="">
            <?php echo $print_pinterest; ?>
        </div>

    </div>
</div>
<script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script>
<script type="text/javascript">
jQuery.noConflict();
jQuery(function (){
jQuery(document).ready(function()
{
jQuery.noConflict();
jQuery(function (){
jQuery("#pboxpin").hover(function(){
jQuery('#pboxpin').css('z-index',101009);
jQuery(this).stop(true,false).animate({left:  0}, 500); },
function(){
    jQuery('#pboxpin').css('z-index',10000);
    jQuery("#pboxpin").stop(true,false).animate({left: -<?php echo trim($width+10); ?>}, 500); });
});}); });
jQuery.noConflict();
</script>
<?php } else { ?>
<div id="real_pinterest_display">
    <div id="pboxpin" style="right: -<?php echo trim($width+10);?>px; top: <?php echo $marginTop;?>px; z-index: 10000; height:<?php echo trim($height);?>px;">
        <div id="pboxpin2" style="text-align: left;width:<?php echo trim($width);?>px;height:<?php echo trim($height);?>;">
            <a class="open" id="plink" href="#"></a><img style="top: 0px;left:-50px;" src="<?php echo $imgURL;?>" alt="">
            <?php echo $print_pinterest; ?>
        </div>

    </div>
</div>
<script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script>
<script type="text/javascript">
jQuery.noConflict();
jQuery(function (){
jQuery(document).ready(function()
{
jQuery.noConflict();
jQuery(function (){
jQuery("#pboxpin").hover(function(){
jQuery('#pboxpin').css('z-index',101009);
jQuery(this).stop(true,false).animate({right:  0}, 500); },
function(){
    jQuery('#pboxpin').css('z-index',10000);
    jQuery("#pboxpin").stop(true,false).animate({right: -<?php echo trim($width+10); ?>}, 500); });
});}); });
jQuery.noConflict();
</script>
<?php } ?>
<?php
}
add_action( 'wp_enqueue_scripts', 'register_real_pinterest_slider_styles' );
 function register_real_pinterest_slider_styles() {
    wp_register_style( 'register_pin_pinterest_slider_styles', plugins_url( 'assets/style.css' , __FILE__ ) );
    wp_enqueue_style( 'register_pin_pinterest_slider_styles' );
        wp_enqueue_script('jquery');
 }
 $real_pin_default_values = array(
     'sidebarImage' => 'pinterest-icon.png',
     'marginTop' => 100,
     'pinterest_username' => 'pinterest',
     'width' => '350',
     'height' => '400',
     'scale' => '80',
     'alignment' => 'left'

 );
 add_option('real_pin_plugin_options', $real_pin_default_values);
