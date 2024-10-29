<?php
    $social_icons = fetch_icons_for_options();
    if(trim(get_option('social_icon_size')) == "") { update_option('social_icon_size','32'); }
    if ( FALSE === get_option('social_hover_effect') ) add_option( 'social_hover_effect', true );
    if(trim(get_option('social_display_style')) == "") { update_option('social_display_style','horizontal'); }
    if ( FALSE === get_option('social_name_display') ) add_option( 'social_name_display', false );
    if(trim(get_option('social_web_link_title')) == "") { update_option('social_web_link_title','Social Bookmarks'); }
    
    if($_POST['action'] == "update") {
        foreach($social_icons as $socialoption) {
            $optioninfo = explode(".",$socialoption);
            $option_name = ucwords($optioninfo[0]).'_url';
            update_option($option_name,$_POST[$option_name]);
        }
        update_option('social_icon_size',$_POST['social_icon_size']);
        update_option('social_hover_effect',$_POST['social_hover_effect']);
        update_option('social_display_style',$_POST['social_display_style']);
        update_option('social_name_display',$_POST['social_name_display']);
        update_option('social_web_link_title',$_POST['social_web_link_title']);
        $settingsmsg = '<div style="background: #fee571; padding: 5px; border: 1px #C68E17 solid;">Settings Saved</div>';
    }
?>
<div class="wrap">
    <h2><?php _e('Beautiful Social Web Link Options'); ?></h2>
    <hr>
    <?php echo $settingsmsg; ?>
    <form name="beautiful-social-web-link-option-form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <?php wp_nonce_field('update-options'); ?>
        <h3><?php _e('Display Options'); ?></h3>
        <table>
            <tr>
                <td style="width: 170px;"><?php _e('Select the size of icons'); ?></td>
                <td>
                    <input type="radio" name="social_icon_size" id="social_icon_size" value="16" <?php if(get_option('social_icon_size') == '16') { ?> checked="checked" <?php } ?> /> <?php _e('16x16'); ?>
                    &nbsp;&nbsp;&nbsp;<input type="radio" name="social_icon_size" id="social_icon_size" value="32" <?php if(get_option('social_icon_size') == '32') { ?> checked="checked" <?php } ?> /> <?php _e('32x32'); ?>
                </td>
            </tr>
            <tr>
                <td><?php _e('Enable Transparency effect: '); ?></td>
                <td><input type="checkbox" name="social_hover_effect" id="social_hover_effect" value="true" <?php if(get_option('social_hover_effect')) { echo 'checked'; } ?> /></td>
            </tr>
            <tr>
                <td style="width: 170px;"><?php _e('Select the Display style'); ?></td>
                <td>
                    <input type="radio" name="social_display_style" id="social_display_style" value="horizontal" <?php if(get_option('social_display_style') == 'horizontal') { ?> checked="checked" <?php } ?> /> <?php _e('Horizontal list'); ?>
                    &nbsp;&nbsp;&nbsp;<input type="radio" name="social_display_style" id="social_display_style" value="single" <?php if(get_option('social_display_style') == 'single') { ?> checked="checked" <?php } ?> /> <?php _e('Single Vertical Column'); ?>
                    &nbsp;&nbsp;&nbsp;<input type="radio" name="social_display_style" id="social_display_style" value="double" <?php if(get_option('social_display_style') == 'double') { ?> checked="checked" <?php } ?> /> <?php _e('Two Vertical Columns'); ?>
                </td>
            </tr>
            <tr>
                <td><?php _e('Display name and URL of the social site besides icons: '); ?></td>
                <td><input type="checkbox" name="social_name_display" id="social_name_display" value="true" <?php if(get_option('social_name_display')) { echo 'checked'; } ?> /></td>
            </tr>
            <tr>
                <td><?php _e('Title for your widget: '); ?></td>
                <td><input style="border: 1px gray solid;" type="text" name="social_web_link_title" id="social_web_link_title" value="<?php echo get_option('social_web_link_title'); ?>" /></td>
            </tr>
        </table>
        <hr/>
        <h3><?php _e('Enter your profile url for respective social sites (eg. <i>http://www.facebook.com/johndoe</i>)'); ?></h3>
        <?php
            $count = 0;
            $first = true;
            foreach($social_icons as $icon) {
                $count++;
                $iconinfo = explode(".",$icon);
                $icon_img = $icon;
                $icon_name = ucwords($iconinfo[0]);
                if($first) { $optionlist .= $icon_name.'_url'; }
                else { $optionlist .= ','.$icon_name.'_url'; }
                $option_img_list .= '<p style="float: left; width: 300px; margin: 0 15px 10px 0;"><img src="'.ICON_URL.'16x16/'.$icon_img.'" />&nbsp;&nbsp;<label>'.$icon_name.'</label>&nbsp;&nbsp;<input style="border: 1px gray solid;" type="text" value="'.get_option($icon_name.'_url').'" name="'.$icon_name.'_url'.'" /></p>'."\n";
                if($count % 3 == 0) { $option_img_list .= '<div style="clear: both; height: 0px;"></div>'; }
                $first = false;
            }
            echo $option_img_list;
            echo '<div style="clear: both; height: 0px;"></div>';
        ?>
        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="<?php echo $optionlist; ?>,social_icon_size,social_hover_effect,social_display_style,social_name_display,social_web_link_title" />
        <p class="submit">
            <input type="submit" name="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
        </p>
    </form>
</div>