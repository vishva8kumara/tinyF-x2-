<?php 	global $static_files_root;
		$files_path = $static_files_root.$path.'/';
		$slides = array();
		if (is_dir($files_path) && $dh = opendir($files_path)){
			while (($file = readdir($dh)) !== false){
				if ($file == '.' || $file == '..' || is_dir($files_path.'/'.$file)){
				}
				else if (substr($file, 0, 6) == 'thumb_'){
					$slides[] = substr($file, 6);
				}
			}
			closedir($dh);
		}
		if (count($slides) > 0){ ?>
<script src="<?= $base_url_static; ?>js/slides.js"></script>
<link rel="stylesheet" type="text/css" href="<?= $base_url_static; ?>css/slides.css" />
<ul class="slides">
<?php 		foreach ($slides as $slide){ ?>
	<li><img src="<?= $base_url_static; ?><?= $path; ?>/<?= $slide; ?>" /></li>
<?php 		} ?>
</ul>
<script>new slideShow(document.querySelectorAll('ul.slides')[0]);</script>
<?php 	} ?>
