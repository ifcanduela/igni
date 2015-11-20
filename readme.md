# Igni

> Automatic websites for you and your kids.

## What do I do?

Go ahead and clone this repo into some folder on your server. Run `composer install`, point your document root to the `public` directory and you're good to go.

Mostly this:

```bash
$ git clone https://github.com/ifcanduela/igni.git
$ cd igni
$ composer install
```

> You also need to set the `cache` and `public/img/thumb` directories to be writable by the web server process. How you do this depends on your setup and preference.

## So what is this?

Igni is a very simple tool to have an article-based website up and running in minutes. Just a front page with a list of recent articles, an article archive and the article pages.

## How do I...

### ...change the site title?

Edit `config/site.yml`. You can change things like the site name (`title`), the tagline (`subtitle`), the background image (`splash`) and the amount of articles (sorted by date) that are displayed underneath the splash.

### ...publish a new article?

Just add a new `.md` file to the `articles` folder. Make sure it has a *front matter* section at the top, with a `title` attribute, like this:

```yaml
---
title: My new article
---
```
Below that, just write your article. You can use [Markdown](http://daringfireball.net/projects/markdown/) (of the [Github flavor](https://help.github.com/articles/github-flavored-markdown/)) or good ol' HTML. Note that you don't have to do anything special to use Markdown, and you can mix and match HTML and Markdown, with the only limitation that you cannot put Markdown inside any HTML

### ...choose a splash image for an article?

First, put your image in the `public/img` folder. Then, in your article file, add a *front matter* attribute called `splash` or `banner` with the name of the image file:

```yaml
---
title: My new article
splash: rainbows.jpg
---
```

A splash image will cover the full screen, while a banner image will only cover the top half.

> A general bit of advice: Don't use images over 150 KB. The [kraken.io image compression service](https://kraken.io/web-interface) can be very helpful.

### ...link to one article from another?

If you want to use Markdown, use this code to insert a link:

```markdown
[My New Article](read/my-new-article)
```

If you prefer the classics, here's the same link using HTML:

```html
<a href="read/my-new-article">My New Article</a>
```

The links should not contain a leading `/`.

### ...add *previous* or *next* links at the bottom of an article?

You can add pagination to articles by using the `previous` and `next` *front matter* attributes. They support two formats, with or without titles:

```yaml
---
previous: the-previous-article
next:
    url: the-next-article
    title: The Next Article
---
```

The *previous* link will look like [« Previous](#) and the *next* link will look like [The Next Article »](#).

### ...set a specific date for an article?

Use the `date` *front matter* attribute and use a value in the year-month-day format (like `2009-02-31` but, you know, a day *that actually exists*). You can set dates in the future and the article will not appear listed until that day comes. Scheduled publishing!

```yaml
---
title: My new article
date: 2013-09-02
---
```

### ...unpublish an article?

Add a `draft` attribute with any value except `0` or `false` to the *front matter*:

```yaml
---
title: My new article
date: 2013-09-02
draft: yeah
---
```

It's time to finish that article, though.

### ...specify an author for an article?

First off, you must add an author to the `config/author.yml` file, following this format:

```yaml
my-nickname:
    name: My Full Name
    photo: authors/my-photo.png
```

Place a picture called `my-photo.png` (or whatever filename you use in `author.yml`) in the `img/authors` folder. Of course, you can just put the file in the `img` folder, but this will keep things tidy.

Then, use the `author` *front matter* attribute and set it to the nickname you chose in the `author.yml` file:

```yaml
---
author: my-nickname
---
```

You can add multiple authors to `author.yml`, and specify who wrote what in an article-by-article basis.

> If an article does not have an author set, no authorship information will be displayed.

### ...highlight code blocks?

If you have a code block for your awful PHP example, you can add some syntax highlighting to it, courtesy of [PrismJS](http://prismjs.com/). These two examples will look weird if you read the source code of this page. With Markdown:

<pre><code class="language-markdown">```php
your
    code
    goes
here
```</code></pre>

I recommend the Markdown approach, but here is the same with HTML:

```html
<pre><code class="language-php">your
    code
    goes
here
</code></pre>
```

So with Markdown you use three backticks (<code>&#96;&#96;&#96;</code>) and the name of the language (the *grammar*) you want to highlight, and with HTML you wrap you code in `<pre>` and `<code>` tags and add a class to the `<code>` tag to specify the grammar, prefixed with `language-`.

> Here's a nice list of supported grammars:
> `bash`, `batch`, `c`, `clike`, `coffeescript`, `cpp`, `csharp`, `css`, `go`, `handlebars`, `html`, `ini`, `java`, `javascript`, `js`, `less`, `markdown`, `markup`, `markup`, `mathml`, `objectivec`, `php`, `python`, `ruby`, `rust`, `scss`, `sql`, `svg`, `twig`, `xml`, `yaml`
>
> You can replace the `public/js/prism.js` with your own build from http://prismjs.com/download.html.

### ...refresh my articles?

Igni will cache articles and the article list, in an attempt to speed up the generation of the pages. If you want to re-create everything, there are two alternatives:

1. Set `debug: true` in  in `config/site.yml` and  browse to `/~refresh`. Remember to turn `debug` off afterwards.

2. Delete all `*.cache` files in the `cache` folder using the command line.

### ...read this document in my site?

You can only do this after setting `debug: true` in  in `config/site.yml`. Point your browser to `/~help` to read Igni's `README.md` rendered by Igni.

## Hey, your design sucks!

First off, thanks; that's really encouraging, I really appreciate it. But more importantly, it's the perfect lede to talk about customisation.

Igni uses a master stylesheet written for the [LESS preprocessor](http://lesscss.org/). The default styles are meant to make the articles readable and elegant, and include responsive breakpoints for tablets and phones. You can find it in `public/css/styles.less`. Change it to your heart's content and then re-compile it into `public/css/styles.css`. Or ignore the LESS file and go the traditional way of changing `styles.css`, you call. The default styles are very simple and include responsive breakpoints. Make sure to also check the 'public/js/app.js' file.

The PHP templates are located at `src/templates`. Igni employs [Plates](http://platesphp.com/) as a templating framework, which means the templates are written in plain PHP, but supercharged with some extra tricks.

## License and credits

Code is &copy;2015 Igor F. Canduela and published under the [MIT License](http://opensource.org/licenses/MIT). Please read the documents at the links if you want more information.

Additional libraries and components are subject to their own licenses:

- [avalanche123/Imagine](https://github.com/avalanche123/Imagine)
- [cebe/markdown](https://github.com/cebe/markdown)
- [gregwar/cache](https://github.com/gregwar/cache)
- [PrismJS/prism](https://github/com/PrismJS/prism)
- [slimphp/Slim](https://github.com/slimphp/Slim)
- [symfony/yaml](https://github.com/symfony/yaml)
- [thephpleague/plates](https://github.com/thephpleague/plates)
- [Montserrat](https://www.google.com/fonts/specimen/Montserrat) font
- [Merriweather](https://www.google.com/fonts/specimen/Merriweather) font
