<?php

/**
 * Types of {Service} Technology Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

$flexible_content = get_field('flexible_content');

if(is_array($flexible_content)):
?>
<div class="flexible-content-container">
    <div class="flexible-content-title-container">
        <h2 class="flexible-content-title"><?php echo $flexible_content['title']; ?></h2>
    </div>
    <div class="flexible-content-description-container">
        <div class="flexible-content-description"><?php echo $flexible_content['description']; ?></div>
    </div>
    <div class="flexible-content-blocks-container">
        <?php foreach($flexible_content['content_blocks'] as $key => $block):
            $page_obj = $block['content_block_page'];
            $author_name = get_the_author_meta('display_name', $page_obj->post_author);
            $tags = get_the_tags($page_obj->ID);
            $truncated_title = create_custom_excerpt($block['content_block_title'], 30);
        ?>
            <div class="flexible-content-block-container content-block-<?php echo $key;?>">
                <a href="<?php echo get_permalink($page_obj->ID); ?>" class="flexible-content-block-link">
                    <div class="content-container">
                        <div class="content-image-container">
                            <img src="<?php echo $block['content_block_image']['sizes']['thumbnail']; ?>" alt="<?php echo $block['content_block_title']; ?>" class="content-image">
                        </div>
                        <div class="content-text-container">
                            <div class="content-tag-container">
                                <div class="content-tag">
                                    <?php echo $tags[0]->name; ?>
                                </div>
                            </div>
                            <div class="content-title-container">
                                <h4 class="content-title desktop"><?php echo $block['content_block_title']; ?></h4>
                                <h4 class="content-title mobile"><?php echo $truncated_title; ?></h4>
                            </div>
                            <div class="content-byline-container">
                                <div class="content-byline">
                                    By <?php echo $author_name; ?> &middot; <?php echo date("M j, Y", strtotime($page_obj->post_date)); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php endif; ?>