<?php
class LR_Shortcodes {
    public function __construct() {
        // Remove the init action since we're registering directly in the main file
        // add_action('init', array($this, 'register_shortcodes'));
        $this->register_shortcodes();
    }

    public function register_shortcodes() {
        // Only register the register shortcode here since login is registered directly
        add_shortcode('lr_register', array($this, 'register_form'));
    }
    
    public function login_form() {
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
        </div>
        <?php
        return ob_get_clean();
    }
    
    public function register_form() {
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
} 