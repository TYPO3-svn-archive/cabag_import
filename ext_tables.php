<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

t3lib_extMgm::allowTableOnStandardPages('tx_cabagimport_config');

$TCA["tx_cabagimport_config"] = array (
	"ctrl" => array (
		'title'     => 'LLL:EXT:cabag_import/locallang_db.xml:tx_cabagimport_config',		
		'label'     => 'title',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => "ORDER BY title",	
		'delete' => 'deleted',	
		'enablecolumns' => array (		
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'ext_icon.gif',
	),
	"feInterface" => array (
		"fe_admin_fieldList" => "hidden, title, configuration",
	)
);


if (TYPO3_MODE == 'BE')	{
		// check for uploads/tx_cabagimport and add .htaccess security protection
	if (file_exists(PATH_site . 'uploads/tx_cabagimport') && !file_exists(PATH_site . 'uploads/tx_cabagimport/.htaccess')) {
		file_put_contents(PATH_site . 'uploads/tx_cabagimport/.htaccess', 'Deny from all');
	}
	
	// add module to the backend
	t3lib_extMgm::addModule('web','txcabagimportM1','',t3lib_extMgm::extPath($_EXTKEY).'mod1/');
}
?>