<?php

function seo($type='all')
{
  return model_generator_seo::render($type);
}

function navigation()
{
  return model_generator_navigation::render();
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

function asset_manager_get($path)
{
  return Asset\Manager::get($path);
}

function asset_manager_get_group($path)
{
  return Asset\Manager::getGroup($path);
}