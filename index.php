<?php

# Igni
# Websites for you and your mom.

require_once 'igni/Igni.php';
require_once 'igni/MarkdownRenderer.php';

if (isset($_GET['url'])) {
    $page = $_GET['url'];
} else {
    $page = false;
}

$igni = new Igni(new MarkdownRenderer);
$igni->renderPage($page);
