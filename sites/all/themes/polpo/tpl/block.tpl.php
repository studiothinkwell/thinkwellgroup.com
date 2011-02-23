<div id="block-<?php print $block->module .'-'. $block->delta; ?>" class="block">

<?php if (!empty($block->subject)): ?>
  <h4><?php print $block->subject ?></h4>
<?php endif;?>

<?php print $block->content ?>
</div>
