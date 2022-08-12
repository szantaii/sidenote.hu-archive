# sidenote-archive

## Contents

* [About](#about)
* [Cleanup checklist](#cleanup-checklist)
* [Some commands used for cleanup](#some-commands-used-for-cleanup)
  * [Remove XML (RSS & Atom feed) files](#remove-xml-rss--atom-feed-files)
  * [Format HTML files](#format-html-files)
  * [Remove unwanted tags and attributes](#remove-unwanted-tags-and-attributes)
  * [Disable search bar on main site](#disable-search-bar-on-main-site)


## About

This is a static and self-contained archive of my personal website or blog, `sidenote.hu` and its subdomains (`bash.sidenote.hu`, `sh.sidenote.hu`, `cellwars.sidenote.hu`, `cw.sidenote.hu`). The mirrored sites can be found in the `mirror` directory. The main page is the `mirror/sidenote.hu/index.html` file where (hopefully) all other pages can be reached from using hyperlinks.

The mentioned domains were mirrored using `wget`. Afterwards, the saved web content was thoroughly processed to remove any external resources (e.g. fonts, embedded content, etc.), tracking (apparently all JavaScript) and XML content to preserve the site in a clean, self-contained way.

Even though, this project is not a very well documented one, below you can see a checklist what I changes I made, and I have noted a couple of commands I have used during the cleanup process.

Copyright © 2022 István Szántai <[szantaii@gmail.com](mailto:szantaii@gmail.com)>

## Cleanup checklist

* [x] Add tools to process the mirrored pages
    * [x] Add HTML Tidy configuration for reformatting HTML files
    * [x] Add tool to remove all unwanted HTML tags (e.g. `script`, some `rel` and some unnecessary inline CSS formatting)
    * [x] Add tool to remove all scriptable attributes from HTML tags
* [x] Remove RSS and/or Atom feed files
* [x] Remove unnecessary WordPress generated stuff
* [x] Reformat HTML files
* [x] Remove JavaScript
    * [x] Remove JavaScript files
    * [x] Remove `script` tags from HTML files
    * [x] Remove scriptable attributes from HTML files
* [x] Remove externally linked font resources
* [x] Remove unwanted HTML tags
* [x] Disable search bar on static mirror
* [x] Remove dead links
* [x] Update non-dead links if necessary
* [x] Change embedded contents to links (e.g. embedded videos, PDF files, etc.)
* [x] Eliminate leftover HTML Tidy warnings


## Some commands used for cleanup

### Remove XML (RSS & Atom feed) files

Remove `feed` directories containing `index.html` RSS feeds:

```bash
find ./mirror -type d -iname feed | xargs -r rm -rf
```

Remove RSS `*feed*` files from archived subdomains:

```bash
find ./mirror -type f -iname *feed* | xargs -r rm -f
```

### Format HTML files

Remove Google +1 tags as HTML Tidy does not recognize them, e.g. `<g:plusone size="medium" href="http://sidenote.hu/2015/03/26/bash-weather-update/"></g:plusone>`:

```bash
find ./mirror -type f -iname *htm* | xargs -r sed -i 's/<g:plusone.*<\/g:plusone>//g'
```

```bash
find ./mirror -type f -iname *htm* | xargs -r tidy -config ./tools/tidy/tidy.config
```

### Remove unwanted tags and attributes

```bash
find ./mirror -type f -iname *htm* -exec php ./tools/remove-unwanted-tags/remove-unwanted-tags.php {} \;
```

```bash
find ./mirror -type f -iname *htm* -exec php ./tools/remove-scriptable-attributes/remove-scriptable-attributes.php {} \;
```

It is necessary to reformat the HTML files once more to eliminate unnecessary HTML entities (use their proper utf-8 forms instead):

```bash
find ./mirror -type f -iname *htm* | xargs -r tidy -config ./tools/tidy/tidy.config
```

### Disable search bar on main site

```bash
find ./mirror -type f -iname *htm* | xargs -r sed -i 's/<form method="get" id="searchform" action="[^"]\+" name="searchform">/<form id="searchform" name="searchform">/g'
```

```bash
find ./mirror -type f -iname *htm* | xargs -r sed -i 's/<fieldset>/<fieldset disabled>/g'
```

```bash
find ./mirror -type f -iname *htm* | xargs -r sed -i "s/<input name=\"s\" type=\"text\" onfocus=\"if(this.value=='Keresés') this.value='';\" onblur=\"if(this.value=='') this.value='Keresés';\" value=\"Keresés\">/<input type=\"text\" value=\"Keresés\" disabled>/g"
```
