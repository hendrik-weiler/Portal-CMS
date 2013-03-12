<h3>
	<?php print __('news.header') ?>
</h3>
<?php print Form::open(array('action'=>Uri::current() . '/add','class'=>'form_style_1')); ?>
  <div class="clearfix">
   <?php print Form::label(__('news.title')); ?>
   <div class="input">
      <?php print Form::input('title',$title); ?>
    </div>
  </div>
<?php
	print '<div class="actions">';

	print Form::submit('submit',__('news.submit'),array('class'=>'btn primary'));

	print '</div>';

	print Form::close();
?>
<hr />

<?php

	try
	{

		$news = model_db_news::find('all',array(
			'order_by' => array('creation_date'=>'DESC')
		));

	}
	catch(Exception $e)
	{
		Controller_Language_Language::add_language(Session::get('lang_prefix'),'',true);
	}

	if(empty($news))
	{
		print __('news.no_entries');
	}
	else
	{
		foreach($news as $new)
		{
			writeRow($new);
		}
	}

	function writeRow($nav,$class='news_entry')
	{
		print '<section id="' . $nav->id . '" class="list_entry clearfix ' . $class . '">';

		print '<date>';

		$date = new DateTime($nav->creation_date);

		print $date->format(__('news.dateformat'));

		print '</date>';

		print '<span>';

		print $nav->title;

		print '</span>';

		print '<div>';

		print '<a href="' . Uri::create('admin/news/edit/' . $nav->id) . '">' . __('constants.edit') . '</a> ';
		print '<a data-id="' . $nav->id . '" class="delete" href="' . Uri::create('admin/news/delete/' . $nav->id) . '">' . __('constants.delete') . '</a>';

		print '</div>';

		print '</section>';
	}
?>

<script type="text/javascript">
	var dialog = new pcms.dialog('.delete', {
		title : _prompt.header,
		text : _prompt.text,
		confirm : _prompt.ok,
		cancel : _prompt.cancel
	});
	dialog.onConfirm = function(helper, event) {
		var id = $(event.initiator).attr('data-id');
		helper.post_data(_url + 'admin/news/delete/' + id, {});
	}
	dialog.render(); 
</script>