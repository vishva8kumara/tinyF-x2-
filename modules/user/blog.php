<link rel="stylesheet" type="text/css" href="<?= BASE_URL_STATIC; ?>css/datatable.css" />
<h2>Blog Articles</h2>

<?php if (!isset($article)){ ?>

<a href="<?= BASE_URL; ?>user/blog/add/new" class="button">Add a Blog Article</a>
<br/><br/>
<?php render_table($schema, $blog, 'tbl-blog'); ?>

<?php 	if ($pages > 1){
			for ($i = 1; $i < $pages + 1; $i++){ ?>
<a class="button<?= $i == $page ? ' current' : ''; ?>" href="<?= BASE_URL; ?>admin/blog/<?= $i; ?>"><?= $i; ?></a>
<?php 		}
		} ?>

<?php }
	else{
		render_form($schema, $article, 'user/blog/edit/'.$article['id']);
	} ?>
