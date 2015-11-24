<?php if ($authorData = author($author)): ?>
    <img class="author-picture" src="img/authors/<?= $authorData['photo'] ?>">
    <div class="author-name"><?= $authorData['name'] ?></div>
<?php endif ?>
