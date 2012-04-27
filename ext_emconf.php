<?php

########################################################################
# Extension Manager/Repository config file for ext "cabag_import".
#
# Auto generated 27-04-2012 11:45
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'CAB AG Import',
	'description' => 'Extension to handle imports with most possible flexibility.',
	'category' => 'misc',
	'author' => 'Sonja Scholz / Jonas Felix (And thanks to: nb, st and dk)',
	'author_email' => 'ss@cabag.ch, jf@cabag.ch',
	'shy' => '',
	'dependencies' => 'cms',
	'conflicts' => '',
	'priority' => '',
	'module' => 'mod1',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '1.3.3',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:62:{s:9:"ChangeLog";s:4:"3eae";s:10:"README.txt";s:4:"2ec4";s:8:"TODO.txt";s:4:"3c00";s:21:"ext_conf_template.txt";s:4:"3694";s:12:"ext_icon.gif";s:4:"7047";s:15:"ext_icon__x.gif";s:4:"7047";s:17:"ext_localconf.php";s:4:"f839";s:14:"ext_tables.php";s:4:"b88c";s:14:"ext_tables.sql";s:4:"8c8c";s:16:"locallang_db.xml";s:4:"c005";s:7:"tca.php";s:4:"d13a";s:12:"cli/conf.php";s:4:"0fdd";s:16:"cli/import.phpsh";s:4:"1230";s:18:"doc/manual-doc.sxw";s:4:"b4a3";s:19:"doc/wizard_form.dat";s:4:"1494";s:20:"doc/wizard_form.html";s:4:"5108";s:36:"lib/class.tx_cabagimport_handler.php";s:4:"e4e6";s:71:"lib/fieldprocs/class.tx_cabagimport_fieldproc_cachedtransformselect.php";s:4:"46ea";s:58:"lib/fieldprocs/class.tx_cabagimport_fieldproc_copyfile.php";s:4:"7068";s:58:"lib/fieldprocs/class.tx_cabagimport_fieldproc_copypage.php";s:4:"870b";s:53:"lib/fieldprocs/class.tx_cabagimport_fieldproc_dam.php";s:4:"a081";s:55:"lib/fieldprocs/class.tx_cabagimport_fieldproc_files.php";s:4:"41a7";s:58:"lib/fieldprocs/class.tx_cabagimport_fieldproc_floatval.php";s:4:"312a";s:63:"lib/fieldprocs/class.tx_cabagimport_fieldproc_if_preg_match.php";s:4:"73b0";s:56:"lib/fieldprocs/class.tx_cabagimport_fieldproc_intval.php";s:4:"f266";s:65:"lib/fieldprocs/class.tx_cabagimport_fieldproc_maptranslations.php";s:4:"ae77";s:55:"lib/fieldprocs/class.tx_cabagimport_fieldproc_mkdir.php";s:4:"846a";s:52:"lib/fieldprocs/class.tx_cabagimport_fieldproc_mm.php";s:4:"8633";s:61:"lib/fieldprocs/class.tx_cabagimport_fieldproc_passwordgen.php";s:4:"ec95";s:65:"lib/fieldprocs/class.tx_cabagimport_fieldproc_preg_match_keys.php";s:4:"07b3";s:62:"lib/fieldprocs/class.tx_cabagimport_fieldproc_preg_replace.php";s:4:"442b";s:58:"lib/fieldprocs/class.tx_cabagimport_fieldproc_relation.php";s:4:"6e39";s:65:"lib/fieldprocs/class.tx_cabagimport_fieldproc_save_to_raw_row.php";s:4:"297a";s:56:"lib/fieldprocs/class.tx_cabagimport_fieldproc_select.php";s:4:"3e7d";s:58:"lib/fieldprocs/class.tx_cabagimport_fieldproc_sendmail.php";s:4:"c506";s:60:"lib/fieldprocs/class.tx_cabagimport_fieldproc_strtolower.php";s:4:"42ba";s:59:"lib/fieldprocs/class.tx_cabagimport_fieldproc_strtotime.php";s:4:"8a4b";s:54:"lib/fieldprocs/class.tx_cabagimport_fieldproc_text.php";s:4:"4f19";s:59:"lib/fieldprocs/class.tx_cabagimport_fieldproc_transform.php";s:4:"8d46";s:48:"lib/interfaces/int.tx_cabagimport_ifieldproc.php";s:4:"c0b8";s:48:"lib/interfaces/int.tx_cabagimport_iinterpret.php";s:4:"962c";s:45:"lib/interfaces/int.tx_cabagimport_isource.php";s:4:"eb9c";s:46:"lib/interfaces/int.tx_cabagimport_istorage.php";s:4:"00dd";s:54:"lib/interpreter/class.tx_cabagimport_interpret_csv.php";s:4:"8208";s:66:"lib/interpreter/class.tx_cabagimport_interpret_csv_alternative.php";s:4:"20d9";s:54:"lib/interpreter/class.tx_cabagimport_interpret_xml.php";s:4:"b9f8";s:48:"lib/sources/class.tx_cabagimport_source_file.php";s:4:"5db1";s:48:"lib/sources/class.tx_cabagimport_source_ldap.php";s:4:"de3f";s:49:"lib/sources/class.tx_cabagimport_source_mssql.php";s:4:"f771";s:49:"lib/sources/class.tx_cabagimport_source_mysql.php";s:4:"1b7c";s:57:"lib/sources/class.tx_cabagimport_source_recordsintree.php";s:4:"7afc";s:47:"lib/sources/class.tx_cabagimport_source_xls.php";s:4:"a446";s:48:"lib/storage/class.tx_cabagimport_storage_csv.php";s:4:"b704";s:48:"lib/storage/class.tx_cabagimport_storage_sql.php";s:4:"0d78";s:48:"lib/storage/class.tx_cabagimport_storage_tce.php";s:4:"7939";s:14:"mod1/clear.gif";s:4:"cc11";s:13:"mod1/conf.php";s:4:"fae6";s:12:"mod1/err.txt";s:4:"d41d";s:14:"mod1/index.php";s:4:"942d";s:18:"mod1/locallang.xml";s:4:"d881";s:22:"mod1/locallang_mod.xml";s:4:"45d7";s:19:"mod1/moduleicon.gif";s:4:"7047";}',
	'suggests' => array(
	),
);

?>