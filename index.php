<?php

# Igni
# Websites for you and your mom.

# Get the requested page
$page = @$_GET['url'];

# Require the Igni bootstrap script
require 'igni/bootstrap.php';

# Instance Igni with the Markdown renderer
$igni = new igni\Igni(new igni\MarkdownRenderer());
echo $igni->renderPage($page);
