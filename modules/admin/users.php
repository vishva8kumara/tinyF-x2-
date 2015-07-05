<link rel="stylesheet" type="text/css" href="<?= BASE_URL_STATIC; ?>css/datatable.css" />
<h2>User Accounts</h2>

<a href="<?= BASE_URL; ?>admin/add-user" class="button">Add User</a>
<br/><br/>

<?php render_table($schema, $users, 'tbl-users'); ?>
