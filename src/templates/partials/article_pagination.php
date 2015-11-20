<?php if ($article->previous || $article->next): ?>
    <nav class="pagination">
        <?php if ($article->previous): ?>
            <?php if (is_array($article->previous)): ?>
                <a href="read/<?= $article->previous['url'] ?>">« <?= $article->previous['title'] ?></a>
            <?php else: ?>
                <a href="read/<?= $article->previous ?>">« Previous</a>
            <?php endif ?>
        <?php endif ?>

        <?php if ($article->next): ?>
            <?php if (is_array($article->next)): ?>
                <a href="read/<?= $article->next['url'] ?>"><?= $article->next['title'] ?> »</a>
            <?php else: ?>
                <a href="read/<?= $article->next ?>">Next »</a>
            <?php endif ?>
        <?php endif ?>
    </nav>
<?php endif ?>
