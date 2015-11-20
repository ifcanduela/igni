<?php

# location of the composer.json file and the src folder
define('APP_PATH', dirname(__DIR__));
# location of the this file (index.php)
define('PUBLIC_PATH', __DIR__);

require APP_PATH . '/vendor/autoload.php';

use League\Plates\Engine;
use Gregwar\Cache\Cache;
use Symfony\Component\Yaml\Yaml;
use Slim\Slim;

use home\Article;

/**
 * Get a list of all articles from cache.
 *
 * @param Gregwar\Cache\Cache $cache
 * @return Article[]
 */
function getArticleList($cache)
{
    # prepare the cache options
    $hash = base64_encode('article_list') . '.cache';
    $cacheConfig = [
        'max-age' => CACHE_DURATION,
    ];

    # load or create the cache data file
    $articleData = $cache->getOrCreate($hash, $cacheConfig, function () {
        $articles = [];
        $articleFiles = glob(APP_PATH . '/articles/*.md');

        foreach ($articleFiles as $articleFileName) {
            $articles[] = new Article($articleFileName);
        }

        $articles = array_filter($articles, function ($article) {
            return !($article->draft || $article->isInTheFuture());
        });

        usort($articles, function ($a, $b) {
            $a_t = $a->date ? strtotime($a->date) : $a->timestamp;
            $b_t = $b->date ? strtotime($b->date) : $b->timestamp;

            if ($a_t === $b_t) {
                return 0;
            }

            return $a_t > $b_t ? -1 : 1;
        });

        return serialize($articles);
    });

    # unserialize the contents of data file
    $articleList = unserialize($articleData);

    return $articleList;
}

/**
 * Thumbnail generation helper.
 *
 * @param string $file File name of the image in the /img folder
 * @param int $width Width of the generated thumbnail
 * @param int $height Height of the generated thumbnail
 * @return string URL-friendly location of the generated thumbnail
 */
function thumb($file, $width, $height)
{
    $src = "img/{$file}";
    $dest = "img/thumb/$width-$height-$file";

    if (file_exists(PUBLIC_PATH  .'/' . $src) && !file_exists(PUBLIC_PATH . '/' . $dest)) {
        $imagine = new Imagine\Gd\Imagine();
        $size = new Imagine\Image\Box($width, $height);
        $mode = Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;

        $imagine->open(PUBLIC_PATH . '/' . $src)
            ->thumbnail($size, $mode)
            ->save(PUBLIC_PATH . '/' . $dest);
    }

    return $dest;
}

function site($property, $default = false)
{
    static $config;

    if (!$config) {
        $config = Yaml::parse(APP_PATH . '/config/site.yml');
    }

    if (array_key_exists($property, $config)) {
        return $config[$property];
    }

    return $default;
}

function author($name)
{
    static $authors;

    if (!$authors) {
        $authors = Yaml::parse(APP_PATH . '/config/author.yml');
    }

    if (array_key_exists($name, $authors)) {
        return $authors[$name];
    }

    return false;
}

# set cache duration to 1 hour
define('CACHE_DURATION', site('cache-duration', 1 * 60 * 60));
# set an amount of articles per page in the article list
define('ARTICLES_PER_PAGE', site('articles-per-page', 9));
# set an amount of articles in the home page
define('ARTICLES_IN_HOMEPAGE', site('articles-in-homepage', 3));

# instantiate the application
$app = new Slim();

# instantiate the templateing framework
$plates = new Engine(APP_PATH . '/src/templates');
# set a default page title
$plates->addData(['pageTitle' => 'ifcanduela']);
$plates->addData(['showFooter' => true]);

# initialize the caching library
$cache = new Cache;
$cache->setCacheDirectory(APP_PATH . '/cache');
$cache->setPrefixSize(0);

$plates->addData(['baseUrl' => $app->request->getScriptName()]);

#
# 404/Not Found setup
#
$app->notFound(function () use ($app, $plates) {
    $plates->addData(['showFooter' => false]);
    echo $plates->render('404');
});

#
# Utility URL to read the documentation
#
$app->get('/~help', function () use($plates) {
    if (false == site('debug', false)) {
        return $app->notFound();
    }
    
    $article = new Article;
    $article->markdown = file_get_contents(APP_PATH . '/README.md');
    $article->title = 'Igni Help';

    $plates->addData(['pageTitle' => 'Igni help | ifcanduela']);

    echo $plates->render('article/read', [
        'article' => $article
    ]);
});

#
# Utility URL to refresh the cache
#
$app->get('/~refresh', function () use($app, $cache) {
    if (false == site('debug', false)) {
        return $app->notFound();
    }

    $files = glob(APP_PATH . '/cache/*.cache');

    foreach ($files as $file) {
        unlink($file);
    }

    $app->redirect('/');
});

#
# Show a single article
#
$app->get('/read/:slug+', function ($slug) use ($app, $plates, $cache) {
    $slug = join('/', $slug);
    $hash = base64_encode($slug) . '.cache';
    $cacheConfig = [
        'max-age' => CACHE_DURATION,
    ];

    $html = $cache->getOrCreate($hash, $cacheConfig, function () use ($slug, $plates) {
        # try to find the file in the articles folder
        $markdown_filename = APP_PATH . '/articles/' . $slug . '.md';

        # if the file does not exist, return an empty result
        if (!file_exists($markdown_filename)) {
            return false;
        }

        # load the article data and update the page title
        $article = new home\Article($markdown_filename);
        $plates->addData(['pageTitle' => $article->title . ' | ifcanduela']);

        return $plates->render('article/read', [
                'article' => $article,
                'menu' => site('menu'),
            ]);
    });

    if (!$html) {
        return $app->notFound();
    }

    echo $html;
});

#
# Show the list of articles
#
$app->get('/articles(/:currentPage)', function ($currentPage = 1) use ($app, $cache, $plates) {
    $articles = getArticleList($cache);
    $articleCount = count($articles);
    $pageCount = ceil($articleCount / ARTICLES_PER_PAGE);
    $articles = array_slice($articles, ($currentPage - 1) * ARTICLES_PER_PAGE, ARTICLES_PER_PAGE);

    $plates->addData(['pageTitle' => 'Articles | ifcanduela']);

    echo $plates->render('article/list', [
            'articles' => $articles,
            'currentPage' => $currentPage,
            'pageCount' => $pageCount,
        ]);
});

#
# Show the front page
#
$app->get('/', function () use ($app, $cache, $plates) {
    $articles = getArticleList($cache);
    $articleCount = site('home-articles', 6);

    echo $plates->render('home', [
            'articles' => array_slice($articles, 0, $articleCount),
        ]);
});

$app->run();
