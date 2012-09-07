Portal Content Management System
====================
#### v: 1.00

Build on [Fuel Framework](https://github.com/fuel/fuel)<br />
Using:<br />
[Nivo-Slider](https://github.com/gilbitron/Nivo-Slider)<br />
[elRTE](https://github.com/Studio-42/elRTE)<br />
[elFinder](https://github.com/Studio-42/elFinder)<br />
[colorbox](https://github.com/jackmoore/colorbox)<br />
[html5boilerplate](https://github.com/h5bp/html5-boilerplate)<br />
[jquery.swfobject](http://jquery.thewikies.com/swfobject/)<br />
[pie](https://github.com/lojjic/PIE)<br />

Features:
---------------------
* Multilanguage interface
* Multilanguage site
* Multi-Navigation ( with every up to 1 hirachie down)
* News
* Page management
* Textcontainer ( up to 3 columns )
* Linking to existing contents ( up to 3 columns )
* Flash (using jquery.swfobject plugin with picture replacement)
* Simple contactform
* Gallery ( slideshow, thumbnail and customizeable)
* Content Stacking ( multiple contents in 1 page )
* Multi-Account
* Simple Permission System
* Module management
* Asset management
* Customizeable Layout

Development Testapp:
---------------------
[http://portalcms.hendrikweiler.com/admin](http://portalcms.hendrikweiler.com/admin)<br />
Username: admin<br />
Password: test<br />

Requirements:
---------------------
PHP 5.3

Install
---------------------
> 1. Download the files
> 2. Extract them into your root folder on your webserver
> 3. Install throught he install tool (http://localhost/projectname/public/admin/install)

Follow all three steps and login into (http://localhost/projectname/public/admin).<br />
*Notice*: you might have to create the bare database yourself.

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
print language_switcher(); //Prints a list of language versions out

//prints out all assets from the include area (public/assets/)
print asset_manager_insert('js'); // bundled javascript in one file
print asset_manager_insert('css'); // bundled css in one file
print asset_manager_get_group('jquery') // jquery package in one file

print layout_image('image.jpg') // display a picture from a layout (img folder from layout)

print asset_manager_get('js->include->modernizr'); // searches in include path after %modernizr% and prints it out
print asset_manager_get('img->admin->logo'); // searches in the img path after the portal logo and prints it out
</pre>

Writing CSS:
---------------------
Portal CMS comes with a light sass,less,stylus-like scripting system.<br />
*Notice:* The script will be parsed line for line so you cant comment after a variable definition. Everything in the code below is valid. There can be multiple scripts at any place like in php.

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

; below is the closing tag
&lt;*/

</pre>

#### Usage:
<pre>
body {
  background-color: site.$bg;
}

nav:hover {
  color: site.navigation.$hover;
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

Troubleshooting:
---------------------

##### Why do i get a redirection error if i want to take a look at my site?
You must create a navigation with a site + content in it first. Then the first navigation will be displayed as default.

##### Why do i get a warning message at the navigation?
Currently if you create navigations without creating a Site for it, it will appear.
To solve this problem create a Site for it or dont create unneeded navigations.

