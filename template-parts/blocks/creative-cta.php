<?php

$type = get_field('type');
$text = get_field('descriptive_text');
$cta = get_field('cta_link');
$logo = ($logo = get_field('header_logo', 'options')) ? $logo : get_template_directory_uri().'/images/Highspeedoptions_header_logo.svg';
$blue = ['internet', 'bundle', 'phone', 'remote', 'gaming', 'fiber', 'dsl', 'work'];
//$green = ['tv', 'security', 'cyber', 'school', 'streaming', 'satellite', 'cable'];
$icon = get_template_directory_uri() . '/images/creative-cta/'. $type . '.svg';

$bg = (in_array($type, $blue)) ? 'bg-blue' : 'bg-green';
?>

<section class="creative-cta-block overflow-hidden <?php echo $bg . ' type-' . $type ?>">
  <div class="d-flex flex-column flex-sm-row align-items-sm-center">
    <div class="mb-4 col-sm-7 col-md-7 mb-sm-0">
      <img src="<?php echo $logo ?>" alt="logo" height="18" width="166" class="logo"> 
      <h3><?php echo $text; ?></h3>
      <div class="text-center text-sm-left">
        <a href="<?php echo $cta['url']; ?>" target="<?php echo $cta['target']; ?>" class="btn cta_btn d-inline-block"><?php echo $cta['title']; ?></a>
      </div>
    </div>
    <div class="text-center col-sm-5 col-md-5 img-container">
      <img src="<?php echo $icon; ?>" alt="<?php echo $type ?> graphic" width="auto" height="150" class="graphic">
    </div>
  </div>
</section>



