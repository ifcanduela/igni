# Blocks

Blocks are Markdown files, like the `sidebar.md` or the `nav.md`, that the theme can insert into the web page. Adding a new block is very easy:

1. Find the template you want to insert the block into, for example `page.php`.
2. Place the text `{{blockName}}` wherever you want.
3. Create a file called `blockName.md` in the `blocks` folder.

The contents of the block file will be inserted into the template at the position you wanted.

The IgniTheme theme has a few blocks defined by default: header, nav, sidebar and footer.

previous: [Navigation](manual/navigation) | next: [Themes](manual/themes)
