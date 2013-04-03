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

function layout_image($filename)
{
	return '<img src="' . Uri::create('server/layout/' . $filename) . '" alt="layout_' . $filename . '" />';
}

function get_sub_navigation()
{
  return model_generator_sub_sites::get_html();
}

function show_sub_navigation($content)
{
  return model_generator_sub_sites::render($content);
}

function get_public_variables()
{
  return model_generator_preparer::$publicVariables;
}