<?php if(is_user_logged_in()): ?>
<div class="migration-banner">
  <div class="gif-container ml-4">
    <div class="gif mr-3">
      <img src="<?php echo get_template_directory_uri(); ?>/images/working.gif" alt="working animation" height="32" width="32">
    </div>
    <div class="gif mr-3">
      <img src="<?php echo get_template_directory_uri(); ?>/images/caution.gif" alt="working animation" height="32" width="32">
    </div>
    <div class="gif mr-3">
      <img src="<?php echo get_template_directory_uri(); ?>/images/working.gif" alt="working animation" height="32" width="32">
    </div>
  </div>

  <div class="scroll-container">
    <div class="scroll-text">
      <?php for($i=0; $i<=6; $i++): ?>
        <?php echo get_field('migration_text', 'option') . '&nbsp&nbsp&nbsp&nbsp'; ?>
      <?php endfor; ?>
    </div>
  </div>

  <div class="gif-container mr-4">
    <div class="gif ml-3">
      <img src="<?php echo get_template_directory_uri(); ?>/images/working.gif" alt="working animation" height="32" width="32">
    </div>
    <div class="gif ml-3">
      <img src="<?php echo get_template_directory_uri(); ?>/images/caution.gif" alt="working animation" height="32" width="32">
    </div>
    <div class="gif ml-3">
      <img src="<?php echo get_template_directory_uri(); ?>/images/working.gif" alt="working animation" height="32" width="32">
    </div>
  </div>
  <div class="fire-ribbon"></div>
</div>
<?php endif; ?>