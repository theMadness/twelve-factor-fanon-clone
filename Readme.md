# Twelve Factor MediaWiki Fanon Clone
This project aims to replicate a Fanon-equivalent level of service for a MediaWiki installation while heavily leveraging composer's capabilities, and reducing to a minimum the amount of code we directly handle.

Similarly, it tries to embrace as many of the other aspects of Twelve-Factor App methodology it can, without producing a ton of extra code and tools to be maintained.

## Requirements
* Caddy
* PHP-FPM
* Composer
* Git
* ghostscript
* imagemagick
* xpdf-utils
* ffmpeg
* Other possible dependencies from the extension to be documented

## Running the webserver for development

From inside the public directory, run `caddy run --watch`

## Composer notes
* After installation, git patches is applied to the vendor folder:
    1. `vendor-check.patch` shortcircuits `PHPVersionCheck::checkVendorExistence()`, that function doesn't know how to properly locate our vendor directory, and we should make sure that it exists, and it has good dependencies.
    2. `maintenance-autoload.patch` injects the composer autoloader inside of Maintenance.php
    3. `social-profile.patch` alters the sub-extension of SocialProfile inclusion to use an explicit relative path. 
* After installation, a git submodule for [Extension:VisualEditor](https://www.mediawiki.org/wiki/Extension:VisualEditor) is installed.

## Outstanding issues
* [Extension:WikiForum](https://www.mediawiki.org/wiki/Extension:WikiForum) is currently disabled due to a known vulnerability, can be reactivated once that vulnerability is addressed.
* [Extension:HTMLets](https://www.mediawiki.org/wiki/Extension:HTMLets) is still installed using the old inclusion strategy, as opposed as to use `wfLoadExtension`
* Some extensions may still need to have symlinks created for them in order to access frontend assets.
* A number of extensions are not tagged `REL1_40`
  * [Extension:Discord](https://www.mediawiki.org/wiki/Extension:Discord) (REL1_39, 1.0.13)
  * [Extension:DynamicPageList3](https://www.mediawiki.org/wiki/Extension:DynamicPageList3) (REL1_39, 3.5.2)
  * [Extension:EmbedSpotify](https://www.mediawiki.org/wiki/Extension:EmbedSpotify) (1.0.3)
  * [Extension:EmbedVideo](https://www.mediawiki.org/wiki/Extension:EmbedVideo_(fork)) (3.3.0)
  * [Extension:ExcludeRandom](https://www.mediawiki.org/wiki/Extension:ExcludeRandom) (2.0.0)
  * [Extension:MobileTabsPlugin](https://github.com/fuerthwiki/MobileTabsPlugin) (0.1.0)
  * [Extension:Moderation](https://www.mediawiki.org/wiki/Extension:Moderation) (1.7.4)
  * [Extension:PDFEmbed](https://www.mediawiki.org/wiki/Extension:PDFEmbed) (3.0.1)
  * [Extension:Sketchfab](https://github.com/follesoe/mediawiki-sketchfab-tag-extension) (1.0.0)
  * [Extension:WikiDexFileRepository](https://github.com/ciencia/mediawiki-extensions-WikiDexFileRepository) (1.2.0)

## Improvement ideas
* A composer plugin that catalogues all the extensions data and simplifies the loading of the extension
* A composer plugin that scans all the extensions for ResourceFileModulePaths configurations, and creates symlinks accordingly (might not be necessary, load.php seems to be able to handle most cases, keep an eye on the logfile)
