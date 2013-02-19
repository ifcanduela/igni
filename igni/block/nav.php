<div class="posts-nav clearfix">
    <?php if ($previous): ?>
        <a class="previous-post" href="<?= $previous['slug'] ?>">&laquo; <?= $previous['title'] ?></a>
    <?php endif; ?>

    <?php if ($next): ?>
        <a class="next-post" href="<?= $next['slug'] ?>"><?= $next['title'] ?> &raquo;</a>
    <?php endif; ?>
</div>
