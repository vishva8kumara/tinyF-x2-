<?php

if (isset($_POST['htaccess']) && isset($_POST['config'])){
	file_put_contents('.htaccess', $_POST['htaccess']);
	file_put_contents('framework/config.php', $_POST['config']);
}
else{
	$path = rtrim($_SERVER['REQUEST_URI'], 'configure.php');
	$server = $_SERVER['SERVER_NAME'];
}

?>
<h1>TinyF(x)</h1>
<h2>Configuration</h2>
<form name="config" method="post">
	<table width="100%">
		<tr>
			<td valign="top">
	<h3>Path</h3>
	<table>
		<tr>
			<td>PATH</td>
			<td><input type="text" name="path" value="<?= $path; ?>" /></td>
		</tr>
		<tr>
			<td>BASE_URL_STATIC</td>
			<td><input type="text" name="base_url_static" value="http://<?= $server.$path; ?>static/" /></td>
		</tr>
		<tr>
			<td>STATIC_FILES_ROOT</td>
			<td><input type="text" name="static_files_root" value="./static/" /></td>
		</tr>
	</table>
	<br/>
	<h3>Database</h3>
	<table>
		<tr>
			<td>DB_HOST</td>
			<td><input type="text" name="db_host" value="localhost" /></td>
		</tr>
		<tr>
			<td>DB_NAME</td>
			<td><input type="text" name="db_name" value="database" /></td>
		</tr>
		<tr>
			<td>DB_USER</td>
			<td><input type="text" name="db_user" value="root" /></td>
		</tr>
		<tr>
			<td>DB_PASS</td>
			<td><input type="text" name="db_pass" value="" /></td>
		</tr>
	</table>
	<p>You are seeing this because you need to configure your web application.</p>
	<p>If you do not understand, just hit save with these default configurations.</p>
	<p>Once you configure, remove this 'configure.php' file from the site root.</p>
			</td>
			<td>&nbsp;&nbsp;</td>
			<td>
	<h3>Config Files</h3>
	.htaccess<br/>
	<textarea name="htaccess" rows="5"></textarea>
	<br/><br/>
	config.php<br/>
	<textarea name="config" rows="13"></textarea>
	<br/><br/>
	<input type="submit" value="Save" />
			</td>
		</tr>
	</table>
</form>
<script>
	function generate_files(){
		document.config.htaccess.value =
			'RewriteEngine On\n'+
			'RewriteBase '+document.config.path.value+'\n'+
			'RewriteCond %{REQUEST_URI} !^'+document.config.path.value+(document.config.static_files_root.value.substring(2))+'\n'+
			'RewriteRule (.*)$ index.php [L]\n';
		//
		document.config.config.value =
			'<'+'?php\n'+
			'define (\'PATH\', \''+document.config.path.value+'\');\n'+
			'define (\'BASE_URL_STATIC\', \''+document.config.base_url_static.value+'\');\n'+
			'define (\'STATIC_FILES_ROOT\', \''+document.config.static_files_root.value+'\');\n'+
			'\n'+
			'// Database settings\n'+
			'define (\'DB_HOST\', \''+document.config.db_host.value+'\');\n'+
			'define (\'DB_NAME\', \''+document.config.db_name.value+'\');\n'+
			'define (\'DB_USER\', \''+document.config.db_user.value+'\');\n'+
			'define (\'DB_PASS\', \''+document.config.db_pass.value+'\');\n'+
			'\n'+
			'?>';
	}
	for (var i = 0; i < document.config.elements.length; i++){
		document.config.elements[i].onkeyup = generate_files;
	}
	generate_files();
</script>
<style>
	input[type="text"]{
		width:300px;
	}
	textarea{
		width:500px;
	}
	tr td:first-child{
		width:162px;
	}
</style>
<?php die(); ?>
