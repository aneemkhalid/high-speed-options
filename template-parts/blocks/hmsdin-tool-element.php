<?php

/**
 * Element for the How Much Speed Do I Need Tool
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

$location = get_field('hmsdin_location');
$image = get_field('hmsdin_image');
$title = get_field('hmsdin_title');
$description = get_field('hmsdin_description');
$button_text = get_field('hmsdin_button_text');
$button_link = get_field('hmsdin_button_link');
$show_speed_descriptions = get_field('hmsdin_show_speed_descriptions');
$show_speed_spacing = '';
$speed_desc = '';
$display_sidebar = '';
$heading_size = 'h2'; 
if( $location['value'] == 'sidebar'){
    $display_sidebar = ' d-sidebar';
    $heading_size = 'h4';
}


    if($show_speed_descriptions):
        if(have_rows('hmsdin_speed_descriptions')):
            $speed_desc .= 
            '<section class="hmsdin-speeds-section' . $display_sidebar . '">'.
            '<div class="container">'.
            '<div class="row row-speeds">'; 
            while(have_rows('hmsdin_speed_descriptions')): the_row();
                $speed_desc_title = get_sub_field('title');
                $speed_desc_content = get_sub_field('description');
                $speed_desc .=  
                '<div class="col col-12 col-sm-6 col-md-3 d-none d-sm-block col-copy">' . 
                    '<h3 class="speed-title">' . $speed_desc_title . '</h3>' . 
                    '<p class="speed-description">' . $speed_desc_content . '</p>' . 
                '</div>';
                        
            endwhile;
            $speed_desc .= 
            '</div>' . 
            '</div>'.
            '</section>';
            $show_speed_spacing = ' show-speed';
        endif;
    endif;


    $html = 
    '<section class="hmsdin-section row-full' . $display_sidebar . $show_speed_spacing . '">' . 
        '<div class="container">' . 
            '<div class="row row-content">' . 
                '<div class="col col-md-6 d-none d-md-block col-image">' . 
                    '<div class="hmsdin-image-container">' . 
                        wp_get_attachment_image($image['id'], 'full') . 
                    '</div>' . 
                '</div>' . 
                '<div class="col col-12 col-md-6">' . 
                    '<'.$heading_size.' class="hmsdin-title">' . $title . '</'.$heading_size.'>' .
                    '<p class="hmsdin-description">' . $description . '</p>' .
                    '<a href="' . $button_link . '" class="cta_btn">' . $button_text . '</a>' .   
                '</div>' . 
            '</div>' . 
        '</div>' . 
    '</section>'. 
    $speed_desc;

    echo $html;

?>

