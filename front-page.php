<?php /* 
This page is used to display the static frontpage. 
*/
 
get_header();

if (isset($_GET['zip'])){ ?>
    <div id="home-page-zip-search-redirect" class="preloader">
        <img class="zip-search-load-gif" src="<?php echo get_template_directory_uri() ?>/images/726-wireless-connection-outline.gif" alt="loading gif" height="100" width="100"/>
    </div>
<?php }

// require get_theme_file_path( '/template-parts/page-banner.php' );
?>

<main class="site-main">

    <?php
        while ( have_posts() ) :
            the_post();

            echo the_content();

        endwhile; // End of the loop.
    ?>
    <?php get_template_part('/template-parts/related_posts', null, ['container' => true,]); ?>

</main><!-- #main -->

<?php get_footer(); ?>