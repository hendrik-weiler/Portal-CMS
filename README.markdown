Portal Content Management System
====================
#### Beta

Build on [Fuel Framework](https://github.com/fuel/fuel)
Using:
[Nivo-Slider](https://github.com/gilbitron/Nivo-Slider)
[elRTE](https://github.com/Studio-42/elRTE)
[elFinder](https://github.com/Studio-42/elFinder)
[colorbox](https://github.com/jackmoore/colorbox)
[html5boilerplate](https://github.com/h5bp/html5-boilerplate)
[jquery.swfobject](http://jquery.thewikies.com/swfobject/)
[pie](https://github.com/lojjic/PIE)

Features:
---------------------
* Multilanguage interface
* Multilanguage site
* Navigation ( up to 1 hirachie down)
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
print model_generator_seo::render(); //All searchoptimation will be given out
print model_generator_navigation::render(); //Prints out the navigation
print model_generator_content::render(); //Prints site contents out
print model_generator_content::renderContent($id_or_name,$language); // Render a single content
print model_generator_content::renderSite($id_or_name,$language); // Render a single site
print model_generator_tools::viewLanguageSelection(); //Prints a list of language versions out

//prints out all assets from the include area (public/assets/)
print Asset\Manager::insert('js'); // public/assets/js/include
print Asset\Manager::insert('css'); // public/assets/css/include
print Asset\Manager::insert(); // prints out js,css

print Asset\Manager::get('js->include->modernizr'); // searches in include path after %modernizr% and prints it out
print Asset\Manager::get('img->admin->logo'); // searches in the img path after the portal logo and prints it out
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

Troubleshooting:
---------------------

##### Why do i get a redirection error if i want to take a look at my site?
You must create a navigation with a site + content in it first. Then the first navigation will be displayed as default.

