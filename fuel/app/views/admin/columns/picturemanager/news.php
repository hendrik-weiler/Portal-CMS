<?php if(empty($all_news)): ?>
<h3><?php print __('picturemanager.no_news'); ?></h3>
<?php endif; ?>
<?php foreach($all_news as $news): ?>

<div class="gallery">

	<h3><?php print $news->title ?></h3>

	<div class="picture_list">
		<?php 
			$pictures = json_decode($news->picture,true);
			$pictures = empty($pictures) ? array() : $pictures;
			$pictures = array_map(function($value) {
				return str_replace('original', 'thumb', $value);
			}, $pictures);
		?>
		<?php if(empty($pictures)): ?>
		<p><?php print __('picturemanager.no_pictures'); ?></p>
		<?php endif; ?>

		<?php if(isset($pictures['picture_1'])): ?>
		<div class="col-xs-3 picture">
			<img src="<?php print Uri::create($pictures['picture_1']) ?>">
			<a class="get_link" href=""><?php print Lang::get('picturemanager.get_link') ?></a>
		</div>
		<?php endif; ?>
		<?php if(isset($pictures['picture_2'])): ?>
		<div class="col-xs-3 picture">
			<img src="<?php print Uri::create($pictures['picture_2']) ?>">
			<a class="get_link" href=""><?php print Lang::get('picturemanager.get_link') ?></a>
		</div>
		<?php endif; ?>
		<?php if(isset($pictures['picture_2'])): ?>
		<div class="col-xs-3 picture">
			<img src="<?php print Uri::create($pictures['picture_3']) ?>">
			<a class="get_link" href=""><?php print Lang::get('picturemanager.get_link') ?></a>
		</div>
		<?php endif; ?>
	</div>

</div>
<?php endforeach; ?>

<script type="text/javascript">

	// ------- link dialog

	var link_dialog = new pcms.dialog('.get_link', {
		title : "<?php print Lang::get('picturemanager.prompt.link_header') ?>",
		text : '',
		confirm : "<?php print Lang::get('picturemanager.prompt.close') ?>",
		cancel : '',
		no_cancel : true
	});
	link_dialog.onConfirm = function(helper, event) {
		helper.cancel_dialog();
	}
	link_dialog.onInitiate = function(helper, event) {
		var text = $('<input style="width:240px" value="" />');
		var index = $('.picture_list .picture a').index(event.currentTarget);

		$(text).attr('value',$('.picture_list .picture').eq(index).find('img').attr('src').replace('thumb','big'));
		helper.set_new_content({
			text : text
		});
	}
	link_dialog.render(); 

</script>