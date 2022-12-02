<?php

/**
 * Well Consider Block.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

$likes = get_field('likes');
$dislikes = get_field('dislikes');
$ltitle = get_field('likes_title');
$dtitle = get_field('dislike_title');

$check = get_template_directory_uri() . '/images/check.svg';
$close = get_template_directory_uri() . '/images/close.svg';

?>

<section class="well-consider-block">
    <div class="well-container">
        <div class="likes-container">
            <h5><?php echo $ltitle ?></h5>
            <div>
                <?php foreach($likes as $item) : ?>
                    <div class="like-item">
                        <div class="icon-container">
                            <img src="<?php echo $check ?>" alt="check icon" width="24" height="24">
                        </div>
                        <div>
                            <?php echo $item['bullet'] ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="dislikes-container">
            <h5><?php echo $dtitle ?></h5>
            <div>
                <?php foreach($dislikes as $item) : ?>
                    <div class="like-item">
                        <div class="icon-container">
                            <img src="<?php echo $close ?>" alt="close icon" width="24" height="24">
                        </div>
                        <div>
                            <?php echo $item['bullet'] ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>