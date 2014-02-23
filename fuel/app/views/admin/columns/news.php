<?php print Form::open(array('action'=>Uri::current() . '/add','class'=>'form_style_1')); ?>
<div class="col-xs-12 vertical graycontainer globalmenu">
    <div class="description">
        <h3>
            <?php print __('news.header') ?>
        </h3>
    </div>
    <div class="list padding15">
        <?php print Form::label(__('news.title')); ?>

        <?php print Form::input('title',$title); ?>

        <?php

        print Form::submit('submit',__('news.submit'),array('class'=>'button'));

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

        function writeRow($nav,$class='')
        {
            print '<div id="' . $nav->id . '" class="list_entry news_item ' . $class . '">';

            print '<div class="col-xs-3 padding15">';

            $date = new DateTime($nav->creation_date);

            print $date->format(__('news.dateformat'));

            print '</div>';

            print '<div class="col-xs-6 padding15">';

            print $nav->title;

            print '</div>';

            print '<div class="col-xs-3 news-options">';

            print '<a data-id="' . $nav->id . '" class="delete" href="' . Uri::create('admin/news/delete/' . $nav->id) . '"><img src="' . Uri::create('assets/img/icons/delete.png') . '" /></a>';
            print '<a href="' . Uri::create('admin/news/edit/' . $nav->id) . '"><img src="' . Uri::create('assets/img/icons/edit.png') . '" /></a> ';

            print '</div>';

            print '</div>';
        }
        ?>
    </div>
</div>

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