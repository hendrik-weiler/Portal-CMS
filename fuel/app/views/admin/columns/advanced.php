<div class="row">
<?php
	print Form::open(array('action'=>'admin/advanced/edit','id'=>'advanced_form','class'=>'form_style_1'));
?>
<section id="advanced" class="clearfix">
	<div class="span8">
	<h3>
		<?php print __('advanced.header.thumbnails') ?>
	</h3>
	<h4>
		<?php print __('advanced.subHeader.news') ?>
	</h4>
	  <div class="clearfix">
	   <?php print Form::label(__('advanced.thumbs.width')); ?>
	   <div class="input">
	      <?php print Form::input('news_thumbs_width',$news_thumbs_width); ?>
	    </div>
	  </div>
	  <div class="clearfix">
	   <?php print Form::label(__('advanced.thumbs.height')); ?>
	   <div class="input">
	      <?php print Form::input('news_thumbs_height',$news_thumbs_height); ?>
	    </div>
	  </div>
	<h4>
		<?php print __('advanced.subHeader.gallery') ?>
	</h4>
		<div class="clearfix">
	   <?php print Form::label(__('advanced.thumbs.width')); ?>
	   <div class="input">
	      <?php print Form::input('gallery_thumbs_width',$gallery_thumbs_width); ?>
	    </div>
	  </div>
	  <div class="clearfix">
	   <?php print Form::label(__('advanced.thumbs.height')); ?>
	   <div class="input">
	      <?php print Form::input('gallery_thumbs_height',$gallery_thumbs_height); ?>
	    </div>
	  </div>
	</div>
<div class="span7">
	<h3>
		<?php print __('advanced.header.news') ?>
	</h3>
	<h4>
		<?php print __('advanced.subHeader.view') ?>
	</h4>
	  <div class="clearfix">
	   <?php print Form::label(__('advanced.news.show_last')); ?>
	   <div class="input">
	      <?php print Form::input('show_last',$show_last); ?>
	    </div>
	  </div>
	  <div class="clearfix">
	   <?php print Form::label(__('advanced.news.show_max_token')); ?>
	   <div class="input">
	      <?php print Form::input('show_max_token',$show_max_token); ?>
	    </div>
	  </div>
	</div>
	<div class="span16">
		<?php
			print '<div class="actions">';
			
			print Form::submit('submit',__('constants.save'),array('class'=>'btn primary'));

			print '</div>';

			print Form::close();
		?>
	</div>
<div class="span16 accounts">
	<h3>
		<?php print __('advanced.header.accounts') ?>
	</h3>
<?php
	print '<a class="btn" href="' . Uri::create('admin/accounts/add') . '">' . __('advanced.accounts.add.button') . '</a>';
$accounts = model_db_accounts::find('all',array(
	'order_by' => array('language'=>'ASC')
));

foreach($accounts as $account)
{
	print writeRow($account);
}

	function writeRow($account,$class='list_entry_accounts')
	{
		print '<section id="' . $account->id . '" class="list_entry clearfix ' . $class . '">';

		print '<div><strong>';

		print $account->language;

		print '</strong></div>';

		print '<span>';

		print $account->username;

		print '</span>';


		print '<div class="options">';

		print '<a href="' . Uri::create('admin/accounts/edit/' . $account->id) . '">' . __('constants.edit') . '</a> ';
		
		if($account->username != model_auth::$user['username'])
			print '<a class="delete" href="' . Uri::create('admin/accounts/delete/' . $account->id) . '">' . __('constants.delete') . '</a>';

		print '</div>';

		print '</section>';
	}
?>
</div>
</section>
</div>