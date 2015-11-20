<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <base href="<?= $baseUrl ?>/">
    <title><?= $pageTitle ?></title>
    <link rel="stylesheet" href="css/styles.css" media="screen" rel="stylesheet" type="text/css">
    <script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
</head>

<body>
    <?= $this->section('content') ?>

    <?php if ($showFooter): ?>
        <div class="page-footer">
            <p>
                &copy; 1942-<?= date('Y') ?> ifcanduela
                |
                Built with <a href="https://github.com/ifcanduela/igni">Igni</a></p>
        </div>
    <?php endif ?>

    <script src="js/app.js"></script>
    <script src="js/prism.js"></script>
</body>
</html>
