<?php
/*
Plugin Name: ShareMe Social
Plugin URI: https://example.com/shareme-social
Description: A customizable social media sharing plugin for WordPress.
Version: 1.0
Author: Sai Kumar Bhimarasetty
Author URI: https://saiwebpro.com
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

// Enqueue Font Awesome, custom styles, and JavaScript
function shareme_social_enqueue_assets() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');
    wp_enqueue_style('shareme-social-styles', plugin_dir_url(__FILE__) . 'css/shareme-social-styles.css');
    wp_enqueue_script('shareme-social-js', plugin_dir_url(__FILE__) . 'js/shareme-social.js', [], false, true);
}
add_action('wp_enqueue_scripts', 'shareme_social_enqueue_assets');

// Activation hook to set default options
function shareme_social_activate() {
    $default_options = [
        'facebook' => true,
        'twitter' => true,
        'linkedin' => true,
        'icon_color' => '#000000',
        'placement' => 'both'
    ];
    if (!get_option('shareme_social_options')) {
        add_option('shareme_social_options', $default_options);
    }
}
register_activation_hook(__FILE__, 'shareme_social_activate');

// Uninstall hook to clean up options
function shareme_social_uninstall() {
    delete_option('shareme_social_options');
}
register_uninstall_hook(__FILE__, 'shareme_social_uninstall');

// Deactivation hook (if needed)
function shareme_social_deactivate() {
    // Placeholder for deactivation actions
}
register_deactivation_hook(__FILE__, 'shareme_social_deactivate');

// Add settings page
function shareme_social_add_settings_page() {
    add_options_page(
        'ShareMe Social Settings',
        'ShareMe Social',
        'manage_options',
        'shareme-social-settings',
        'shareme_social_settings_page'
    );
}
add_action('admin_menu', 'shareme_social_add_settings_page');

// Settings page content
function shareme_social_settings_page() {
    if (isset($_POST['shareme_social_save'])) {
        update_option('shareme_social_options', $_POST['shareme_social_options']);
        echo '<div class="updated"><p>Settings saved.</p></div>';
    }

    $options = get_option('shareme_social_options', [
        'facebook' => true,
        'twitter' => true,
        'linkedin' => true,
        'icon_color' => '#000000',
        'placement' => 'both'
    ]);
    ?>

    <div class="wrap">
        <h2>ShareMe Social Settings</h2>
        <form method="post">
            <h3>Enable Social Platforms</h3>
            <label><input type="checkbox" name="shareme_social_options[facebook]" <?php checked($options['facebook'], true); ?>> Facebook</label><br>
            <label><input type="checkbox" name="shareme_social_options[twitter]" <?php checked($options['twitter'], true); ?>> Twitter</label><br>
            <label><input type="checkbox" name="shareme_social_options[linkedin]" <?php checked($options['linkedin'], true); ?>> LinkedIn</label><br>

            <h3>Icon Color</h3>
            <input type="color" name="shareme_social_options[icon_color]" value="<?php echo esc_attr($options['icon_color']); ?>">

            <h3>Icon Placement</h3>
            <label><input type="radio" name="shareme_social_options[placement]" value="above" <?php checked($options['placement'], 'above'); ?>> Above Content</label><br>
            <label><input type="radio" name="shareme_social_options[placement]" value="below" <?php checked($options['placement'], 'below'); ?>> Below Content</label><br>
            <label><input type="radio" name="shareme_social_options[placement]" value="both" <?php checked($options['placement'], 'both'); ?>> Both</label><br>

            <?php submit_button('Save Settings', 'primary', 'shareme_social_save'); ?>
        </form>
    </div>
    <?php
}

// Display icons in content
function shareme_social_display_icons($content) {
    $options = get_option('shareme_social_options', [
        'facebook' => true,
        'twitter' => true,
        'linkedin' => true,
        'icon_color' => '#000000',
        'placement' => 'both'
    ]);

    $icons = '<div class="shareme-social-icons" style="color:' . esc_attr($options['icon_color']) . ';">';
    if ($options['facebook']) $icons .= '<a href="#" class="fab fa-facebook"></a>';
    if ($options['twitter']) $icons .= '<a href="#" class="fab fa-twitter"></a>';
    if ($options['linkedin']) $icons .= '<a href="#" class="fab fa-linkedin"></a>';
    $icons .= '</div>';

    return ($options['placement'] == 'above' ? $icons . $content : ($options['placement'] == 'below' ? $content . $icons : $icons . $content . $icons));
}
add_filter('the_content', 'shareme_social_display_icons');
?>
