<?php

use ApacheSolrForTypo3\Solrfal\Indexing\DocumentFactory;
use BeechIt\FalSecuredownload\Aspects\SolrFalAspect;
use BeechIt\FalSecuredownload\Configuration\ExtensionConfiguration;
use BeechIt\FalSecuredownload\ContextMenu\ItemProvider;
use BeechIt\FalSecuredownload\Controller\FileTreeController;
use BeechIt\FalSecuredownload\Controller\FileTreeStateController;
use BeechIt\FalSecuredownload\FormEngine\DownloadStatistics;
use BeechIt\FalSecuredownload\Hooks\CmsLayout;
use BeechIt\FalSecuredownload\Hooks\DocHeaderButtonsHook;
use BeechIt\FalSecuredownload\Hooks\KeSearchFilesHook;
use BeechIt\FalSecuredownload\Hooks\ProcessDatamapHook;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

ExtensionUtility::configurePlugin(
    'FalSecuredownload',
    'Filetree',
    [
        FileTreeController::class => 'tree',
    ],
    // non-cacheable actions
    [
        FileTreeController::class => 'tree',
    ]
);

// FE FileTree leaf open/close state dispatcher
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['FalSecuredownloadFileTreeState'] = FileTreeStateController::class . '::saveLeafState';

// Page module hook
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info']['falsecuredownload_filetree']['fal_securedownload'] =
    CmsLayout::class . '->getExtensionSummary';

// Add FolderPermission button to docheader of filelist
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['Backend\Template\Components\ButtonBar']['getButtonsHook']['FalSecuredownload'] =
    DocHeaderButtonsHook::class . '->getButtons';

// Context menu
// Only needed for TYPO3 v11
// https://docs.typo3.org/c/typo3/cms-core/12.4/en-us/Changelog/12.0/Breaking-96333-AutoConfigurationOfContextMenuItemProviders.html
$GLOBALS['TYPO3_CONF_VARS']['BE']['ContextMenu']['ItemProviders'][1547242135] = ItemProvider::class;

// refresh file tree after change in tx_falsecuredownload_folder record
$GLOBALS ['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = ProcessDatamapHook::class;
$GLOBALS ['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] = ProcessDatamapHook::class;

// ext:ke_search custom indexer hook
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ke_search']['modifyFileIndexEntryFromContentIndexer'][] = KeSearchFilesHook::class;
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ke_search']['modifyFileIndexEntry'][] = KeSearchFilesHook::class;

if (ExtensionConfiguration::trackDownloads()) {
    // register FormEngine node for rendering download statistics in fe_users
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1470920616] = [
        'nodeName' => 'falSecureDownloadStats',
        'priority' => 40,
        'class' => DownloadStatistics::class,
    ];
}
