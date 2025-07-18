<?php

## Default skin: you can change the default skin. Use the internal symbolic
## names, e.g. 'vector' or 'monobook':
$wgDefaultSkin = "vector-2022";

# Enabled skins.
# The following skins were automatically enabled:
wfLoadSkin('MinervaNeue', __DIR__ . '/../vendor/mediawiki/minerva-neue-skin/skin.json');
wfLoadSkin('MonoBook', __DIR__ . '/../vendor/mediawiki/mono-book-skin/skin.json');
wfLoadSkin('Timeless', __DIR__ . '/../vendor/mediawiki/timeless-skin/skin.json');
wfLoadSkin('Vector', __DIR__ . '/../vendor/mediawiki/vector-skin/skin.json');
$wgDefaultUserOptions['vector-theme'] = 'os';
$wgVectorNightMode["logged_out"] = true;
$wgVectorNightMode["beta"] = false;

// Watch and unwatch as a star icon rather than a link (for Vector skin only).
$wgVectorUseIconWatch = true;
