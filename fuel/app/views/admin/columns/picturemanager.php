<!DOCTYPE html>
<html>
<head>
	<title>Portalcms - Picturemanager</title>
	<meta charset="utf-8" />
	<?php print Asset\Manager::get('js->include->1_jquery->jquery') ?>
	<?php print Asset\Manager::get('js->include->1_jquery->jquery-ui') ?>
	<?php print Asset\Manager::get('js->dialog->dialog') ?>
	<?php print Asset\Manager::get('js->picturemanager->picturemanager') ?>

	<script type="text/javascript">
	    var _prompt = {
	      header : '<?php print __('picturemanager.prompt.header') ?>',
	      text : '<?php print __('picturemanager.prompt.text') ?>',
	      ok : '<?php print __('picturemanager.prompt.ok') ?>',
	      cancel : '<?php print __('picturemanager.prompt.cancel') ?>'
	    };
	    var _url = '<?php print Uri::create('/') ?>';
	</script>

	<?php print \Asset::css('bootstrap.min.css') ?>
    <?php print \Asset::css('ui.css') ?>
	<?php print \Asset::css('picturemanager.css') ?>
</head>
<body>
	<div class="row-fluid">
		<div class="navigation col-xs-3">
			<ul>
				<?php foreach(Lang::get('picturemanager.types') as $key => $type): ?>
				<li><a <?php print $key == $current_type ? 'class="active"' : '' ?> href="<?php print Uri::create('admin/picturemanager/' . $key) ?>"><?php print $type ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<div class="content col-xs-9">
			<?php print $content ?>
		</div>
	</div>
	<script type="text/javascript">
	$('.navigation').height($(document).height());
	$('.content').height($(document).height()-15);

	$(window).resize(function() {
		$('.navigation').height($(document).height());
		$('.content').height($(document).height()-15);
	});
	</script>
</body>
</html>