# How To Install Igni

If you have an Apache 2 web server and PHP 5.4, you're covered with the software. The configuration can be a little bit tricky, but there's a good chance that everything works out of the box.

The main hurdle is `mod-rewrite` support. It's not required, but highly recommended. With `mod-rewrite`, URLs will look like this:

    http://mydomain.com/my-article

Without `mod-rewrite`, URLs wil have to be written like this:

    http://mydomain.com/?url=my-article

A little bit uglier. For installation of `mod-rewrite`, refer to [this article](http://dummy.com "mod-rewrite Setup").

Afterwards, drop the Igni files in your document root. In Linux systems, this is usually `/var/www`, but it's different from system to system, and can also be changed by the user.

The installation is complete. Igni does not use a database server, nor requires write permissions to any file or folder.
