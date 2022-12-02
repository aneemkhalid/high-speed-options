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
 * Template Name: Template - Provider Lander
 * Template Post Type: page
 * @package HSO
 */

get_header();

$title = get_field('homepage_title');
$header_background = get_template_directory_uri() . '/images/homepage-header.svg';

?>

<header class="homepage-header">
    <div class="homepage-header-background" style="background-image: url(<?php echo $header_background; ?>);">
       <div class="container">
            <?php get_template_part( 'template-parts/breadcrumbs', null, array( 'has_advertiser_disclosure_link' => true ) ); ?>
            <div class="d-flex flex-column align-items-center p-sm-5">
                    <h1 class="text-center mb-5"><?php echo $title; ?></h1>
                    <?php get_template_part( 'template-parts/zip-search-form' ); ?>
            </div>
        </div> 
    </div>
</header>

<main class="site-main">

    <?php
        while ( have_posts() ) :
            the_post();

            echo the_content();

        endwhile; // End of the loop.
    ?>
</main><!-- #main -->

<?php get_footer(); ?>