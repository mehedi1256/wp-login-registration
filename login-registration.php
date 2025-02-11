<?php
/*
 * Plugin Name:       Login and Registration
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Login, Registration with authentication and authorization.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Mehedi Hassan Shovo
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       my-basics-plugin
 * Domain Path:       /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('LR_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('LR_PLUGIN_URL', plugin_dir_url(__FILE__));

// Enqueue scripts and styles
/*
function lr_enqueue_scripts() {
    if (lr_is_login_page()) {
        wp_enqueue_style('lr-styles', LR_PLUGIN_URL . 'assets/css/style.css', array(), '1.0.0');
        wp_enqueue_script('jquery');
        wp_enqueue_script('lr-scripts', LR_PLUGIN_URL . 'assets/js/scripts.js', array('jquery'), '1.0.0', true);
        
        wp_localize_script('lr-scripts', 'lr_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('lr_nonce')
        ));
    }
}
add_action('wp_enqueue_scripts', 'lr_enqueue_scripts', 100);
*/

// Login Form Shortcode
function lr_login_form_shortcode() {
    ob_start();
    ?>
    <div class="lr-form-container">
        <form id="lr-login-form" class="lr-form">
            <div class="lr-message"></div>
            
            <div class="lr-field">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="lr-field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit">Login</button>
        </form>
        
        <div class="lr-register-link">
            <p>Don't have an account?</p>
            <button type="button" class="lr-toggle-form" data-form="register">Register Now</button>
        </div>
    </div>

    <div class="lr-form-container" id="lr-register-container" style="display: none;">
        <form id="lr-register-form" class="lr-form">
            <div class="lr-message"></div>
            
            <div class="lr-field">
                <label for="reg_username">Username</label>
                <input type="text" id="reg_username" name="username" required>
            </div>
            
            <div class="lr-field">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" required>
            </div>
            
            <div class="lr-field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="lr-field">
                <label for="reg_password">Password</label>
                <input type="password" id="reg_password" name="password" required>
            </div>
            
            <div class="lr-field">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <button type="submit">Register</button>
        </form>

        <div class="lr-login-link">
            <p>Already have an account?</p>
            <button type="button" class="lr-toggle-form" data-form="login">Back to Login</button>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('lr_login', 'lr_login_form_shortcode');

// Register Form Shortcode
function lr_register_form_shortcode() {
    ob_start();
    ?>
    <div class="lr-form-container">
        <form id="lr-register-form" class="lr-form">
            <div class="lr-message"></div>
            
            <div class="lr-field">
                <label for="reg_username">Username</label>
                <input type="text" id="reg_username" name="username" required>
            </div>
            
            <div class="lr-field">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" required>
            </div>
            
            <div class="lr-field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="lr-field">
                <label for="reg_password">Password</label>
                <input type="password" id="reg_password" name="password" required>
            </div>
            
            <div class="lr-field">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <button type="submit">Register</button>
        </form>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('lr_register', 'lr_register_form_shortcode');

// Ajax handler for login
add_action('wp_ajax_nopriv_lr_login', 'lr_handle_login');
function lr_handle_login() {
    // Verify nonce
    if (!check_ajax_referer('lr_nonce', 'nonce', false)) {
        wp_send_json_error(array('message' => 'Security check failed'));
        return;
    }
    
    // Get and validate input
    $username = isset($_POST['username']) ? sanitize_user($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    // Validate required fields
    if (empty($username) || empty($password)) {
        wp_send_json_error(array('message' => 'Username and password are required'));
        return;
    }
    
    // Attempt to sign on
    $user = wp_signon(array(
        'user_login'    => $username,
        'user_password' => $password,
        'remember'      => true
    ), is_ssl());
    
    if (is_wp_error($user)) {
        wp_send_json_error(array('message' => 'Invalid username or password'));
        return;
    }
    
    wp_send_json_success(array(
        'message' => 'Login successful! Redirecting...',
        'redirect_url' => home_url() // Or wherever you want to redirect after login
    ));
}

// Ajax handler for registration
add_action('wp_ajax_nopriv_lr_register', 'lr_handle_register');
function lr_handle_register() {
    // Verify nonce
    if (!check_ajax_referer('lr_nonce', 'nonce', false)) {
        wp_send_json_error(array('message' => 'Security check failed'));
        return;
    }
    
    // Get and sanitize input
    $username = isset($_POST['username']) ? sanitize_user($_POST['username']) : '';
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $fullname = isset($_POST['fullname']) ? sanitize_text_field($_POST['fullname']) : '';
    
    // Validate required fields
    if (empty($username) || empty($email) || empty($password) || empty($fullname)) {
        wp_send_json_error(array('message' => 'All fields are required'));
        return;
    }
    
    // Validate email format
    if (!is_email($email)) {
        wp_send_json_error(array('message' => 'Invalid email address'));
        return;
    }
    
    // Check password strength (minimum 6 characters)
    if (strlen($password) < 6) {
        wp_send_json_error(array('message' => 'Password must be at least 6 characters long'));
        return;
    }
    
    // Check if username exists
    if (username_exists($username)) {
        wp_send_json_error(array('message' => 'This username is already taken'));
        return;
    }
    
    // Check if email exists
    if (email_exists($email)) {
        wp_send_json_error(array('message' => 'This email address is already registered'));
        return;
    }
    
    // Create user
    $user_id = wp_create_user($username, $password, $email);
    
    if (is_wp_error($user_id)) {
        wp_send_json_error(array('message' => $user_id->get_error_message()));
        return;
    }
    
    // Update user meta
    wp_update_user(array(
        'ID' => $user_id,
        'display_name' => $fullname,
        'first_name' => $fullname
    ));
    
    // Optional: Automatically log in the user after registration
    /*
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);
    */
    
    wp_send_json_success(array('message' => 'Registration successful! You can now login.'));
}

// Register the custom template
function lr_add_template_to_select($post_templates, $wp_theme, $post, $post_type) {
    if ($post_type === 'page') {
        $post_templates['templates/page-login-template.php'] = 'Login Registration Page';
    }
    return $post_templates;
}
add_filter('theme_page_templates', 'lr_add_template_to_select', 10, 4);

// Load the template from plugin
function lr_load_plugin_template($template) {
    if (is_page()) {
        $template_name = get_post_meta(get_the_ID(), '_wp_page_template', true);
        if ('templates/page-login-template.php' === $template_name) {
            $template = LR_PLUGIN_PATH . 'templates/page-login-template.php';
        }
    }
    return $template;
}
add_filter('template_include', 'lr_load_plugin_template');

// Check if page contains login/register shortcode and automatically set template
function lr_set_template_for_login_page($post_id) {
    if (get_post_type($post_id) === 'page') {
        $content = get_post_field('post_content', $post_id);
        if (has_shortcode($content, 'lr_login') || has_shortcode($content, 'lr_register')) {
            update_post_meta($post_id, '_wp_page_template', 'templates/page-login-template.php');
        }
    }
}
add_action('save_post', 'lr_set_template_for_login_page');

// Add this function after your existing code
function lr_is_login_page() {
    if (!is_page()) return false;
    global $post;
    return has_shortcode($post->post_content, 'lr_login') || has_shortcode($post->post_content, 'lr_register');
}

// Remove all other content when on login/register page
function lr_template_redirect() {
    if (lr_is_login_page()) {
        // Ensure styles and scripts are loaded
        wp_enqueue_style('lr-styles', LR_PLUGIN_URL . 'assets/css/style.css', array(), '1.0.0');
        wp_enqueue_script('jquery');
        wp_enqueue_script('lr-scripts', LR_PLUGIN_URL . 'assets/js/scripts.js', array('jquery'), '1.0.0', true);
        
        wp_localize_script('lr-scripts', 'lr_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('lr_nonce')
        ));

        // Load our custom template
        include(LR_PLUGIN_PATH . 'templates/blank-template.php');
        exit;
    }
}
add_action('template_redirect', 'lr_template_redirect', 999);

function lr_get_login_content($content) {
    if (has_shortcode($content, 'lr_login')) {
        return do_shortcode('[lr_login]');
    } elseif (has_shortcode($content, 'lr_register')) {
        return do_shortcode('[lr_register]');
    }
    return $content;
}
