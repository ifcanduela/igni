<?php $this->layout('layout') ?>

<div id="home">
    <?php $siteSplash = site('splash') ?>
    <header class="page-splash" style="<?= $siteSplash ? "background-image: url(img/{$siteSplash})" : ''?>">
        <div class="splash-title">
            <h1><?= site('title') ?></h1>
        </div>

        <?php if ($siteSubtitle = site('subtitle')): ?>
            <div class="splash-subtitle">
                <p><?= $siteSubtitle ?></p>
            </div>
        <?php endif; ?>
    </header>

    <section class="page-blog">
        <?= $this->insert('partials/article_list', ['articles' => $articles]) ?>

        <nav class="all-articles"><a href="articles">All articles</a></nav>
    </section>
</div>
