<?php if ($article->author || $article->showDate): ?>
    <div class="<?= $section ?>-meta">
        <?php if ($article->author): ?>
            <?php $this->insert('partials/author', ['author' => $article->author]) ?>
        <?php endif; ?>

        <?php if ($article->showDate): ?>
            <div class="post-date">
                <?= $article->date ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
