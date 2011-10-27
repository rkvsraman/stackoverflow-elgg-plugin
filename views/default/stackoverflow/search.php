<div class="contentWrapper">
<form class="elgg-form-stackoverflow-search" action="<?php echo $vars['url']; ?>action/stackoverflow/search" method="post">
 
<p><?php echo elgg_echo("search"); ?><br />
<?php echo elgg_view('input/text',array('internalname' => 'title')); ?></p>
 
<?php echo elgg_view('input/securitytoken'); ?>
 
<p><?php echo elgg_view('input/submit', array('value' => elgg_echo('search'))); ?></p>
 
</form>
