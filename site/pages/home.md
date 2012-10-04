# Home

So, you don't know how to make websites, but you want one. An affordable, pretty one. But... oh, no &mdash; you can't find anything you can call your own out there. I'd like to tell you Igni is the solution, but it's far from being a solution to anything.

What I can tell you is that you should try it.

## What Is Igni?

Igni is a PHP script that takes your files and automatically builds a simple website around them.

## How Do I Use It?

You write your content in [Markdown syntax](http://daringfireball.net/projects/markdown/syntax) and put it in text files in the **pages** and **posts** folders. Taht's it, mostly.

## Can I Change The Way It Works?

Only slightly. There's a file, `config.json`, that you can edit to change a few things. Don't get your hopes up, the configuration options are deliberately scarce.

## Can I Change The Way It Looks?

You can change quite a bit of it, yes. The overall structure of the site is called a *theme*, and you can change it if you know some basic HTML. This file also determines which stylesheet you use. The content of main *pieces* of the web page (the header and the footer) can be changed in the same way you write your pages and posts.

## Can I Use Plain HTML Instead Of Markdown?

Well, yes. But it's not as straightforward as it should be. You need to modify `index.php` to use `new NullRenderer` instead of `new MarkdownRenderer`. Just change that and you're set.
