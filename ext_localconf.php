<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43($_EXTKEY, 'pi2/class.tx_listfeusers_pi2.php', '_pi2', 'list_type', 0);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43($_EXTKEY, 'pi3/class.tx_listfeusers_pi3.php', '_pi3', 'list_type', 0);
