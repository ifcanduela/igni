<ul>
    <?php foreach ($pages as $page): ?>
        <li><a href="<?= $page->slug ?>"><?= $page->title ?></a></li>
    <?php endforeach ?>
</ul>
