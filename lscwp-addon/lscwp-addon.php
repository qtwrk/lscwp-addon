<?php
/*
Plugin Name: LiteSpeed Cache Plugin Addon
Plugin URI:  https://github.com/qtwrk/lscwp-addon
Description: This plugin will add option to enabling purge button for non-admin user.
Author:      qtwrk
Version:     1.0
License:     GPLv3
License URI: http://www.gnu.org/licenses/gpl.html
*/

defined('WPINC') || exit;

add_filter('plugin_action_links_' . plugin_basename(__FILE__) , 'add_action_links');
function add_action_links($links)
{

    $mylinks = array(
        '<a href="' . admin_url('admin.php?page=litespeed-cache-addon-menu') . '">Settings</a>',
    );

    if (!defined('LSCWP_V'))
    {
        $mylinks = array(
            '<a style="color:red; font-weight: bold;" href="#">LiteSpeed Cache is not activated</a> ',
        );
    }
    return array_merge($links, $mylinks);
}
#message if LSCWP not enabled

if (!defined('LSCWP_V'))
{
    return;
}
#returns nothing if not enabled.

function lscwp_addon_settings()
{
    register_setting('lscwp_addon_settings', 'addon_purge_switch');
    register_setting('lscwp_addon_settings', 'purge_current');
    register_setting('lscwp_addon_settings', 'purge_all');
    register_setting('lscwp_addon_settings', 'purge_pages');
    register_setting('lscwp_addon_settings', 'purge_posts');
    register_setting('lscwp_addon_settings', 'purge_categories');
    register_setting('lscwp_addon_settings', 'purge_products');
}
add_action('admin_init', 'lscwp_addon_settings');
#register settings 

function lscwp_addon_check_before_save($new_value, $old_value)
{
    if (wp_roles()->is_role($new_value))
    {
        return $new_value;
    }
    return $old_value;
}

$options_array = array(
    'all',
    'current',
    'pages',
    'posts',
    'categories',
    'products'
);
foreach ($options_array as $options)
{
    add_filter("pre_update_option_purge_$options", 'lscwp_addon_check_before_save', 10, 2);
}
#check input is a valid role

add_action('admin_menu', 'lscwp_addon_add_menu');
function lscwp_addon_add_menu()
{
    add_menu_page('LiteSpeed Cache Addon', 'LiteSpeed Cache Addon', 'manage_options', 'litespeed-cache-addon-menu', 'lscwp_addon_html');
}
#create setting page

add_action('admin_menu', 'lscwp_addon_hide_menu');
function lscwp_addon_hide_menu()
{
    remove_menu_page('litespeed-cache-addon-menu');
}
#hide it from wp-admin area

add_action('admin_menu', 'lscwp_addon_setting_page', 90);
function lscwp_addon_setting_page()
{
    add_submenu_page('litespeed', 'LiteSpeed Cache Addon', 'Addon', 'manage_options', 'admin.php?page=litespeed-cache-addon-menu');
}
#inject into LSCWP's menu

function lscwp_addon_html()
{
?>
    <div class="wrap">
        <h2>LiteSpeed Cache Addon Setting</h2>
 
<form method="post" action="options.php">
    <?php settings_fields('lscwp_addon_settings'); ?>
<table class="form-table">
    <tr>
        <th><label for="addon_purge_switch">Enable Purge Button for Non-Admin user ?</label></th>
        <td>
<select id="addon_purge_switch" name="addon_purge_switch">
<option 
  <option value="enable" 
<?php
    if (get_option('addon_purge_switch') == 'enable')
    {
        echo 'selected="selected"';
    }
?>
>Enable</option>
  <option value="disable" 
<?php
    if (get_option('addon_purge_switch') == 'disable')
    {
        echo 'selected="selected"';
    }
?>
>Disable</option>
</option>
</select>
        </td>
    </tr>
    <tr>
        <th><label for="purge_current">Who can access "Purge Current URL" ?</label></th>
        <td>
<?php global $wp_roles; ?>
<select id="purge_current" name="purge_current">
<?php foreach ($wp_roles->roles as $key => $value): ?>
<option 
<?php
        if ($key == get_option('purge_current'))
        {
            echo 'selected="selected"';
        }
?>
value="<?php echo $key; ?>"><?php echo $value['name']; ?></option>
<?php
    endforeach; ?>
</select>
        </td>
    </tr>
    <tr>
        <th><label for="purge_current">Who can access "Purge All" ?</label></th>
        <td>
<?php global $wp_roles; ?>
<select id="purge_all" name="purge_all">
<?php foreach ($wp_roles->roles as $key => $value): ?>
<option
<?php
        if ($key == get_option('purge_all'))
        {
            echo 'selected="selected"';
        }
?>
value="<?php echo $key; ?>"><?php echo $value['name']; ?></option>
<?php
    endforeach; ?>
</select>
        </td>
    </tr>
        <th><label for="purge_current">Who can access "Purge All Pages" ?</label></th>
        <td>
<?php global $wp_roles; ?>
<select id="purge_pages" name="purge_pages">
<?php foreach ($wp_roles->roles as $key => $value): ?>
<option
<?php
        if ($key == get_option('purge_pages'))
        {
            echo 'selected="selected"';
        }
?>
value="<?php echo $key; ?>"><?php echo $value['name']; ?></option>
<?php
    endforeach; ?>
</select>
        </td>
    </tr>
    <th><label for="purge_current">Who can access "Purge All Posts" ?</label></th>
        <td>
<?php global $wp_roles; ?>
<select id="purge_posts" name="purge_posts">
<?php foreach ($wp_roles->roles as $key => $value): ?>
<option
<?php
        if ($key == get_option('purge_posts'))
        {
            echo 'selected="selected"';
        }
?>
value="<?php echo $key; ?>"><?php echo $value['name']; ?></option>
<?php
    endforeach; ?>
</select>
        </td>
    </tr>
    <th><label for="purge_current">Who can access "Purge All Categories" ?</label></th>
        <td>
<?php global $wp_roles; ?>
<select id="purge_categories" name="purge_categories">
<?php foreach ($wp_roles->roles as $key => $value): ?>
<option
<?php
        if ($key == get_option('purge_categories'))
        {
            echo 'selected="selected"';
        }
?>
value="<?php echo $key; ?>"><?php echo $value['name']; ?></option>
<?php
    endforeach; ?>
</select>
        </td>
    </tr>
     <th><label for="purge_current">Who can access "Purge All Products" ?</label></th>
        <td>
<?php global $wp_roles; ?>
<select id="purge_products" name="purge_products">
<?php foreach ($wp_roles->roles as $key => $value): ?>
<option
<?php
        if ($key == get_option('purge_products'))
        {
            echo 'selected="selected"';
        }
?>
value="<?php echo $key; ?>"><?php echo $value['name']; ?></option>
<?php
    endforeach; ?>
</select>
        </td>
    </tr>
</table>
<br><br>
<b>Remember to purge all after you save setting.</b>
<?php submit_button(); ?>

    </div>
 
<?php
}
#setting page HTML code

function lscwp_addon_purge_admin_menu()
{
    if (current_user_can('manage_options') || get_option('addon_purge_switch') != 'enable')
    {
        return;
    }
    $user_roles = (array)(wp_get_current_user()->roles);
    $options = array(
        'purge_all' => array(
            'id' => 'purge-all',
            'title' => __('Purge All')
        ) ,
        'purge_posts' => array(
            'id' => 'purge-all-posts',
            'title' => __('Purge All Posts')
        ) ,
        'purge_pages' => array(
            'id' => 'purge-all-pages',
            'title' => __('Purge All Pages')
        ) ,
        'purge_products' => array(
            'id' => 'purge-all-products',
            'title' => __('Purge All Products')
        ) ,
        'purge_categories' => array(
            'id' => 'purge-all-categories',
            'title' => __('Purge All Categories')
        ) ,
    );
    $admin_url = str_replace(get_home_url() , "", get_admin_url());
    if (stripos($_SERVER['REQUEST_URI'], $admin_url) === false)
    {
        $options['purge_current'] = array(
            'id' => 'purge_current',
            'title' => __('Purge Current Page')
        );
    }

    $allowed = array();
    foreach ($options as $o => $data)
    {
        if (in_array(get_option($o) , $user_roles))
        {
            $allowed[] = $o;
        }
    }
    if (empty($allowed))
    {
        return;
    }
    global $wp_admin_bar;
    $menu_id = 'lscwp_addon_purge';
    do_action('litespeed_nonce', 'lscwp_addon_nonce');
    $href = get_admin_url() . 'admin-post.php?action=lscwp_addon_purge&lscwp_addon_nonce=' . wp_create_nonce('lscwp_addon_nonce') . '&previous_page=' . $_SERVER['REQUEST_URI'] . '&data=';
    $wp_admin_bar->add_menu(array(
        'id' => $menu_id,
        'title' => __('LiteSpeed Cache Purge') ,
        'href' => ''
    ));
    foreach ($allowed as $o)
    {
        $wp_admin_bar->add_menu(array(
            'parent' => $menu_id,
            'title' => $options[$o]['title'],
            'id' => $options[$o]['id'],
            'href' => $href . $o,
            'meta' => array(
                'target' => '_self'
            )
        ));
    }
}
add_action('admin_bar_menu', 'lscwp_addon_purge_admin_menu', 100);
#create admin bar menu conditionally

add_action('admin_post_lscwp_addon_purge', 'lscwp_addon_purge');
function lscwp_addon_purge()
{

    $nonce = $_REQUEST['lscwp_addon_nonce'];
    if (!wp_verify_nonce($nonce, 'lscwp_addon_nonce'))
    {
        echo 'Nonce verification failed.';
        echo '<script>window.location.href = "' . $_REQUEST['previous_page'] . '";</script>';
        exit();
    }

    if (!current_user_can('manage_options'))
    {
        if (get_option('addon_purge_switch') == 'enable')
        {
            $user = wp_get_current_user();

            if (in_array(get_option('purge_all') , (array)$user->roles))
            {
                if (strpos($_REQUEST['data'], 'purge_all') !== false)
                {
                    do_action('litespeed_purge_all');
                    echo "purged all";
                    echo '<script>window.location.href = "' . $_REQUEST['previous_page'] . '";</script>';
                }
            }

            if (in_array(get_option('purge_categories') , (array)$user->roles))
            {
                if (strpos($_REQUEST['data'], 'purge_categories') !== false)
                {
                    do_action('litespeed_purge', 'T');
                    do_action('litespeed_purge', 'tax');
                    echo "purged all categories";
                    echo '<script>window.location.href = "' . $_REQUEST['previous_page'] . '";</script>';
                }

            }

            if (in_array(get_option('purge_posts') , (array)$user->roles))
            {
                if (strpos($_REQUEST['data'], 'purge_posts') !== false)
                {
                    do_action('litespeed_purge_posttype', 'post');
                    do_action('litespeed_purge', 'post');
                    echo "purged all posts";
                    echo '<script>window.location.href = "' . $_REQUEST['previous_page'] . '";</script>';
                }
            }

            if (in_array(get_option('purge_pages') , (array)$user->roles))
            {
                if (strpos($_REQUEST['data'], 'purge_pages') !== false)
                {
                    do_action('litespeed_purge_posttype', 'page');
                    do_action('litespeed_purge', 'PGS');
                    do_action('litespeed_purge', 'page');
                    echo "purged all pages";
                    echo '<script>window.location.href = "' . $_REQUEST['previous_page'] . '";</script>';
                }
            }

            if (in_array(get_option('purge_products') , (array)$user->roles))
            {
                if (strpos($_REQUEST['data'], 'purge_products') !== false)
                {
                    do_action('litespeed_purge_posttype', 'product');
                    do_action('litespeed_purge', 'product');
                    echo "purged all products";
                    echo '<script>window.location.href = "' . $_REQUEST['previous_page'] . '";</script>';
                }
            }

            if (in_array(get_option('purge_current') , (array)$user->roles))
            {
                if ((strpos($_REQUEST['data'], 'purge_current') !== false))
                {
                    do_action('litespeed_purge_url', $_REQUEST['previous_page']);
                    echo "purged page :" . $_REQUEST['previous_page'];
                    echo '<script>window.location.href = "' . $_REQUEST['previous_page'] . '";</script>';
                }
            }
        }
    }
}
#actions for each button.
