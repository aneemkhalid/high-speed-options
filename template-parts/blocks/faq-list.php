<?php

/**
 * FAQ List Block.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

$faq_list = get_field('faq_list');

if( have_rows('faq_list') ): ?>
    <?php foreach($faq_list as $faq): 
            $group_anchor_link = convert_string_to_anchor_link( $faq['faq_group_title'] );    
    ?>
        <section class="faq-block-container row-full">
            <div class="faq-block">
                <div class="container">
                    <div class="title-container" id="<?php echo $group_anchor_link; ?>">
                        <div class="pretitle">FAQ</div>
                        <h2><?php echo $faq['faq_group_title'];  ?></h2>
                    </div>
                    <div id="accordion">
                        <?php
                            $counter = 1;
                            // Loop through rows.
                            foreach($faq['faq_group_items'] as $item): ?>

                                <div class="card">
                                    <div class="card-header" id="heading<?php echo $counter ?>">
                                    <h4>
                                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapse<?php echo $counter ?>" aria-expanded="true" aria-controls="collapse<?php echo $counter ?>">
                                            <div class="question"><?php echo $item['question']; ?></div>
                                            <span class="material-icons">expand_less</span>
                                        </button>
                                    </h4>
                                    </div>
                                    <div id="collapse<?php echo $counter ?>" class="collapse  show<?php //if($counter == 1) echo 'show'; ?>" aria-labelledby="heading<?php echo $counter ?>" data-parent="#accordion">
                                        <div class="card-body"><?php echo $item['answer']; ?></div>
                                    </div>
                                </div>

                            <?php $counter++; endforeach;	?>
                    
                    </div>
                </div>	
            </div>
        </section>
    <?php endforeach; ?>
<?php endif; ?>

