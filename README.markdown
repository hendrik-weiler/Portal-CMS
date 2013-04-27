Portal Content Management System
====================
#### v: 1.036 dev

Stable versions:
---------------------
[Download a copy of 1.35](http://portalcms.hendrikweiler.com/pcms-1.35.zip)<br />
<br />
Build on [Fuel Framework](https://github.com/fuel/fuel)<br />
Using:<br />
[Nivo-Slider](https://github.com/gilbitron/Nivo-Slider)<br />
[tinymce](https://github.com/tinymce)<br />
[colorbox](https://github.com/jackmoore/colorbox)<br />
[html5boilerplate](https://github.com/h5bp/html5-boilerplate)<br />
[swfobject](https://code.google.com/p/swfobject/)<br />
[pie](https://github.com/lojjic/PIE)<br />
[jquery.hotkeys](http://github.com/jeresig/jquery.hotkeys)<br />
[sutra](https://github.com/Tatsh/sutra)<br />
[spectrum](https://github.com/bgrins/spectrum)<br />

Features:
---------------------
* Multilanguage interface
* Multilanguage site
* Multi-Navigation ( with every to 1 hirachie down)
* News
* Page management
* Textcontainer ( up to 3 columns )
* Linking to existing contents ( up to 3 columns )
* Flash (using jquery.swfobject plugin with picture replacement)
* Simple contactform
* Gallery ( slideshow, thumbnail and customizeable)
* FLV Video Player
* Content Stacking ( multiple contents in 1 page )
* Multi-Account
* Simple Permission System
* Module management
* Asset management
* Customizeable Layout
* Supersearch
* Actionarea

Development Testapp:
---------------------
[http://portalcms.hendrikweiler.com/admin](http://portalcms.hendrikweiler.com/admin)<br />
Username: admin<br />
Password: test<br />

Requirements:
---------------------
PHP 5.3<br />
fsockopen ( else manually updating )<br />
mod_rewrite


Backend Tested in:
---------------------
- Safari
- Chrome
- Opera
- Firefox
- IE 9

Install
---------------------
> 1. Download the files
> 2. Extract them into your root folder on your webserver
> 3. Install throught he install tool (http://domain/admin/install)

if you get message like "install tool disabled" go to "yoursitefolder/fuel/app/" and delete the file : "INSTALL_TOOL_DISABLED"

Follow all three steps and login into (http://domain/admin).<br />
*Notice*: you might have to create the bare database yourself.

You need to setup a virtual server, if you are working locally:<br>
Windows: [http://www.uwamp.com](Uwamp)<br>
OSX: [http://www.mamp.info/de/mamp-pro/](Mamp Pro)<br>

If you set the destinated path, make sure your path includes the public folder at the end
(/projectname/public).

Snippet for inside of the httpd-vhosts.conf:<br>
<pre>
&lt;VirtualHost *:80&gt;
  ServerName portalcms.hendrikweiler.com
  SetEnv FUEL_ENV production
  DocumentRoot "/sites/portalcms.hendrikweiler.com/public"
&lt;/VirtualHost&gt;
</pre>

Actionarea:
---------------------
In the actionarea you can looking for the task you want to do and either get a shortcut link
to the place where it will be done or you get a "learn how" link where you will be guided to
what needs to be done.


Supersearch:
---------------------
With supersearch you can search the entire cms restricted to current siteversion your in after
contents, sites, news, accounts and tasks (from the actionarea).

Shortcuts (works almost everywhere):<br />
<strong>Shift + r</strong> = Supersearch new search<br />
<strong>Shift + e</strong> = Supersearch category "all" search<br />
<strong>Shift + c</strong> = Supersearch category "contents" search<br />
<strong>Shift + t</strong> = Supersearch category "tasks" search<br />
<strong>Shift + n</strong> = Supersearch category "news" search<br />
<strong>Shift + a</strong> = Supersearch category "accounts" search<br />
<strong>Shift + s</strong> = Supersearch category "sites" search<br />

You will see the that the tasks have like special options, for example "show only navigations with with sub entries".
You can search in the supersearch textfield like that:
=myoption1;myoption2;myoption3

these options exist now:
> no_main ( excludes points with sub entries )<br />
> main_points ( shows only points with sub entries )

Templating:
---------------------
Portal got a simple templating system. You can find all templates in "fuel/app/views/public/template".

Within the index.php a folder before you can design your site.
There a few generators you need to know if you want to work with it.

<pre>
//All searchoptimation will be given out
print seo('head'); // meta tags printout
print seo('analytics'); // analytics printout

print navigation(); //Prints out the navigation
print content(); //Prints site contents out
print show_sub_navigation($content) // prints out the content with sidebar (if in navigationpoint set to active)
print content_single($id_or_name,$language); // Render a single content
print content_site($id_or_name,$language); // Render a single site
print get_sub_navigation() // Displays subnavigation
print language_switcher(); //Prints a list of language versions out

//prints out all assets from the include area (public/assets/)
print asset_manager_insert('js'); // bundled javascript in one file
print asset_manager_insert('css'); // bundled css in one file
print asset_manager_get_group('jquery') // jquery package in one file

print layout_image('image.jpg') // display a picture from a layout (img folder from layout)

print asset_manager_get('js->include->modernizr'); // searches in include path after %modernizr% and prints it out
print asset_manager_get('img->admin->logo'); // searches in the img path after the portal logo and prints it out

var_dump(get_public_variables()); // Receive all to public open variables like (current navigation text-color and background, current content count, current language prefix, etc..)
</pre>

Layouts:
---------------------
Portal CMS is able to use Layouts/Themes. In 'Advanced settings' in tab 'Layout' you can change
the current Layout with another one.

All layouts are located at 'root/layouts'.

Writing CSS:
---------------------
Portal CMS comes with a light sass,less,stylus-like scripting system.<br />
*Notice:* The script will be parsed line for line so you cant comment after a variable definition. Everything in the code below is valid. There can be multiple scripts at any place like in php.

*New (as of 1.36):* You have now the possibility to add a script attribute to everything (example shown below). Inside that script closure you can freely write javascript (in combination with jquery and all other added javascript files from the layout!).<br />
Keywords:<br />
self = The selector you are inside (.footer, body, p)<br />
*Notice:* Up until now you have to refresh the site twice to get the written javascript affecting the site

#### Syntax:
<pre>
/*&gt;
; above is the opening tag
; this is a comment

; making a variable with permanent root folder in it
; will represent similiar to this http://localhost/portalcms/public
$root = "DOCROOT"

; using pie
$pie = "behavior:url(PIEPATH)"

$im_a_variable = "i contain any possible value"
im_also_a_variable = 'i contain another value'
[even_this_is_a_variable] = and im a value

; now creating c++ like structs/objects with properties
obj site
  $bg = "#ccc"
  ; you can nest them
  obj navigation
    $hover = "#cc0005"
  end
end

; this link will serve a image from the layout folder
$background = DOCROOT/server/layout/assets/img/bg.png

; below is the closing tag
&lt;*/

</pre>

#### Usage:
<pre>
body {
  background-color: site.$bg;
}

/* script without selector */
script : {
  /* its like im in a normal javascript file */
}

nav:hover {
  color: site.navigation.$hover;
  script : {
    /* a comment, single line comments wont work */
    self.animate({
      background : 'black'
    },1000);
  }
}

p:after {
  content: "$im_a_variable";
  border-radius: 10px;
  $pie;
}
</pre>

Using Flash Content notice:
---------------------
Within the parameters you can give the flash file, there are two keywords avaible:

* $language[extension] - Current site language with given extension e.g ( de.jpg, en.swf )
* $sitename[extension] - Current site name with given extension e.g ( home.jpg, products.swf )

Example:
<pre>
name = Picturebox
picture = $sitename[jpg]
</pre>

How to use the template-content-type:
---------------------
After you have created a template.php file located in "root/layouts/yourlayout/custom_templates/custom" you can write down your markup.<br>
Inside this file you can easily print out some predefined variables:<br>
- @$tpl_text_mytext1 (adds a textfield with wysiwig editor in the backend)
- @$tpl_rawtext_myrawtext1 (adds a textfield without wysiwig editor in the backend)
- @$tpl_file_myfile (adds a field for uploading a file in the backend)
The last part of these variablenames are variable themself. So you can change it for whatever description.

Troubleshooting:
---------------------

##### Why do i get a redirection error if i want to take a look at my site?
You must create a navigationpoint with a content in it first. Then the first navigation will be displayed as default.

##### Why do i get an error when im updating?
You have to set the complete folder in mode 777.<br />
(e.g linux: sudo chmod -R 777 foldername)
