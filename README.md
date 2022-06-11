# TODO: write title here

## Steps

- [TODO: write title here](#todo-write-title-here)
  - [Steps](#steps)
    - [Remove unnecessary files](#remove-unnecessary-files)
    - [Format HTML files](#format-html-files)
    - [Remove JavaScript](#remove-javascript)
  - [TODO checklist](#todo-checklist)

### Remove unnecessary files

#### Remove XML (RSS & Atom feed) files

Remove `feed` directories containing `index.html` RSS feeds:

```bash
find ./mirror -type d -iname feed | xargs -r rm -rf
```

Remove RSS feed files from archived subdomains:

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

```bash
find ./mirror -type f -iname *htm* -exec php ./tools/remove-unwanted-tags/remove-unwanted-tags.php {} \;
```

### Remove JavaScript

### Disable search form

```bash
find ./mirror -type f -iname *htm* | xargs -r sed -i 's/<form method="get" id="searchform" action="[^"]\+" name="searchform">/<form id="searchform" name="searchform">/g'
```

```bash
find ./mirror -type f -iname *htm* | xargs -r sed -i 's/<fieldset>/<fieldset disabled>/g'
```

```bash
find ./mirror -type f -iname *htm* | xargs -r sed -i "s/<input name=\"s\" type=\"text\" onfocus=\"if(this.value=='Keresés') this.value='';\" onblur=\"if(this.value=='') this.value='Keresés';\" value=\"Keresés\">/<input type=\"text\" value=\"Keresés\" disabled>/g"
```

### Remove attributes containing JavaScript

### Remove scriptable attributes

See: https://www.w3schools.com/tags/ref_eventattributes.asp

```bash
find ./mirror -type f -iname *htm* -exec php ./tools/remove-scriptable-attributes/remove-scriptable-attributes.php {} \;
```

## TODO checklist
* [x] Format HTML files
* [ ] Remove unnecessary files
  * [ ] Remove XML files
