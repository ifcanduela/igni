# ==Frequently Asked== Questions

## What Is Igni?

Igni is a PHP script that takes your files and automatically builds a simple website around them.

## Who Is It For?

This is for those folks who want a simple website and have a web host with [PHP](http://php.net/) support, but don't know PHP and don't want to know PHP.

## How Do I Use It?

You write your content in [Markdown syntax](http://daringfireball.net/projects/markdown/syntax) and put it in text files in the **pages** and **posts** folders. That's it, mostly.

## Can I Change The Way It Works?

Only slightly. There's a file, `config.json`, that you can edit to change a few things. Don't get your hopes up, the configuration options are deliberately scarce. *Deliberately* here means I don't know what to do with it.

## Can I Change The Way It Looks?

You can change quite a bit of it, yes. The overall structure of the site is called a *theme*, and you can change it if you know some basic HTML (or PHP). This file also determines which stylesheet you use. 

The content of main *pieces* of the web page, called **blocks** (header, menu, sidebar and footer) can be changed in the same way you write your pages and posts.

## How Do I Write Links To Other Articles?

This is quite simple. If you want to link to the article `site/pages/projects/photos.md` from a source article, you write this in your source article:

    [This is another article](project/photos)

## How Do I Add Images To My Pages?

Another easy question. Place your image files in `site/files` and embed them like this:

    ![Title of the picture](site/files/image.jpg)

As you may have possibly guessed, you can place such files anywhere, and must provide the relative path to them.

## Can I Use HTML Instead Of Markdown?

Well, yes. But it's not as straightforward as it should be. You need to modify `index.php` to use `new HtmlRenderer` or `PhpRenderer` instead of `new MarkdownRenderer`. Just change that and you're set.

    $igni = new igni\Igni(new igni\HtmlRenderer);
    $igni = new igni\Igni(new igni\PhpRenderer);

Let me remind you that Igni is meant to make things easier. HTML is probably not what you were looking for.

## Can I Use Plain Text Instead Of Markdown or HTML?

Yes, you can, but you're on your own here. Once again, change the following line in `index.php` to this:

    $igni = new igni\Igni(new igni\IgniRenderer('.txt'));

The file extension can actually be anything. This will in fact still render the text as HTML.

## Can I Use PHP Instead Of Markdown?

You pose interesting questions, kid. This is currently not supported, but will probably get done sometime in the future. Again, it's not directly in line with the purpose of the project.
