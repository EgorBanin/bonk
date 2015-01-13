<h2>Users</h2>

<div>
	<?php foreach ($users as $user): ?>
		<div>
			<?php echo htmlspecialchars($user->name) ?>
			<a href='<?php echo \router\url('actions/users/edit.php', ['id' => $user->id]) ?>'>edit</a>
			<a href='<?php echo \router\url('actions/users/delete.php', ['id' => $user->id]) ?>'>delete</a>
		</div>
	<?php endforeach ?>
</div>
<div>
	<a href='<?php echo \router\url('actions/users/edit.php') ?>'>add</a>
</div>