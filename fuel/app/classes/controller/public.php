<?php

class Controller_Public extends Controller
{
	public function action_index()
	{
            model_generator_preparer::initialize();
            model_generator_module::checkStatus();

            // Initiate site caching

            if(model_db_option::getKey('site_caching')->value)
            {

                  $cache_name = implode('-', Uri::segments());

                  if(model_generator_preparer::$isMainLanguage && empty($cache_name))
                  {
                        $uri = array();
                        if(is_object(model_generator_preparer::$currentMainNav))
                        $uri[] = model_generator_preparer::$currentMainNav->url_title;

                        if(is_object(model_generator_preparer::$currentSubNav))
                        $uri[] = model_generator_preparer::$currentSubNav->url_title;
                  
                        $cache_name = implode('-', $uri);
                  }

                  $cache_path = APPPATH . '/cache/' . $cache_name . '.cache';

                  $siteTime = time();
                  $cache_created = 0;
                  if(file_exists($cache_path))
                  {
                        if(!is_object(model_generator_preparer::$currentSite))
                        {
                              model_generator_preparer::$currentSite = new model_db_site();
                              model_generator_preparer::$currentSite->changed = 0;
                        }

                        $cache_created = file_get_contents($cache_path);
                        
                        $cache_created = explode(PHP_EOL, $cache_created);
                        $cache_created = str_replace(array('{{Fuel_Cache_Properties}}','{{/Fuel_Cache_Properties}}'), '', $cache_created[0]);
                        $cache_created = explode(',', $cache_created);
                        $cache_created = str_replace('{"created":', '', $cache_created[0]);
                        
                        $siteTime = strtotime(model_generator_preparer::$currentSite->changed);
                  }

                  try
                  {
                        if($cache_created < $siteTime)
                        {
                              $this->response->body = View::forge('public/procedural_helpers');
                              $this->response->body .= model_generator_layout::render();
                              Cache::delete($cache_name);
                              Cache::set($cache_name, $this->response->body);
                        }
                        else
                        {
                              $cache_data = Cache::get($cache_name);
                              $this->response->body = $cache_data;
                        }

                  }
                  catch (\CacheNotFoundException $e)
                  {
                        $this->response->body = View::forge('public/procedural_helpers');
                        $this->response->body .= model_generator_layout::render();
                        Cache::set($cache_name, $this->response->body);
                  }
            }
            else
            {
                  $this->response->body = View::forge('public/procedural_helpers');
                  $this->response->body .= model_generator_layout::render();
            }


	    if(model_db_option::getKey('inline_edit')->value && model_auth::check())
	    {
	      $inline_edit_html = Asset\Manager::get('js->inline_edit');
            $this->response->body = str_replace('</head>',
                  '<script>
                  var base_url = "' . Uri::create('/') . '";
                  var current_language = "' . model_generator_preparer::$publicVariables['current_language'] . '";
                  var inline_edit_language = "' . str_replace(array('/','\\'),'',model_auth::$user->language) . '";
                  </script>' 
                  . $inline_edit_html . '</head>', 
            $this->response->body);
	    }
           
	}
}

/* End of file welcome.php */