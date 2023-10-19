<?php

// Special pages
wfLoadExtension('AdminLinks', __DIR__ . '/../vendor/mediawiki/admin-links/extension.json');
wfLoadExtension('CheckUser', __DIR__ . '/../vendor/mediawiki/extension-check-user/extension.json');
wfLoadExtension('CiteThisPage', __DIR__ . '/../vendor/mediawiki/extension-cite-this-page/extension.json');
wfLoadExtension('CreatePageUw', __DIR__ . '/../vendor/mediawiki/extension-create-page-uw/extension.json');
$wgCreatePageUwUseVE = true;
wfLoadExtension('DataTransfer', __DIR__ . '/../vendor/mediawiki/data-transfer/extension.json');
wfLoadExtension('DeleteBatch', __DIR__ . '/../vendor/mediawiki/extension-delete-batch/extension.json');
wfLoadExtension('Echo', __DIR__ . '/../vendor/mediawiki/extension-echo/extension.json');
wfLoadExtension('Editcount', __DIR__ . '/../vendor/mediawiki/extension-editcount/extension.json');
wfLoadExtension('Interwiki', __DIR__ . '/../vendor/mediawiki/extension-interwiki/extension.json');
$wgGroupPermissions['sysop']['interwiki'] = true;
wfLoadExtension('LookupUser', __DIR__ . '/../vendor/mediawiki/extension-lookup-user/extension.json');
wfLoadExtension('Nuke', __DIR__ . '/../vendor/mediawiki/extension-nuke/extension.json');
wfLoadExtension('ReplaceText', __DIR__ . '/../vendor/mediawiki/replace-text/extension.json');
wfLoadExtension('SimpleChanges', __DIR__ . '/../vendor/mediawiki/extension-simple-changes/extension.json');
wfLoadExtension('UserMerge', __DIR__ . '/../vendor/mediawiki/extension-user-merge/extension.json');
$wgGroupPermissions['sysop']['usermerge'] = true;

// Editors
wfLoadExtension('CodeEditor', __DIR__ . '/../vendor/mediawiki/extension-code-editor/extension.json');
$wgDefaultUserOptions['usebetatoolbar'] = 1;
wfLoadExtension('CodeMirror', __DIR__ . '/../vendor/mediawiki/extension-code-mirror/extension.json');
$wgDefaultUserOptions['usecodemirror'] = true;
wfLoadExtension('VisualEditor', __DIR__ . '/../vendor/mediawiki/extension-visual-editor/extension.json');
$wgGroupPermissions['user']['writeapi'] = true;
wfLoadExtension('WikiEditor', __DIR__ . '/../vendor/mediawiki/extension-wiki-editor/extension.json');
$wgWikiEditorRealtimePreview = true;

// Parser hooks
wfLoadExtension('AJAXPoll', __DIR__ . '/../vendor/mediawiki/extension-ajax-poll/extension.json');
wfLoadExtension('Babel', __DIR__ . '/../vendor/mediawiki/babel/extension.json');
wfLoadExtension('CategoryTree', __DIR__ . '/../vendor/mediawiki/extension-category-tree/extension.json');
wfLoadExtension('CharInsert', __DIR__ . '/../vendor/mediawiki/extension-char-insert/extension.json');
wfLoadExtension('Cite', __DIR__ . '/../vendor/mediawiki/extension-cite/extension.json');
$wgCiteBookReferencing = true;
wfLoadExtension('Comments', __DIR__ . '/../vendor/mediawiki/extension-comments/extension.json');
wfLoadExtension('DynamicPageList3', __DIR__ . '/../vendor/universal-omega/dynamic-page-list3/extension.json');
wfLoadExtension('EmbedSpotify', __DIR__ . '/../vendor/nessunkim/mediawiki-embed-spotify/extension.json');
wfLoadExtension('EmbedVideo', __DIR__ . '/../vendor/starcitizenwiki/embedvideo/extension.json');
wfLoadExtension('HeaderTabs', __DIR__ . '/../vendor/mediawiki/extension-header-tabs/extension.json');
require_once __DIR__ . "/../vendor/mediawiki/extension-htm-lets/HTMLets.php";
$wgHTMLetsDirectory = __DIR__ . "/../vendor/mediawiki/extension-htm-lets";
wfLoadExtension('ImageMap', __DIR__ . '/../vendor/mediawiki/image-map/extension.json');
wfLoadExtension('InputBox', __DIR__ . '/../vendor/mediawiki/extension-input-box/extension.json');
wfLoadExtension('LabeledSectionTransclusion', __DIR__ . '/../vendor/mediawiki/extension-labeled-section-transclusion/extension.json');
wfLoadExtension('LoopFunctions', __DIR__ . '/../vendor/mediawiki/extension-loop-functions/extension.json');
wfLoadExtension('Loops', __DIR__ . '/../vendor/mediawiki/extension-loops/extension.json');
wfLoadExtension('MagicNoCache', __DIR__ . '/../vendor/mediawiki/extension-magic-no-cache/extension.json');
wfLoadExtension('Math', __DIR__ . '/../vendor/mediawiki/extension-math/extension.json');
wfLoadExtension('MsUpload', __DIR__ . '/../vendor/mediawiki/extension-ms-upload/extension.json');
wfLoadExtension('OpenGraphMeta', __DIR__ . '/../vendor/mediawiki/extension-open-graph-meta/extension.json');
wfLoadExtension('PageInCat', __DIR__ . '/../vendor/mediawiki/extension-page-in-cat/extension.json');
wfLoadExtension('ParserFunctions', __DIR__ . '/../vendor/mediawiki/extension-parser-functions/extension.json');
$wgPFEnableStringFunctions = true;
wfLoadExtension('PDFEmbed', __DIR__ . '/../vendor/WolfgangFahl/PDFembed/extension.json');
wfLoadExtension('Poem', __DIR__ . '/../vendor/mediawiki/extension-poem/extension.json');
wfLoadExtension('PortableInfobox', __DIR__ . '/../vendor/mediawiki/portableinfobox/extension.json');
wfLoadExtension('RandomSelection', __DIR__ . '/../vendor/mediawiki/extension-random-selection/extension.json');
wfLoadExtension('RSS', __DIR__ . '/../vendor/mediawiki/extension-rss-feed/extension.json');
wfLoadExtension('Scribunto', __DIR__ . '/../vendor/mediawiki/extension-scribunto/extension.json');
$wgScribuntoDefaultEngine = 'luastandalone';
wfLoadExtension('Sketchfab', __DIR__ . '/../vendor/follesoe/mediawiki-sketchfab-tag-extension/Sketchfab/extension.json');
wfLoadExtension('SyntaxHighlight', __DIR__ . '/../vendor/mediawiki/syntax-highlight/extension.json');
wfLoadExtension('TabberNeue', __DIR__ . '/../vendor/mediawiki/tabber-neue/extension.json');
wfLoadExtension('Tabs', __DIR__ . '/../vendor/mediawiki/extension-tabs/extension.json');
wfLoadExtension('TemplateData', __DIR__ . '/../vendor/mediawiki/extension-template-data/extension.json');
wfLoadExtension('TemplateStyles', __DIR__ . '/../vendor/mediawiki/extension-template-styles/extension.json');
wfLoadExtension('Variables', __DIR__ . '/../vendor/mediawiki/variables/extension.json');
wfLoadExtension('VoteNY', __DIR__ . '/../vendor/mediawiki/extension-vote-ny/extension.json');
wfLoadExtension('Widgets', __DIR__ . '/../vendor/mediawiki/extension-widgets/extension.json');
$wgWidgetsCompileDir = __DIR__ . '/../generated/Widgets/compiled_templates';

// Media handlers
wfLoadExtension('PdfHandler', __DIR__ . '/../vendor/mediawiki/extension-pdf-handler/extension.json');
wfLoadExtension('TimedMediaHandler', __DIR__ . '/../vendor/mediawiki/extension-timed-media-handler/extension.json');
$wgFFmpegLocation = '/usr/bin/ffmpeg';
wfLoadExtension('WikiDexFileRepository', __DIR__ . '/../vendor/ciencia/mediawiki-extensions-wikidexfilerepository/extension.json');

// Spam prevention
wfLoadExtension('AbuseFilter', __DIR__ . '/../vendor/mediawiki/abuse-filter/extension.json');
wfLoadExtension('ConfirmEdit', __DIR__ . '/../vendor/mediawiki/extension-confirm-edit/extension.json');
wfLoadExtension('ConfirmEdit/QuestyCaptcha', __DIR__ . '/../vendor/mediawiki/extension-confirm-edit/QuestyCaptcha/extension.json');
$wgCaptchaQuestions = [
    'What is the capital of France?' => 'Paris',
    'What is the capital of Spain' => 'MADRID', // Answers are case-insensitive
//    'What is the name of this wiki?' => $wgSitename, // You can use variables
    'How many fingers does a hand have?' => [ 5, 'five' ], // A question may have many answers
];
wfLoadExtension('Moderation', __DIR__ . '/../vendor/edwardspec/mediawiki-moderation/extension.json');
wfLoadExtension('SpamBlacklist', __DIR__ . '/../vendor/mediawiki/extension-spam-blacklist/extension.json');
wfLoadExtension('TitleBlacklist', __DIR__ . '/../vendor/mediawiki/extension-title-blacklist/extension.json');

// API
wfLoadExtension('PageImages', __DIR__ . '/../vendor/mediawiki/extension-page-images/extension.json');

// Other
wfLoadExtension('AutoCreateCategoryPages', __DIR__ . '/../vendor/mediawiki/extension-auto-create-category-pages/extension.json');
wfLoadExtension('BetaFeatures', __DIR__ . '/../vendor/mediawiki/extension-beta-features/extension.json');
wfLoadExtension('BlogPage', __DIR__ . '/../vendor/mediawiki/extension-blog-page/extension.json');
wfLoadExtension('CirrusSearch', __DIR__ . '/../vendor/mediawiki/cirrussearch/extension.json');
wfLoadExtension('CLDR', __DIR__ . '/../vendor/mediawiki/cldr/extension.json');
wfLoadExtension('CleanChanges', __DIR__ . '/../vendor/mediawiki/clean-changes/extension.json');
$wgDefaultUserOptions['usenewrc'] = 1;
wfLoadExtension('Description2', __DIR__ . '/../vendor/mediawiki/extension-description-2/extension.json');
$wgEnableMetaDescriptionFunctions = true;
wfLoadExtension('Discord', __DIR__ . '/../vendor/jayktaylor/mw-discord/extension.json');
wfLoadExtension('Elastica', __DIR__ . '/../vendor/mediawiki/elastica/extension.json');
wfLoadExtension('ExcludeRandom', __DIR__ . '/../vendor/majr25/exclude-random/extension.json');
wfLoadExtension('Flow', __DIR__ . '/../vendor/mediawiki/flow/extension.json');
$wgDefaultUserOptions['flow-editor'] = 'visualeditor';
$wgNamespaceContentModels[NS_TALK] = 'flow-board';
$wgNamespacesWithSubpages[NS_TALK] = true;
$wgNamespaceContentModels[NS_USER_TALK] = 'flow-board';
$wgNamespacesWithSubpages[NS_USER_TALK] = true;
wfLoadExtension('Gadgets', __DIR__ . '/../vendor/mediawiki/extension-gadgets/extension.json');
wfLoadExtension('JsonConfig', __DIR__ . '/../vendor/mediawiki/extension-json-config/extension.json');
wfLoadExtension('LinkSuggest', __DIR__ . '/../vendor/mediawiki/extension-link-suggest/extension.json');
wfLoadExtension('Lockdown', __DIR__ . '/../vendor/mediawiki/extension-lockdown/extension.json');
wfLoadExtension('MobileFrontend', __DIR__ . '/../vendor/mediawiki/extension-mobile-frontend/extension.json');
wfLoadExtension('MobileTabsPlugin', __DIR__ . '/../vendor/fuerthwiki/mobile-tabs-plugin/extension.json');
wfLoadExtension('OATHAuth', __DIR__ . '/../vendor/mediawiki/extension-oath-auth/extension.json');
wfLoadExtension('PageLanguage', __DIR__ . '/../vendor/mediawiki/extension-page-language/extension.json');
wfLoadExtension('PageNotice', __DIR__ . '/../vendor/mediawiki/extension-page-notice/extension.json');
wfLoadExtension('PollNY', __DIR__ . '/../vendor/mediawiki/extension-poll-ny/extension.json');
wfLoadExtension('Popups', __DIR__ . '/../vendor/mediawiki/extension-popups/extension.json');
wfLoadExtension('SecureLinkFixer', __DIR__ . '/../vendor/mediawiki/extension-secure-link-fixer/extension.json');
require_once __DIR__ . "/../vendor/mediawiki/extension-social-profile/SocialProfile.php";
wfLoadExtension('TextExtracts', __DIR__ . '/../vendor/mediawiki/extension-text-extracts/extension.json');
wfLoadExtension('Thanks', __DIR__ . '/../vendor/mediawiki/extension-thanks/extension.json');
wfLoadExtension('TwoColConflict', __DIR__ . '/../vendor/mediawiki/extension-two-col-conflict/extension.json');
wfLoadExtension('UniversalLanguageSelector', __DIR__ . '/../vendor/mediawiki/universal-language-selector/extension.json');
// Security warning
//wfLoadExtension('WikiForum', __DIR__ . '/../vendor/mediawiki/extension-wiki-forum/extension.json');


