<div class="padding20">

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

<?php print Form::open(); ?>

<div class="row inputbutton">
  <div class="col-xs-4 inputbutton-input">
      <?php print Form::input('filter','',array('placeholder'=>'Einträge filtern...')) ?>
  </div>
  <div class="col-xs-4 inputbutton-button">
    <img src="<?php print Uri::create('assets/img/icons/filter.gif') ?>" alt="">
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
	<div class="question col-xs-4">
		<h4><?php print $question ?></h4>
		<div class="links">
			<?php if(preg_match('#shortcut#i',$show[$key])) print Html::anchor(preg_replace('/\#([\w\=]+)/i','',$links[$key]), __('short_cut'),array('class'=>'button')); ?>
			<?php if(preg_match('#tour#i',$show[$key])) print Html::anchor($links[$key], __('take_tour'),array('class'=>'button')); ?> 
		</div>
	</div>
<?php endif; ?>
<?php endforeach; ?>
</div>
</div>
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