<style type="text/css">
.question {
	border-bottom: 1px solid #dfdfdf;
	margin: 5px;
	padding: 5px;
}

.question h4 {
	height: 50px;
	line-height: 140%;
}
</style>
<div class="row">
	<div class="span16">

<?php if(count($new_updates) != 0): ?>
<div class="dashboard-message">
	<?php 

	if(count($new_updates) == 1)
		$message = __('new_updates_single');
	else
		$message = __('new_updates_multi');

	print Html::anchor('admin/advanced/update',count($new_updates) . $message); 
	?>
</div>
<?php endif; ?>

<h1>
	<?php print __('header') ?>
</h1>

<?php print Form::open(); ?>
<div class="clearfix">
	<?php print Form::label(__('filter')) ?>
	<div class="input">
	<?php print Form::input('filter','',array('class'=>'xxlarge')) ?>
	</div>
</div>
<?php print Form::close(); ?>
<div class="row questions">
<?php 
$links = __('question_links');
$show = __('question_links_show');
foreach(__('questions') as $key => $question): 
?>
<?php
$question_permission = explode(',',__('question_permissions.' . $key));
if(Controller_Supersearch_Supersearch::get_task_permission($question_permission , $permissions) || model_db_accounts::getCol(Session::get('session_id'),'admin')):
?>
	<div class="question span7">
		<h4><?php print $question ?></h4>
		<div class="links">
			<?php if(preg_match('#shortcut#i',$show[$key])) print Html::anchor(preg_replace('/\#([\w\=]+)/i','',$links[$key]), __('short_cut')); ?>
			<?php if(preg_match('#tour#i',$show[$key]) && preg_match('#shortcut#i',$show[$key])) print ' | '; ?>
			<?php if(preg_match('#tour#i',$show[$key])) print Html::anchor($links[$key], __('take_tour')); ?> 
		</div>
	</div>
<?php endif; ?>
<?php endforeach; ?>
</div>
</div>
<div class="span5"></div>
</div>
<script type="text/javascript">
$(function() {
	var filter_value = '';

	function filter_it()
	{
		var words = filter_value.split(' ');

		$('.questions').find('.question').each(function(key, obj) {
			var title = $(obj).find('h4').text();
			var condition_counter = 0;
			for(var key in words)
			{
				var regex = new RegExp(words[key],'i');
				if(regex.test(title))
					condition_counter++;
			}
			if(condition_counter != words.length)
				$(obj).hide();
			else
				$(obj).show();
		});
	}

	$('#form_filter').keyup(function() {
		filter_value = $(this).val();
		filter_it();
	});

	$('#form_filter').focus();
});
</script>