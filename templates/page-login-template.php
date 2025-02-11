<?php
/*
 * Template Name: Login Registration Page
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title(); ?></title>
    <?php wp_head(); ?>
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
        
        .site-logo {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .site-logo img {
            max-width: 200px;
            height: auto;
        }
        
        .back-to-site {
            position: fixed;
            top: 20px;
            left: 20px;
            padding: 8px 16px;
            background: #fff;
            color: #2271b1;
            text-decoration: none;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .back-to-site:hover {
            background: #2271b1;
            color: #fff;
        }
    </style>
</head>
<body <?php body_class(); ?>>
    <a href="<?php echo home_url(); ?>" class="back-to-site">← Back to Site</a>
    
    <div class="login-page-wrapper">
        <?php if (has_custom_logo()) : ?>
            <div class="site-logo">
                <?php the_custom_logo(); ?>
            </div>
        <?php endif; ?>
        
        <?php while (have_posts()) : the_post(); ?>
            <?php the_content(); ?>
        <?php endwhile; ?>
    </div>
    
    <?php wp_footer(); ?>
</body>
</html> 