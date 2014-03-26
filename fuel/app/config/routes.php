<?php
return array(
	'_root_'  => 'public/index',  // The default route
	'_404_'   => 'welcome/404',    // The main 404 route

	# -- admin/siteselector

	'admin/siteselector/data' => 'siteselector/siteselector/get_data',

	# -- admin/shop

	'admin/shop/settings/edit' => 'shop/settings/edit',
	'admin/shop/settings' => 'shop/settings/index',

	'admin/shop/orders/accept/:id' => 'shop/order/accept',
	'admin/shop/orders/cancel/:id' => 'shop/order/cancel',
	'admin/shop/orders/display/invoice/:id' => 'shop/order/display_mail',
	'admin/shop/orders/display/:id' => 'shop/order/display',
	'admin/shop/orders' => 'shop/order/index',

	'admin/shop/tax/delete/:id' => 'shop/tax/delete',
	'admin/shop/tax/edit/:id' => 'shop/tax/edit',
	'admin/shop/tax/add' => 'shop/tax/add',
	'admin/shop/tax' => 'shop/tax/index',

	'admin/shop/groups/delete/:id' => 'shop/group/delete',
	'admin/shop/groups/edit/:id' => 'shop/group/edit',
	'admin/shop/groups/add' => 'shop/group/add',
	'admin/shop/groups' => 'shop/group/index',

	'admin/shop/articles/delete/:id/picture/:index' => 'shop/article/delete_picture',
	'admin/shop/articles/delete/:id' => 'shop/article/delete',
	'admin/shop/articles/edit/:id' => 'shop/article/edit',
	'admin/shop/articles/add' => 'shop/article/add',
	'admin/shop/articles' => 'shop/article/index',

	# -- admin/picturemanager

	'admin/content/picturemanager/own_pictures/delete_image' => 'picturemanager/picturemanager/own_pictures_delete',
	'admin/picturemanager/own_pictures/add' => 'picturemanager/picturemanager/own_pictures_add',
	'admin/picturemanager/own_pictures' => 'picturemanager/picturemanager/own_pictures',
	'admin/picturemanager/galleries' => 'picturemanager/picturemanager/galleries',
	'admin/picturemanager/news' => 'picturemanager/picturemanager/news',

	# -- admin/supersearch

	'admin/supersearch/all' => 'supersearch/supersearch/all',
	'admin/supersearch/content' => 'supersearch/supersearch/content',
	'admin/supersearch/sites' => 'supersearch/supersearch/sites',
	'admin/supersearch/news' => 'supersearch/supersearch/news',
	'admin/supersearch/accounts' => 'supersearch/supersearch/accounts',
	'admin/supersearch/tasks' => 'supersearch/supersearch/tasks',

	# -- admin/dashboard

	'admin/dashboard' => 'dashboard/dashboard/index',

	# -- admin/sites

	'admin/accounts/delete/:id' => 'advanced/accounts/delete',
	'admin/accounts/edit/:id' => 'advanced/accounts/edit',
	'admin/accounts/add' => 'advanced/accounts/add',

	# -- admin/advanced

	'admin/advanced/update/execute/manually' => 'advanced/update/execute_manually',
	'admin/advanced/update/execute' => 'advanced/update/execute',
	'admin/advanced/update' => 'advanced/update/index',

	'admin/advanced/layout/preview/:path' => 'advanced/advanced/layout_image',
	'admin/advanced/layout/edit' => 'advanced/advanced/layout_edit',
	'admin/advanced/layout/choose' => 'advanced/advanced/layout_choose',
	'admin/advanced/layout' => 'advanced/advanced/layout',

	'admin/advanced/import' => 'advanced/import/index',
	'admin/advanced/import/check' => 'advanced/import/check',

	'admin/advanced/edit' => 'advanced/advanced/edit',
	'admin/advanced' => 'advanced/advanced/index',

	# -- admin/content

	'admin/content/gallery/:id/order/update' => 'pages/content/update_gal_order',
	'admin/content/gallery/delete' => 'pages/content/delete_gal_picture',
	'admin/content/order/update' => 'pages/content/order',
	'admin/content/delete/:id' => 'pages/content/delete',

	'admin/content/:id/edit/:content_id/type/1' => 'pages/content/type1',
	'admin/content/:id/edit/:content_id/type/2' => 'pages/content/type2',
	'admin/content/:id/edit/:content_id/type/3' => 'pages/content/type3',
	'admin/content/:id/edit/:content_id/type/4' => 'pages/content/type4',
	'admin/content/:id/edit/:content_id/type/5' => 'pages/content/type5',
	'admin/content/:id/edit/:content_id/type/6' => 'pages/content/type6',
	'admin/content/:id/edit/:content_id/type/7' => 'pages/content/type7',
	'admin/content/:id/edit/:content_id/type/8' => 'pages/content/type8',
	'admin/content/:id/edit/:content_id/type/9' => 'pages/content/type9',
	'admin/content/:id/edit/:content_id/type/10' => 'pages/content/type10',
  'admin/content/:id/edit/:content_id/type/11' => 'pages/content/type11',
  'admin/content/:id/edit/:content_id/type/12' => 'pages/content/type12',

  'admin/content/:id/edit/:content_id/type/13' => 'pages/content/type13',
  'admin/content/:id/edit/:content_id/type/13/edit' => 'pages/content/type13/edit',
  'admin/content/:id/edit/:content_id/type/13/preview' => 'pages/content/type13/preview',

  'admin/content/:id/edit/:content_id/type/14' => 'pages/content/type14',
  'admin/content/:id/edit/:content_id/type/14/edit' => 'pages/content/type14/edit',
  'admin/content/:id/edit/:content_id/type/14/preview' => 'pages/content/type14/preview',
  'admin/content/:id/edit/:content_id/type/14/save/skin/:skinname' => 'pages/content/type14/save_skin',

  'admin/content/:id/edit/:content_id/type/15' => 'pages/content/type15',
  'admin/content/:id/edit/:content_id/type/15/edit' => 'pages/content/type15/edit',

	'admin/content/add/:id' => 'pages/content/add',

	# -- admin/news

	'admin/news/picture/delete/:id/:picture' => 'news/news/picture',
	'admin/news/delete/:id' => 'news/news/delete',
	'admin/news/edit/:id' => 'news/news/edit',
	'admin/news/add' => 'news/news/add',
	'admin/news' => 'news/news/index',

	# -- admin/sites

	'admin/sites/classnames/update' => 'pages/pages/classnames',

	'admin/sites/order/update' => 'pages/pages/order',
	'admin/sites/delete/:id' => 'pages/pages/delete',
    'admin/sites/edit/:id/delete/image' => 'pages/pages/delete_image',
	'admin/sites/edit/:id' => 'pages/pages/edit',
	'admin/sites/add' => 'pages/pages/add',
	'admin/sites/:group' => 'pages/pages/index',
	'admin/sites' => 'pages/pages/index',

	# -- admin/navigation

	'admin/navigation/group/action' => 'navigation/navigation/group',
	'admin/navigation/group/edit' => 'navigation/navigation/group_edit',
	'admin/navigation/group/new' => 'navigation/navigation/group_new',
	'admin/navigation/group/delete' => 'navigation/navigation/group_delete',

	'admin/navigation/order/update' => 'navigation/navigation/order',
	'admin/navigation/delete/:id' => 'navigation/navigation/delete',
	'admin/navigation/edit/:id' => 'navigation/navigation/edit',
	'admin/navigation/add' => 'navigation/navigation/add',
	'admin/navigation/:group/:main/:sub' => 'navigation/navigation/index_main_sub',
	'admin/navigation/:group/:main' => 'navigation/navigation/index_main',
	'admin/navigation/:group' => 'navigation/navigation/index',
	'admin/navigation' => 'navigation/navigation/index',

	# -- admin/settings

	'admin/settings/edit' => 'settings/settings/edit',
	'admin/settings' => 'settings/settings/index',

	# -- admin/language

	'admin/language/delete/:id' => 'language/language/delete',
	'admin/language/edit/:id' => 'language/language/edit',
	'admin/language/order/update' => 'language/language/order',
	'admin/language/add' => 'language/language/add',
	'admin/language' => 'language/language/index',

	# -- install tool

	'admin/install/lang/:lang' => 'install/tool/lang',

	'admin/install' => 'install/tool/index',
	'admin/install/1' => 'install/tool/index',
	'admin/install/2' => 'install/tool/step2',
	'admin/install/3' => 'install/tool/step3',

	# -- admin

	'admin/inlineedit/lang/change/:lang' => 'inlineedit/change_language',

	'elfinder/connector' => 'elfinder/connector',
	'elfinder/(:any)' => 'elfinder/connector$1',

	'admin/update/version/:lang' => 'version/update',

	'admin/login' => 'login/login',
	'admin/clear_cache' => 'login/clear_cache',
	'admin/logout' => 'login/logout',
	'admin' => 'login/index',

	# -- frontend

	':lang/cart/step/2' => 'public/index',
	':lang/cart/step/1' => 'public/index',
	':lang/cart/overview' => 'public/index',
	':lang/cart/remove/:index' => 'public/cart_remove',

	':main/:sub/product/:id/:product_name' => 'public/index',
	':main/product/:id/:product_name' => 'public/index',

	':lang/:main/:sub/product/:id/:product_name' => 'public/index',
	':lang/:main/product/:id/:product_name' => 'public/index',
	':lang/product/:id/:product_name' => 'public/index',
	'product/:id/:product_name' => 'public/index',
	

	'layout/picture/:path' => 'advanced/advanced/layout_image',
	'player/serve/video/:videoname' => 'pages/content/type14/serve_video',
	'player/serve/skin/:skinname' => 'pages/content/type14/serve_skin',
	'player/serve/player' => 'pages/content/type14/serve_player',

	'sitemap.xml' => 'sitemap/index',
	'parse/file/:path' => 'generator/file/index',
	'parse/js/:cmd' => 'generator/file/js',
	'parse/js' => 'generator/file/js',
	'parse/css' => 'generator/file/css',

	':lang/news' => 'public/index',
	':lang/news/archive' => 'public/index',
	':lang/news/:id/:title' => 'public/index',

	':lang/:main/:sub' => 'public/index',
	':lang/:main' => 'public/index',
	':lang' => 'public/index',
);