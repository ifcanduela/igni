<?php $this->layout('layout') ?>

<?php $this->insert('partials/nav') ?>

<div id="articles-page">
    <section class="page-blog">
        <div class="post-title">
            <h1>Articles</h1>
        </div>

        <?php echo $this->insert('partials/article_list', ['articles' => $articles]) ?>

        <?php if ($pageCount > 1): ?>
            <nav class="pagination">
                <?php if ($currentPage > 1): ?>
                    <a href="articles/<?= $currentPage - 1 ?>">« Newer</a>
                <?php endif ?>
                <?php if ($currentPage < $pageCount): ?>
                    <a href="articles/<?= $currentPage + 1 ?>">Older »</a>
                <?php endif ?>
            </nav>
        <?php endif ?>
    </section>
</div>
