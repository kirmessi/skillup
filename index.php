<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package nix
 */

get_header();

?>
<?php if ( ! is_user_logged_in()): ?>
    <form id="myForm">
        Username: <input type="text" id="username" class="required"/>
        password: <input type="password" id="password" class="required"/>
        <input type="submit"/>
    </form>
    <div id="result"></div>
<?php endif; ?>
    <script>
        $(document).ready(function () {
            $('#myForm').submit(function () {

                let username = $('#username').val();
                let password = $('#password').val();

                $.post('/wp-json/nix/v1/login', {username: username, password: password}, function (data) {

                    if (data === 'successfully login') {
                        location.reload();
                    }

                    $("#result").html(data);

                });

                return false;

            });
        });
    </script>

    <div id="primary" class="content-area">
        <main id="main" class="site-main">

            <?php
            if (have_posts()) :

                if (is_home() && ! is_front_page()) :
                    ?>
                    <header>
                        <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
                    </header>
                <?php
                endif;

                /* Start the Loop */
                while (have_posts()) :
                    the_post();

                    /*
                     * Include the Post-Type-specific template for the content.
                     * If you want to override this in a child theme, then include a file
                     * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                     */
                    get_template_part('template-parts/content', get_post_type());

                endwhile;

                the_posts_navigation();

            else :

                get_template_part('template-parts/content', 'none');

            endif;
            ?>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php
get_sidebar();
get_footer();
