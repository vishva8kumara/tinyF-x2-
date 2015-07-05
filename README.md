# tiny F(x2)
Smallest php Framework - with some more rocket fuel

This is the 2015 version of the TinyFx php framework.

This version comes with:
* Self and Minimal Configuration
* Improved schema based table and form builder
* Advanced form inputs such as Currency, Numeric, Richtext, File, Folder, Autocomplete etc..
* TinyMCE and FontAwsome integrated BoilerPlate templates
* Example App with User Management and Blogging

This version is not compatible with the previous version.
This version implements a new modular design pattern.
There are modules, interfaces and templates.

In the example app, you will find three modules: index, user and admin.
The module controller file name starts with an '@' and ends with the extension '.module.php'.
All the other files are views, if not starting with an underscore (_)

http://example.com/module/method/parameter/s

You can put a module inside a module, then it become a sub-module.

http://example.com/module/submodule/method/parameter/s

== Topology ==
Just like the old TinyF(x), on HTTP requests, related module is loaded and the related method in the module is invoked.
The invoked method receives an array of HTTP parameters. It should return an associative array,
which will be converted to variables for the view. When the method returns, the related view is rendered,
then put in to the template as defined on the top of the module.

To help in processing the HTTP requests in modules, you can use various interfaces such as:
Database, ImageMagic, EMail, GCM etc..

To help in rendering HTML in views, there is a library of helper methods such as:
render_data_view, render_form, render_table, flash_message, render_dropdown, render_calendar, shorten_string, slugify, beautify_datetime

These are pretty self explainatory. You can look up all these on framework/render.php

For the first three data related view helpers you need to define data schema as follows:
$pages_schema = array(
				'stub' 	=> array('Title', 		'key' => true),
				'en' 		=> array('English', 	'display' => 'richtext', 'table' => false),
				'ch' 		=> array('Chinese', 	'display' => 'richtext', 'table' => false),
				'slides' 	=> array('Slides', 	'display' => 'folder', 'path' => 'user/images/uploads/{stub}', 'table' => false),
				'edit' 		=> array('Edit', 		'form' => false, 'cmd' => 'admin/pages/{key}', 'default' => true),
				'view' 	=> array('View', 		'form' => false, 'cmd' => '{key}')
			);
