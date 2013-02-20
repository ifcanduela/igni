<ul>
    <?php foreach ($posts as $post): ?>
        <li><a href="<?= $post->slug ?>"><?= $post->title ?></a></li>
    <?php endforeach ?>
</ul>
