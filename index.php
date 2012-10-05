<?php

# Igni
# Websites for you and your mom.

require_once 'igni/Igni.php';
require_once 'igni/MarkdownRenderer.php';

$page = isset($_GET['url']) ? $_GET['url'] : '';

$igni = new Igni(new MarkdownRenderer);
echo $igni->renderPage($page);
