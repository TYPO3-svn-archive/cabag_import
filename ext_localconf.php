<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_cabagimport_config=1
');

// Available default storages
// -----------------------------

// TCE
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['storage']['tce'] = 'EXT:cabag_import/lib/storage/class.tx_cabagimport_storage_tce.php:tx_cabagimport_storage_tce';

// SQL
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['storage']['sql'] = 'EXT:cabag_import/lib/storage/class.tx_cabagimport_storage_sql.php:tx_cabagimport_storage_sql';

// CSV
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['storage']['csv'] = 'EXT:cabag_import/lib/storage/class.tx_cabagimport_storage_csv.php:tx_cabagimport_storage_csv';


// Available default sources
// -----------------------------

// regular file
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['source']['file'] = 'EXT:cabag_import/lib/sources/class.tx_cabagimport_source_file.php:tx_cabagimport_source_file';

// xls file
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['source']['xls'] = 'EXT:cabag_import/lib/sources/class.tx_cabagimport_source_xls.php:tx_cabagimport_source_xls';

// mysql connection
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['source']['mysql'] = 'EXT:cabag_import/lib/sources/class.tx_cabagimport_source_mysql.php:tx_cabagimport_source_mysql';

// recordsintree connection
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['source']['recordsintree'] = 'EXT:cabag_import/lib/sources/class.tx_cabagimport_source_recordsintree.php:tx_cabagimport_source_recordsintree';

// mssql connection
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['source']['mssql'] = 'EXT:cabag_import/lib/sources/class.tx_cabagimport_source_mssql.php:tx_cabagimport_source_mssql';

// ldap connection
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['source']['ldap'] = 'EXT:cabag_import/lib/sources/class.tx_cabagimport_source_ldap.php:tx_cabagimport_source_ldap';

// Available default interprets
// -----------------------------

// csv
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['interpret']['csv'] = 'EXT:cabag_import/lib/interpreter/class.tx_cabagimport_interpret_csv.php:tx_cabagimport_interpret_csv';

// csv with php and not with fgetcsv
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['interpret']['csv_alternative'] = 'EXT:cabag_import/lib/interpreter/class.tx_cabagimport_interpret_csv_alternative.php:tx_cabagimport_interpret_csv_alternative';

// xml
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['interpret']['xml'] = 'EXT:cabag_import/lib/interpreter/class.tx_cabagimport_interpret_xml.php:tx_cabagimport_interpret_xml';


// Available default fieldprocs
// -----------------------------

// maptranslations
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['fieldproc']['maptranslations'] = 'EXT:cabag_import/lib/fieldprocs/class.tx_cabagimport_fieldproc_maptranslations.php:tx_cabagimport_fieldproc_maptranslations';

// relation
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['fieldproc']['relation'] = 'EXT:cabag_import/lib/fieldprocs/class.tx_cabagimport_fieldproc_relation.php:tx_cabagimport_fieldproc_relation';

// text
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['fieldproc']['TEXT'] = 'EXT:cabag_import/lib/fieldprocs/class.tx_cabagimport_fieldproc_text.php:tx_cabagimport_fieldproc_text';

// mm relations
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['fieldproc']['mm'] = 'EXT:cabag_import/lib/fieldprocs/class.tx_cabagimport_fieldproc_mm.php:tx_cabagimport_fieldproc_mm';

// select
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['fieldproc']['select'] = 'EXT:cabag_import/lib/fieldprocs/class.tx_cabagimport_fieldproc_select.php:tx_cabagimport_fieldproc_select';

// cachedtransformselect
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['fieldproc']['cachedtransformselect'] = 'EXT:cabag_import/lib/fieldprocs/class.tx_cabagimport_fieldproc_cachedtransformselect.php:tx_cabagimport_fieldproc_cachedtransformselect';

// preg_replace
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['fieldproc']['preg_replace'] = 'EXT:cabag_import/lib/fieldprocs/class.tx_cabagimport_fieldproc_preg_replace.php:tx_cabagimport_fieldproc_preg_replace';

// strtotime
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['fieldproc']['strtotime'] = 'EXT:cabag_import/lib/fieldprocs/class.tx_cabagimport_fieldproc_strtotime.php:tx_cabagimport_fieldproc_strtotime';

// strtolower
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['fieldproc']['strtolower'] = 'EXT:cabag_import/lib/fieldprocs/class.tx_cabagimport_fieldproc_strtolower.php:tx_cabagimport_fieldproc_strtolower';

// intval
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['fieldproc']['intval'] = 'EXT:cabag_import/lib/fieldprocs/class.tx_cabagimport_fieldproc_intval.php:tx_cabagimport_fieldproc_intval';

// floatval
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['fieldproc']['floatval'] = 'EXT:cabag_import/lib/fieldprocs/class.tx_cabagimport_fieldproc_floatval.php:tx_cabagimport_fieldproc_floatval';

// transform
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['fieldproc']['transform'] = 'EXT:cabag_import/lib/fieldprocs/class.tx_cabagimport_fieldproc_transform.php:tx_cabagimport_fieldproc_transform';

// files 
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['fieldproc']['files'] = 'EXT:cabag_import/lib/fieldprocs/class.tx_cabagimport_fieldproc_files.php:tx_cabagimport_fieldproc_files';

// copyfile 
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['fieldproc']['copyfile'] = 'EXT:cabag_import/lib/fieldprocs/class.tx_cabagimport_fieldproc_copyfile.php:tx_cabagimport_fieldproc_copyfile';

// passwordgen
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['fieldproc']['passwordgen'] = 'EXT:cabag_import/lib/fieldprocs/class.tx_cabagimport_fieldproc_passwordgen.php:tx_cabagimport_fieldproc_passwordgen';

// sendmail
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['fieldproc']['sendmail'] = 'EXT:cabag_import/lib/fieldprocs/class.tx_cabagimport_fieldproc_sendmail.php:tx_cabagimport_fieldproc_sendmail';

// mkdir
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['fieldproc']['mkdir'] = 'EXT:cabag_import/lib/fieldprocs/class.tx_cabagimport_fieldproc_mkdir.php:tx_cabagimport_fieldproc_mkdir';

// dam
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['fieldproc']['dam'] = 'EXT:cabag_import/lib/fieldprocs/class.tx_cabagimport_fieldproc_dam.php:tx_cabagimport_fieldproc_dam';

// preg_match_keys
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['fieldproc']['preg_match_keys'] = 'EXT:cabag_import/lib/fieldprocs/class.tx_cabagimport_fieldproc_preg_match_keys.php:tx_cabagimport_fieldproc_preg_match_keys';

// if_preg_match
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['fieldproc']['if_preg_match'] = 'EXT:cabag_import/lib/fieldprocs/class.tx_cabagimport_fieldproc_if_preg_match.php:tx_cabagimport_fieldproc_if_preg_match';

// save_to_raw_row
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['fieldproc']['save_to_raw_row'] = 'EXT:cabag_import/lib/fieldprocs/class.tx_cabagimport_fieldproc_save_to_raw_row.php:tx_cabagimport_fieldproc_save_to_raw_row';

// copypage 
$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['fieldproc']['copypage'] = 'EXT:cabag_import/lib/fieldprocs/class.tx_cabagimport_fieldproc_copypage.php:tx_cabagimport_fieldproc_copypage';

?>