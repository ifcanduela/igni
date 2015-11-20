<?php if ($authorData = author($author)): ?>
    <div class="author">
        <img class="author-picture" src="img/authors/<?= $authorData['photo'] ?>">
        <div class="author-name"><?= $authorData['name'] ?></div>
    </div>
<?php endif ?>
