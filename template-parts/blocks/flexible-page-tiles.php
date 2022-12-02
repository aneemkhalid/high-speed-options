<?php

/**
 * Flexible Page Tiles Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

$flexible_page_tiles = get_field('flexible_page_tiles');

if(is_array($flexible_page_tiles)):
?>
<section class="flexible-content-wrapper row-full">
    <div class="container">
    <div class="flexible-content-container">
    <div class="flexible-content-title-container">
        <h3><?php echo $flexible_page_tiles['title']; ?></h2>
    </div>
    <div class="flexible-content-description-container">
        <p><?php echo $flexible_page_tiles['description']; ?></p>
    </div>
    <div class="flexible-content-blocks-container">
        <?php foreach($flexible_page_tiles['content_blocks'] as $key => $content_block):
            $page_obj = $content_block['content_block_page'];
            $author_name = get_the_author_meta('display_name', $page_obj->post_author);
            $tags = get_the_tags($page_obj->ID);
            $tag_name = '';
            $post_type = get_post_type($page_obj->ID);
            if ($post_type == 'page' && $page_obj->post_parent){
                $parent_title = get_the_title($page_obj->post_parent);
                $tag_name = $parent_title;
            } else {
                if (!empty($tags)){
                    $tag_name = $tags[0]->name;
                }
            }
            $truncated_title = truncate($content_block['content_block_title'], 42);
        ?>
            <div class="flexible-content-block-container content-block-<?php echo $key;?>">
                <a href="<?php echo get_permalink($page_obj->ID); ?>" class="flexible-content-block-link">
                    <div class="content-container">
                        <div class="content-image-container">
                            <img src="<?php echo $content_block['content_block_image']['url']; ?>" alt="<?php echo $content_block['content_block_title']; ?>" class="content-image">
                        </div>
                        <div class="content-text-container">
                            <div class="content-tag-container">
                                <div class="content-tag">
                                    <?php echo $tag_name; ?>
                                </div>
                            </div>
                            <div class="content-title-container">
                                <h4 class="content-title desktop"><?php echo $content_block['content_block_title']; ?></h4>
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
    </div>
</section>


<?php endif; ?>