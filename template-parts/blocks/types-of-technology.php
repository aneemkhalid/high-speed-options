<?php

/**
 * Types of {Service} Technology Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

$technology_types = get_field('technology_types');

if(is_array($technology_types)):
?>
<div class="technology-types-container row-full">
    <div class="container">
        <div class="technology-types-title-container">
            <h2>Types of <?php the_title(); ?> Technology</h2>
        </div>
        <div class="technology-types-description-container">
            <?php echo $technology_types['description']; ?>
        </div>
        <div class="technology-types-tiles-container">
            <?php if(is_array($technology_types['technology_tiles'])): ?>
                <?php foreach($technology_types['technology_tiles'] as $tile): ?>
                    <a href="<?php echo $tile['link']['url']; ?>">
                        <div class="tile-container">
                            <div class="tile-icon-container">
                                <img src="<?php echo $tile['image']['sizes']['thumbnail']; ?>" alt="<?php echo $tile['label']; ?>">
                            </div>
                            <div class="tile-label-container">
                                <?php echo $tile['label']; ?>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php endif; ?>