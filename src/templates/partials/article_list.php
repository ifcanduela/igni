<div class="article-list">
    <?php foreach ($articles as $article): ?>
        <?php if (!$article->draft): ?>
            <article>
                <a style="<?= $article->getBackgroundImage() ?>" href="read/<?= $article->slug ?>">
                    <h2 class="post-title"><?= $article->title ?></h2>
                    <div class="post-date"><?= $article->created ?></div>
                </a>
            </article>
        <?php endif; ?>
    <?php endforeach ?>
</div>
