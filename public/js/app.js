$(function (){
    $pageSplash = $('.curtain');
    $pageBlog = $('.page-blog');
    $postBody = $('.post-body');

    if ($pageSplash.length) {
        var splashHeight = $pageSplash.height();

        $(window).on('resize', function () {
            splashHeight = $pageSplash.height();
            $pageBlog.css('padding-top', (250 + splashHeight) + 'px');
        });

        $pageSplash.css({
            'position': 'absolute',
            'z-index': 9
        });
        $pageSplash.parent().css('overflow', 'hidden');
        $pageBlog.css('padding-top', '100vh');

        $(window).on('load scroll', function (e) {
            var scrollTop = $(window).scrollTop();
            var opacity = 1 - scrollTop / splashHeight;

            $pageSplash.css('top', '-' + scrollTop + 'px');
            $pageSplash.css('opacity', opacity);
        });
    }

    if ($postBody.length) {
        var headingTags = ['h1, h2, h3, h4, h5, h6'];

        for (var headingTagIndex in headingTags) {
            var headingTag = headingTags[headingTagIndex];

            var $headings = $postBody.find(headingTag);

            $headings.each(function () {
                var txt = $(this).text();
                var slug = titleToSlug(txt);
                
                $(this).prepend('<a name="' + slug + '"></a>');
            });
        }
    }

    function titleToSlug(title) {
        return title
            .replace(new RegExp('[^A-Za-z0-9]+', 'g'), '-')
            .toLowerCase()
            .replace(new RegExp('^\-+|\-+$', 'g'), '');
    }
});
