<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title('|', true, 'right'); bloginfo('name'); ?></title>
    <?php 
    // Remove emoji support for this page
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    
    do_action('wp_enqueue_scripts');
    wp_head();
    ?>
    <style>
        body {
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
        }
        .login-page-container {
            width: 100%;
            max-width: 450px;
            margin: 0 auto;
            padding: 20px;
        }
    </style>
</head>
<body <?php body_class(); ?>>
    <div class="login-page-container">
        <?php 
        if (has_shortcode(get_post()->post_content, 'lr_login')) {
            echo do_shortcode('[lr_login]');
        } elseif (has_shortcode(get_post()->post_content, 'lr_register')) {
            echo do_shortcode('[lr_register]');
        }
        ?>
    </div>
    <?php wp_footer(); ?>
</body>
</html> 