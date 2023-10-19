<?php

## [UPO] means: this is also a user preference option

// Set to true to enable basic email features. (https://www.mediawiki.org/wiki/Manual:$wgEnableEmail)
$wgEnableEmail = true;

// [UPO] Set to true to enable user-to-user email. (https://www.mediawiki.org/wiki/Manual:$wgEnableUserEmail)
$wgEnableUserEmail = false;

// [UPO] Enable email notifications for edits on users' talk pages. (https://www.mediawiki.org/wiki/Manual:$wgEnotifUserTalk)
$wgEnotifUserTalk = false;

// [UPO] Set to true to allow the email notification for watched pages. (https://www.mediawiki.org/wiki/Manual:$wgEnotifWatchlist)
$wgEnotifWatchlist = false;

// Set to true to enable email authentication (confirmation) for this wiki. (https://www.mediawiki.org/wiki/Manual:$wgEmailAuthentication)
$wgEmailAuthentication = true;

## Database settings
$wgDBtype = getenv('MW_DB_TYPE');
$wgDBserver = getenv('MW_DB_SERVER');
$wgDBname = getenv('MW_DB_NAME');
$wgDBuser = getenv('MW_DB_USER');
$wgDBpassword = getenv('MW_DB_PASSWORD');

# MySQL specific settings
$wgDBprefix = getenv('MW_DB_PREFIX');

# MySQL table options to use during installation or update
$wgDBTableOptions = "ENGINE=InnoDB, DEFAULT CHARSET=binary";

## Shared memory settings
// Also see https://www.mediawiki.org/wiki/Manual:Performance_tuning
$wgMainCacheType = CACHE_NONE;
$wgMemCachedServers = [];

## To enable image uploads, make sure the 'images' directory
## is writable, then set this to true:
$wgEnableUploads = true;
#$wgUseImageMagick = true;
#$wgImageMagickConvertCommand = "/usr/bin/convert";

# InstantCommons allows wiki to use images from https://commons.wikimedia.org
$wgUseInstantCommons = false;

# Periodically send a pingback to https://www.mediawiki.org/ with basic data
# about this MediaWiki instance. The Wikimedia Foundation shares this data
# with MediaWiki developers to help guide future development efforts.
$wgPingback = false;

## Set $wgCacheDirectory to a writable directory on the web server
## to make your wiki go slightly faster. The directory should not
## be publicly accessible from the web.
#$wgCacheDirectory = "$IP/cache";

// Customized secret. (https://www.mediawiki.org/wiki/Manual:$wgSecretKey)
$wgSecretKey = getenv('MW_SECRET_KEY');

# Changing this will log out all existing sessions.
$wgAuthenticationTokenVersion = "1";

# Site upgrade key. Must be set to a string (default provided) to turn on the
# web installer while LocalSettings.php is in place
//$wgUpgradeKey = "7a322b4ab6f67196";

# Path to the GNU diff3 utility. Used for conflict resolution.
$wgDiff3 = "/usr/bin/diff3";
