<?php
return array(
	'_root_'  => 'public/index',  // The default route
	'_404_'   => 'welcome/404',    // The main 404 route

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

	'admin/advanced/layout/preview/:path' => 'advanced/advanced/layout_image',
	'admin/advanced/layout/edit' => 'advanced/advanced/layout_edit',
	'admin/advanced/layout/choose' => 'advanced/advanced/layout_choose',
	'admin/advanced/layout' => 'advanced/advanced/layout',

	'admin/advanced/edit' => 'advanced/advanced/edit',
	'admin/advanced' => 'advanced/advanced/index',

	# -- admin/content

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

	'admin/content/add/:id' => 'pages/content/add',

	# -- admin/news

	'admin/news/picture/delete/:id/:picture' => 'news/news/picture',
	'admin/news/delete/:id' => 'news/news/delete',
	'admin/news/edit/:id' => 'news/news/edit',
	'admin/news/add' => 'news/news/add',
	'admin/news' => 'news/news/index',

	# -- admin/sites

	'admin/sites/order/update' => 'pages/pages/order',
	'admin/sites/delete/:id' => 'pages/pages/delete',
	'admin/sites/edit/:id' => 'pages/pages/edit',
	'admin/sites/add' => 'pages/pages/add',
	'admin/sites/:group' => 'pages/pages/index',
	'admin/sites' => 'pages/pages/index',

	# -- admin/navigation

	'admin/navigation/group/edit' => 'navigation/navigation/group_edit',
	'admin/navigation/group/new' => 'navigation/navigation/group_new',
	'admin/navigation/group/delete' => 'navigation/navigation/group_delete',

	'admin/navigation/order/update' => 'navigation/navigation/order',
	'admin/navigation/delete/:id' => 'navigation/navigation/delete',
	'admin/navigation/edit/:id' => 'navigation/navigation/edit',
	'admin/navigation/add' => 'navigation/navigation/add',
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

	'elfinder/connector' => 'elfinder/connector',
	'elfinder/(:any)' => 'elfinder/connector$1',

	'admin/update/version/:lang' => 'version/update',

	'admin/login' => 'login/login',
	'admin/logout' => 'login/logout',
	'admin' => 'login/index',

	# -- frontend

	'layout/picture/:path' => 'advanced/advanced/layout_image',

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