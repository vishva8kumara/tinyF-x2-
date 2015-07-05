<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="robots" content="INDEX,FOLLOW" />
		<meta name="viewport" content="width=device-width" />
		<link rel="shortcut icon" type="image/x-icon" href="<?= BASE_URL_STATIC; ?>favicon.ico" />
		<title><?= isset($html_head['title']) ? $html_head['title'] : $lex[$lang]['title']; ?></title>
		<meta property="og:title" content="<?= isset($html_head['title']) ? $html_head['title'] : $lex[$lang]['title']; ?>" />
		<meta property="og:site_name" content="Website Name" />
<?php 	if (isset($html_head['description'])){ ?>
		<meta name="description" content="<?= $html_head['description']; ?>" />
		<meta property="og:description" content="<?= $html_head['description']; ?>" />
<?php 	} ?>
		<link rel="stylesheet" href="<?= BASE_URL_STATIC; ?>font-awesome/css/font-awesome.min.css" />
		<link rel="stylesheet" href="<?= BASE_URL_STATIC; ?>css/fonts.css" />
		<link rel="stylesheet" href="<?= BASE_URL_STATIC; ?>css/basic.css" />
		<link rel="stylesheet" href="<?= BASE_URL_STATIC; ?>css/admin.css" />
	</head>
	<body>
		<nav>
			<div class="container">
				<h1><a href="<?= BASE_URL; ?>"><?= $lex[$lang]['title']; ?></a></h1>
				<div class="right f-right">
<?php 	if (isset($user)){ ?>
					<a><?= $user['username']; ?></a> &nbsp;
					<a href="<?= BASE_URL; ?>user/sign-out">Log Out</a>
<?php 	} ?>
				</div>
				<a id="hamberger"></a>
				<ul class="nav">
					<li <?= $method == 'index' ? 'class="current"' : ''; ?>>
						<a href="<?= BASE_URL; ?>"><i class="fa fa-home"></i> <?= $lex[$lang]['home']; ?></a>
					</li>
					<li <?= $module == 'index' && $method == 'blog' ? 'class="current"' : ''; ?>>
						<a href="<?= BASE_URL; ?>blog"><i class="fa fa-newspaper-o"></i> <?= $lex[$lang]['blog']; ?></a>
					</li>
					<li <?= $method == 'about' ? 'class="current"' : ''; ?>>
						<a href="<?= BASE_URL; ?>about"><i class="fa fa-info-circle"></i> <?= $lex[$lang]['about']; ?></a>
					</li>
					<li <?= $method == 'contact' ? 'class="current"' : ''; ?>>
						<a href="<?= BASE_URL; ?>contact"><i class="fa fa-envelope-o"></i> <?= $lex[$lang]['contact']; ?></a>
					</li>
<?php 	if (isset($user)){ ?>
					<li <?= $module == 'user' && $method == 'blog' ? 'class="current"' : ''; ?>>
						<a href="<?= BASE_URL; ?>user/blog"><i class="fa fa-newspaper-o"></i> <?= $lex[$lang]['my-blog']; ?></a>
					</li>
<?php 	} ?>
				</ul>
			</div>
		</nav>
		<main class="container">
<?php flash_message_dump(); ?>
<?= $yield; ?><br class="clear-both" /><br/>
		</main>
		<footer>
			<div class="container">
				&copy; From Year - <?= date('Y'); ?> All rights reserved &nbsp; | &nbsp; Company Name
				<div class="right f-right">
					<a href="<?= BASE_URL; ?>privacy_policy">Privacy Policy</a>
					&nbsp; | &nbsp;
					<a href="<?= BASE_URL; ?>terms_of_use">Terms of Use</a>
					&nbsp; | &nbsp;
					<a href="<?= BASE_URL; ?>contact"><?= $lex[$lang]['contact']; ?></a>
				</div>
				<ul class="social-links">
					<li><a href="https://www.linkedin.com/company/example" target="_blank"><img src="<?= BASE_URL_STATIC; ?>linkedin.png" /></a></li>
					<li><a href="https://plus.google.com/333" target="_blank"><img src="<?= BASE_URL_STATIC; ?>googleplus.png" /></a></li>
				</ul>
			</div>
		</footer>
		<script src="<?= BASE_URL_STATIC; ?>js/script.js"></script>
	</body>
</html>