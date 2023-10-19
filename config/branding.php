<?php

//The name of the site. (https://www.mediawiki.org/wiki/Manual:$wgSitename)
$wgSitename = getenv('MW_SITE_NAME');

// Name used for the meta namespace. (https://www.mediawiki.org/wiki/Manual:$wgMetaNamespace)
$wgMetaNamespace = getenv('MW_META_NAMESPACE');

## The protocol and server name to use in fully-qualified URLs
$wgServer = getenv('MW_SERVER');

## The URL base path to the directory containing the wiki;
## defaults for all runtime URL paths are based off of this.
## For more information on customizing the URLs
## (like /w/index.php/Page_title to /wiki/Page_title) please see:
## https://www.mediawiki.org/wiki/Manual:Short_URL
## (https://www.mediawiki.org/wiki/Manual:$wgScriptPath)
$wgScriptPath = getenv('MW_SCRIPT_PATH');

// The base URL used to create article links. (https://www.mediawiki.org/wiki/Manual:$wgArticlePath)
$wgArticlePath = getenv('MW_ARTICLE_PATH');

// Whether to use 'pretty' URLs. (https://www.mediawiki.org/wiki/Manual:$wgUsePathInfo)
$wgUsePathInfo = filter_var(getenv('MW_USE_PATH_INFO'), FILTER_VALIDATE_BOOLEAN);

## The URL path to static resources (images, scripts, etc.)
$wgResourceBasePath = $wgScriptPath;

## The URL paths to the logo.  Make sure you change this from the default,
## or else you'll overwrite your logo when you upgrade!
$wgLogos = [
    '1x' => "$wgResourceBasePath/resources/assets/change-your-logo.svg",
    'icon' => "$wgResourceBasePath/resources/assets/change-your-logo-icon.svg",
];

// Site admin email address (https://www.mediawiki.org/wiki/Manual:$wgEmergencyContact)
$wgEmergencyContact = getenv('MW_EMERGENCY_CONTACT');

// Password reminder email address. (https://www.mediawiki.org/wiki/Manual:$wgPasswordSender)
$wgPasswordSender = getenv('MW_PASSWORD_SENDER');

# Site language code, should be one of the list in ./includes/languages/data/Names.php
$wgLanguageCode = "en";

# Time zone
$wgLocaltimezone = "UTC";

## For attaching licensing metadata to pages, and displaying an
## appropriate copyright notice / icon. GNU Free Documentation
## License and Creative Commons licenses are supported so far.
$wgRightsPage = ""; # Set to the title of a wiki page that describes your license/copyright
$wgRightsUrl = "";
$wgRightsText = "";
$wgRightsIcon = "";

// For using a direct (authenticated) SMTP server connection. (https://www.mediawiki.org/wiki/Manual:$wgSMTP)
$wgSMTP = [
    // could also be an IP address. Where the SMTP server is located. If using SSL or TLS, add the prefix "ssl://" or
    // "tls://".
    'host' => getenv('MW_SMTP_HOST'),
    // Generally this will be the domain name of your website (aka mywiki.org)
    'IDHost' => getenv('MW_SMTP_ID_HOST'),
    // Same as IDHost above; required by some mail servers
    'localhost' => getenv('MW_SMTP_LOCALHOST'),
    // Port to use when connecting to the SMTP server
    'port' => getenv('MW_SMTP_PORT'),
    // Should we use SMTP authentication (true or false)
    'auth' => filter_var(getenv('MW_SMTP_AUTH'), FILTER_VALIDATE_BOOLEAN),
    // Username to use for SMTP authentication (if being used)
    'username' => getenv('MW_SMTP_USERNAME'),
    // Password to use for SMTP authentication (if being used)
    'password' => getenv('MW_SMTP_PASSWORD')
];
