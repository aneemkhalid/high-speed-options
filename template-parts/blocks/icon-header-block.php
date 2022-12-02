<?php

$header = get_field('header');
$content = get_field('content');
$icon = get_field('icon');


?>
<section class="icon-header-block">
  <div class="title-container">
    <?php if($icon) : ?>
      <div class="icon-container">
        <img src="<?php echo $icon['url']; ?>" height="40" width="40" alt="<?php echo $icon['alt']; ?>" class="icon-header-block-icon">
      </div>
    <?php endif; ?>
    <h3><?php echo $header; ?></h3>
  </div>
  <?php if($content) : ?>
    <div>
      <?php echo $content; ?>
    </div>
  <?php endif; ?>
</section>