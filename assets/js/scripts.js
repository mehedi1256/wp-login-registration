jQuery(document).ready(function($) {
    // Toggle between login and registration forms
    $('.lr-toggle-form').on('click', function() {
        var formType = $(this).data('form');
        if (formType === 'register') {
            $('#lr-register-container').fadeIn();
            $(this).closest('.lr-form-container').hide();
        } else {
            $('#lr-register-container').hide();
            $('.lr-form-container').first().fadeIn();
        }
    });

    // Login form submission
    $('#lr-login-form').on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var $message = $form.find('.lr-message');
        var $submitBtn = $form.find('button[type="submit"]');
        
        // Validate fields
        var username = $form.find('#username').val();
        var password = $form.find('#password').val();
        
        if (!username || !password) {
            $message.html('Please fill in all fields').addClass('error');
            return;
        }
        
        $.ajax({
            url: lr_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'lr_login',
                nonce: lr_ajax.nonce,
                username: username,
                password: password
            },
            beforeSend: function() {
                $submitBtn.prop('disabled', true).html('Logging in...');
                $message.html('Please wait...').removeClass('error success');
            },
            success: function(response) {
                if (response.success) {
                    $message.html(response.data.message).addClass('success');
                    setTimeout(function() {
                        window.location.href = response.data.redirect_url;
                    }, 1000);
                } else {
                    $message.html(response.data.message).addClass('error');
                    $submitBtn.prop('disabled', false).html('Login');
                }
            },
            error: function() {
                $message.html('Connection error. Please try again.').addClass('error');
                $submitBtn.prop('disabled', false).html('Login');
            }
        });
    });
    
    // Registration form submission
    $('#lr-register-form').on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var $message = $form.find('.lr-message');
        var $submitBtn = $form.find('button[type="submit"]');
        
        // Get form data
        var username = $form.find('#reg_username').val();
        var fullname = $form.find('#fullname').val();
        var email = $form.find('#email').val();
        var password = $form.find('#reg_password').val();
        var confirm_password = $form.find('#confirm_password').val();
        
        // Validate fields
        if (!username || !fullname || !email || !password || !confirm_password) {
            $message.html('Please fill in all fields').addClass('error');
            return;
        }
        
        // Validate password match
        if (password !== confirm_password) {
            $message.html('Passwords do not match').addClass('error');
            return;
        }
        
        // Validate password length
        if (password.length < 6) {
            $message.html('Password must be at least 6 characters long').addClass('error');
            return;
        }
        
        $.ajax({
            url: lr_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'lr_register',
                nonce: lr_ajax.nonce,
                username: username,
                fullname: fullname,
                email: email,
                password: password
            },
            beforeSend: function() {
                $submitBtn.prop('disabled', true).html('Registering...');
                $message.html('Please wait...').removeClass('error success');
            },
            success: function(response) {
                if (response.success) {
                    $message.html(response.data.message).addClass('success');
                    $form[0].reset();
                    $submitBtn.prop('disabled', false).html('Register');
                    
                    // Switch to login form after successful registration
                    setTimeout(function() {
                        $('#lr-register-container').fadeOut(function() {
                            $('.lr-form-container').first().fadeIn();
                            $('.lr-form-container').first().find('.lr-message')
                                .html('Registration successful! Please login.')
                                .addClass('success');
                        });
                    }, 2000);
                } else {
                    $message.html(response.data.message).addClass('error');
                    $submitBtn.prop('disabled', false).html('Register');
                }
            },
            error: function() {
                $message.html('Connection error. Please try again.').addClass('error');
                $submitBtn.prop('disabled', false).html('Register');
            }
        });
    });
}); 