# tiny F(x2)
#### Smallest php Framework - with some more rocket fuel

*This is the 2015 version of the TinyFx php framework.*

This version comes with:
- Self and Minimal Configuration
- Improved schema based table and form builder
- Advanced form inputs such as Currency, Numeric, Richtext, File, Folder, Autocomplete etc..
- TinyMCE and FontAwsome integrated BoilerPlate templates
- Example App with User Management and Blogging

This version is not compatible with the previous version.
This version implements a new modular design pattern.
This is NOT an MVC framework. This is much easier to work with.
This is a completely different perspective
There are modules, interfaces and templates.


## Instructions - Getting Started
1. Check-out this repo to a folder under var/www/html or htdocs
2. Start Apache or WAMP
3. Create database from the rpovided database.sql
4. Make sure apache can write in to the directory (or you will have to manually copy the .htaccess and framework/config.php)
5. Navigate to http://localhost/tinyfx or wherever the folder you checked out this.
6. Provide database username/password, and modify default settings as needed.
7. configure.php will generate .hraccess and framework/config.php

- Your database settings are now in framework/config.php

- Navigate to http://localhost/tinyfx/admin Username:admin , Password:admin


## Module
A module is a section of a website, like Admin section, Dashboard, Shopping cart, Blog.
Each of these modules is put inside a single folder. There is a module controller for every module.
There can be many functions inside a module controller, and a view file corresponding to each of these functions.
An HTTP request will load a module, execute a function and render the view.


## Development
In the example app, you will find three modules: index, user and admin.
The module controller file name starts with an '@' and ends with the extension '.module.php'.
All the other files are views, if not starting with an underscore (_), in which case those are shared view partials.

Format: http://example.com/module/method/parameter/s

Eg: http://example.com/user/log-in/
- This http request will first load the module ~/modules/user/@user.module.php
- Then invoke the function log_in($params)
- If this was a POST request (user clicked "Log In" - submit), post fields (username/password) will be put in to $params


You can put a module inside a module, then it become a sub-module.

http://example.com/module/submodule/method/parameter/s

Eg: http://example.com/admin/users/edit/8
- This http request will load the module ~/modules/admin/users/@users.module.php
- Then invoke the function log_in($params) - **NOTICE the dash is converted to an underscore**
- Since this is a GET request, $params[0] will be 8


To move a website section into another master section, all you need to do is to move a single folder into another. (and edit any urls as necessory)

Also, you can copy a module from one project to another. On a Linux server, you can create a SymLink to share a module between two websites.


## URLs
We recommend using absolute URLs *Whenever Not Impossible*
You can use **BASE_URL** and **BASE_URL_STATIC** to refer to website root url.
Eg:

```php
<a href="<?= BASE_URL; ?>dashboard">Dashboard</a>

<img src="<?= BASE_URL_STATIC; ?>images/flower.jpg" />

<script src="<?= BASE_URL_STATIC; ?>js/script.js"></script>

```

**BASE_URL_STATIC** is defined in framework/config.php
You may later want to leverage static content to a cookieless subdomain.
If you use **BASE_URL_STATIC**, you only need to change this in one place.
Also, for uploading files to this static directory, use **STATIC_FILES_ROOT**.
This is the relative path from website root to static content path.


## How a request is processed
Just like the old TinyF(x), on HTTP requests, related module is loaded and the related method in the module is invoked.
The invoked method receives an array of HTTP parameters. It should return an associative array,
which will be converted to variables for the view. When the method returns, the related view is rendered,
then put in to the template as defined on the top of the module.

To help in processing the HTTP requests in modules, you can use various interfaces such as:
Database, ImageMagic, EMail, GCM etc..

To help in rendering HTML in views, there is a library of helper methods such as:
render_data_view, render_form, render_table, flash_message, render_dropdown, render_calendar, shorten_string, slugify, beautify_datetime

These are pretty self explainatory. You can look up all these on framework/render.php


## Database
For the first three data related view helpers you need to define data schema as follows:

```php
$pages_schema = array(
				'stub' 	=> array('Title', 		'key' => true),
				'en' 		=> array('English', 	'display' => 'richtext', 'table' => false),
				'ch' 		=> array('Chinese', 	'display' => 'richtext', 'table' => false),
				'slides' 	=> array('Slides', 		'display' => 'folder', 'path' => 'user/images/uploads/{stub}', 'table' => false),
				'edit' 		=> array('Edit', 		'form' => false, 'cmd' => 'admin/pages/{key}', 'default' => true),
				'view' 	=> array('View', 		'form' => false, 'cmd' => '{key}')
			);

```

To connect to the database from the module, all you need to call is:
```php
$db = connect_database();
```

This will connect to the database as defined in framework/config.php
If the primary key of a table is 'id' and set to auto_increment, this database abstraction object makes things like Insert, Update and Delete much easy.

For an example:
```php
// This could be a submitted form ($_POST becomes $params)
// $params = array('title' => 'Lorem ipsum', 'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit ...');
$db->insert('blog', $params);
//	INSERT INTO `blog`(`title`, `content`) VALUES('Lorem ipsum', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit ...')

// $params = array('id' => 4, 'password' => '86f7as54dfg8769...');
$db->update('user', $params);
//	UPDATE `user` SET `password` = '86f7as54dfg8769...' WHERE id = 4

$db->delete('blog', $params[0]);	//	$params[0] = 6 (from GET parameter 1 - http://example.com/admin/delete-blog-post/8)
//	DELETE FROM `blog` WHERE id = 6

```

