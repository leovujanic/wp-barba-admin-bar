<?php
/*
Plugin Name:  Barba Admin Bar
Plugin URI:   http://www.degordian.com/
Description:  Allows users to use admin bar when site is using barba.js manager
Version:      1.0.1
Author:       Leo Vujanić
Author URI:   http://leo.vujanic.info/
*/


define('BAB_OPTION_INSTALLED', 'barba_admin_bar_installed');
define('BAB_PLUGIN_DEBUG', false);

/**
 * Install function
 * @return bool
 */
function barba_admin_bar_install()
{
    return update_option(BAB_OPTION_INSTALLED, 'true', true);
}

/**
 * Uninstall function
 * @return bool
 */
function barba_admin_bar_uninstall()
{
    return delete_option(BAB_OPTION_INSTALLED);
}

/**
 * Inserts JS code after wp admin menu bar
 */
function bab_add_script()
{
    $ajaxUrl = admin_url('admin-ajax.php');
    $babDebug = json_encode(BAB_PLUGIN_DEBUG);
    $js = <<<JS
jQuery(function() {
    var ajaxUrl = "$ajaxUrl";
    var babDebug = $babDebug;
    
    function babLog(str) {
        if(babDebug !== true) {
            return;
        }
        console.log(str);
    }
  
    function updateData() {
        babLog('updateData called');
        var data = {
		    'action': 'bab_get_page_id',
		    'location': window.location.href
	        };
        $.post("$ajaxUrl", data, function(response) {
            if(!response) {
                console.error('Barba Admin Menu - No Ajax response');
                return;
            }
            response = JSON.parse(response);
            
            if(response && response.success === true) {
                babLog('Barba Admin Menu - updateData ajax success');
                jQuery(".ab-top-menu").find("a").each(function() {
                    var jqthis = $(this);
                    var mregex = /post=(\d+)&/g;
                    jqthis.addClass("no-barba");
                    var href = jqthis.attr('href');
                    
                    var groups = mregex.exec(href);
                    
                   if(groups && groups.length >= 2) {
                       jqthis.attr('href', href.replace(groups[1], response.pageId));
                   } else {
                        var chunks =  href.split('?url=');
                        if(chunks.length === 2) {
                           jqthis.attr('href', chunks[0] + '?url=' + encodeURIComponent(window.location.href));
                        }
                   }
                });
                babLog('Barba Admin Menu - Updated menu');
            } else {
                console.error('Barba Admin Menu - No Ajax response');
            }
        });
    }
    
    
    jQuery(".ab-top-menu").find("a").addClass("no-barba");
    
    if(Barba) {
        Barba.Dispatcher.on('transitionCompleted', function() {
            updateData();
        });
        
        babLog('Barba Admin Menu - Registered menu update');
    } else {
        babLog('Barba Admin Menu - Missing Barba object');
    }
});
JS;
    echo '<script>' . $js . '</script>';
}


/**
 * Get page id from page url
 */
function bab_get_page_id()
{
    ob_clean();
    
    $data = [
        'success' => false,
        'pageId'  => false,
    ];
    
    if (isset($_POST['location']) && filter_var($_POST['location'], FILTER_VALIDATE_URL) !== false) {
        $postId = url_to_postid($_POST['location']);
        $data['success'] = true;
        $data['pageId'] = $postId;
    }
    
    echo json_encode($data);
    
    wp_die();
    exit;
}

add_action('wp_ajax_bab_get_page_id', 'bab_get_page_id');


if (is_admin()) {
    register_activation_hook(__FILE__, 'barba_admin_bar_install');
    register_deactivation_hook(__FILE__, 'barba_admin_bar_uninstall');
} else {
    if (get_option(BAB_OPTION_INSTALLED) === 'true') {
        add_action('wp_after_admin_bar_render', 'bab_add_script');
    }
}
