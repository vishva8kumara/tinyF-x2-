<html>
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="robots" content="NOFOLLOW" />
		<meta name="viewport" content="width=device-width" />
		<link rel="shortcut icon" type="image/x-icon" href="<?= BASE_URL_STATIC; ?>favicon.ico" />
		<title><?= isset($html_head['title']) ? $html_head['title'] : 'Admin Dashboard'; ?></title>
		<link rel="stylesheet" type="text/css" href="<?= BASE_URL_STATIC; ?>css/basic.css" />
		<link rel="stylesheet" type="text/css" href="<?= BASE_URL_STATIC; ?>css/admin.css" />
		<link rel="stylesheet" href="<?= BASE_URL_STATIC; ?>font-awesome/css/font-awesome.min.css" />
	</head>
	<body>
		<nav>
			<div class="container">
				<h2>Admin Dashboard</h2>
<?php if (isset($user) && $user['level'] > 4){ ?>
				<ul class="nav">
					<li><a href="<?= BASE_URL; ?>admin/pages">Pages</a></li>
					<li><a href="<?= BASE_URL; ?>admin/inquiry">Inquiry</a></li>
					<li><a href="<?= BASE_URL; ?>admin/users">Users</a></li>
					<li><a href="<?= BASE_URL; ?>user/sign-out">Log Out</a></li>
				</ul>
<?php } ?>
			</div>
		</nav>
		<main class="container">
<?php flash_message_dump(); ?>
<?= $yield; ?>
		</main>
		<script src="<?= BASE_URL_STATIC; ?>js/script.js"></script>
	</body>
</html>