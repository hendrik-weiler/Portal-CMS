<?php print Form::open(array('action'=>Uri::current(),'class'=>'form_style_1','enctype'=>'multipart/form-data')); ?>
<div class="col-xs-1 backbutton">
    <label>
        <?php print Form::submit('back',__('types.15.back'),array('class'=>'hide')); ?>
        <img src="<?php print Uri::create('assets/img/icons/arrow_left.png') ?>" alt=""/>
    </label>
</div>
<div class="col-xs-11 vertical graycontainer globalmenu">
    <div class="description">
        <h3>
          <?php print __('types.5.header') ?>
        </h3>
    </div>
    <div class="list padding15">
        <div class="col-xs-6">
            <?php writeDownEverything('col_1',$col_1_selected); ?>
        </div>
        <div class="col-xs-6">
            <?php writeDownEverything('col_2',$col_2_selected); ?>
        </div>
        <div class="col-xs-12">
            <?php print Form::submit('submit',__('types.5.submit'),array('class'=>'button')); ?>
        </div>
    </div>
</div>
<?php
function writeDownEverything($name,$selected)
{
    $navigations = model_db_navigation::asSelectBox();
    $navigations = array(0=>__('constants.not_set')) + $navigations;
  if(empty($navigations))
  {
    print __('sites.no_entries');
  }
  else
  {

    foreach($navigations as $key => $navi)
    {
      if(is_array($navi))
      {
        print '<h4>' . $key . '</h4><blockquote>';

        foreach($navi as $subKey => $subNavi)
        {
          print '<h4>' . $subNavi . '</h4>';

          $sites = model_db_site::find()->where('navigation_id',$subKey)->order_by(array('sort'=>'ASC'))->get();

          if(!empty($sites))
          {
            print '<blockquote>';
            foreach($sites as $site) {
              $contents = model_db_content::find('all',array(
                'where' => array('site_id'=>$site->id),
                'order_by' => array('sort'=>'ASC')
              ));

              foreach($contents as $content)
              {
                if(!in_array($content->type,array(5,8,9)))
                  writeRowContent($content,$site->id,$selected,$name);
              }

              print '</blockquote>';
            }
          }
        }

        print '</blockquote>';
      }
      else
      {
        print '<h4>' . $navi . '</h4><blockquote>';
        $sites = model_db_site::find()->where('navigation_id',$key)->order_by(array('sort'=>'ASC'))->get();
        if(!empty($sites))
        {
          print '<blockquote>';
          foreach($sites as $site) {
            $contents = model_db_content::find('all',array(
              'where' => array('site_id'=>$site->id),
              'order_by' => array('sort'=>'ASC')
            ));

            foreach($contents as $content)
            {
              if(!in_array($content->type,array(5,8,9)))
                writeRowContent($content,$site->id,$selected,$name);
            }
            print '</blockquote>';
          }
        }
        print '</blockquote>';
      }
    }
  }
}

  function writeRowContent($nav,$id,$selected,$name)
  {
    print '<div id="' . $nav->id . '" class="list_entry clearfix content_type_entry">';

    print '<span>';

    print '<strong>' . __('content.type.' . $nav->type) . '</strong>: ';

    print $nav->label;

    print '</span>';


    print '<div>';

    $attr = array('style'=>'width:auto;');

    if($selected == $nav->id)
      $attr['checked'] = 'checked';

    print Form::radio($name,$nav->id,$attr);

    print '</div>';

    print '</div>';
  }

  print Form::close();
?>