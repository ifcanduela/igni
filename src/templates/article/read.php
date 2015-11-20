<?php $this->layout('layout') ?>

<?php echo $this->insert('partials/nav') ?>

<article class="article-container" id="<?= $article->slug ?>">
    <?php if ($article->splash): ?>
        <header class="page-splash curtain" style="background-image: url(img/<?= $article->splash ?>)">
            <div class="splash-title">
                <h1><?= $article->title ?></h1>
            </div>

            <?php if ($article->subtitle): ?>
                <div class="splash-subtitle">
                    <?= $article->subtitle ?>
                </div>
            <?php endif; ?>

            <?php echo $this->insert('partials/meta', ['section' => 'splash', 'article' => $article]) ?>
        </header>
    <?php elseif ($article->banner): ?>
        <header class="page-banner" style="background-image: url(img/<?= $article->banner ?>)">
            <div class="splash-title">
                <h1><?= $article->title ?></h1>
            </div>

            <?php if ($article->subtitle): ?>
                <div class="splash-subtitle">
                    <?= $article->subtitle ?>
                </div>
            <?php endif; ?>
        </header>
    <?php endif ?>

    <section class="page-blog">
        <?php if (!$article->splash): ?>
            <?php if (!$article->banner): ?>
                <div class="post-title">
                    <h1><?=$article->title ?></h1>

                    <?php if ($article->subtitle): ?>
                        <p class="post-subtitle">
                            <?= $article->subtitle ?>
                        </p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php echo $this->insert('partials/meta',['section' => 'post', 'article' => $article]) ?>
        <?php endif; ?>

        <div class="post-body">
            <?= $article->html ?>

            <div class="post-footer">
                §
            </div>

            <?= $this->insert('partials/article_pagination', ['article' => $article]) ?>
        </div>
    </section>
</article>
