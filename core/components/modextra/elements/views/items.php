<h2>Items List</h2>

<?php if ($items): ?>

<ul>
<?php foreach ($items as $item): ?>
    <li><?= $item->name ?></li>
<?php endforeach ?>
</ul>

<?php else: ?>
<div>No items</div>
<?php endif ?>
