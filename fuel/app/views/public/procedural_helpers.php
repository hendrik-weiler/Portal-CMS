<?php

function seo($type='all')
{
  return model_generator_seo::render($type);
}

function navigation($group_id)
{
  return model_generator_navigation::render($group_id);
}

function content()
{
  return model_generator_content::render();
}

function content_single($name)
{
  return model_generator_content::renderContent($name);
}

function content_site($name)
{
  return model_generator_content::renderSite($name);
}

function language_switcher()
{
  return model_generator_tools::viewLanguageSelection();
}

function asset_manager_insert($type)
{
  return Asset\Manager::insert($type);
}

function asset_manager_get($path,$attr=array())
{
  return Asset\Manager::get($path,$attr);
}

function asset_manager_get_group($path)
{
  return Asset\Manager::getGroup($path);
}