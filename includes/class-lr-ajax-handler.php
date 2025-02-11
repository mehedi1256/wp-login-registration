<?php
class LR_Ajax_Handler {
    public function __construct() {
        add_action('wp_ajax_nopriv_lr_login', array($this, 'handle_login'));
        add_action('wp_ajax_nopriv_lr_register', array($this, 'handle_register'));
    }
    
    public function handle_login() {
        check_ajax_referer('lr_nonce', 'nonce');
        
        $username = sanitize_user($_POST['username']);
        $password = $_POST['password'];
        
        $user = wp_signon(array(
            'user_login' => $username,
            'user_password' => $password,
            'remember' => true
        ));
        
        if (is_wp_error($user)) {
            wp_send_json_error(array('message' => 'Invalid credentials'));
        } else {
            wp_send_json_success(array('message' => 'Login successful'));
        }
    }
    
    public function handle_register() {
        check_ajax_referer('lr_nonce', 'nonce');
        
        $username = sanitize_user($_POST['username']);
        $email = sanitize_email($_POST['email']);
        $password = $_POST['password'];
        $fullname = sanitize_text_field($_POST['fullname']);
        
        if (username_exists($username)) {
            wp_send_json_error(array('message' => 'Username already exists'));
            return;
        }
        
        if (email_exists($email)) {
            wp_send_json_error(array('message' => 'Email already exists'));
            return;
        }
        
        $user_id = wp_create_user($username, $password, $email);
        
        if (is_wp_error($user_id)) {
            wp_send_json_error(array('message' => $user_id->get_error_message()));
            return;
        }
        
        // Update full name
        wp_update_user(array(
            'ID' => $user_id,
            'display_name' => $fullname
        ));
        
        wp_send_json_success(array('message' => 'Registration successful'));
    }
} 