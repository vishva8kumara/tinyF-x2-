<?php if (isset($article)){ ?>

<h2><?= $article['title']; ?></h2>
<small><?= beautify_datetime($article['published']); ?></small>
<?= $article['content']; ?>

<?php }else{ ?>

<h2><?= $lex[$lang]['blog']; ?></h2>

<ul class="blog">
<?php 	while ($article = mysql_fetch_assoc($blog)){ ?>
	<li>
		<a href="<?= BASE_URL; ?>blog/<?= $page; ?>/<?= $article['id']; ?>/<?= slugify($article['title']); ?>">
			<small><?= beautify_datetime($article['published']); ?></small>
			<h3><?= $article['title']; ?></h3>
			<p><?= shorten_string($article['content'], 250); ?></p>
		</a>
	</li>
<?php 	} ?>
</ul>

<?php 	if ($pages > 1){
			for ($i = 1; $i < $pages + 1; $i++){ ?>
<a class="button<?= $i == $page ? ' current' : ''; ?>" href="<?= BASE_URL; ?>blog/<?= $i; ?>"><?= $i; ?></a>
<?php 		}
		} ?>

<?php } ?>
