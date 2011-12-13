<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008 Sonja Scholz <ss@cabag.ch>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

require_once(PATH_txdam.'lib/class.tx_dam_indexing.php');

/**
 * Field proccessor that adds fiels to DAM not existing and creates Relations to them
 * Checkout: http://forge.typo3.org/projects/extension-dam/wiki/Call_indexing_API_from_your_extension
 *
 * @author	Sonja Scholz <ss@cabag.ch>
 * @package	TYPO3
 * @subpackage	tx_cabagimport
 */
class  tx_cabagimport_fieldproc_dam implements tx_cabagimport_ifieldproc {
	// contains the configuration
	private $conf = false;
	private $object_handler = false;
	private $damIndexer = false;
	
	/**
	* main()
	* 
	* - Proove if the field is empty if the required option isset
	* - Loop the stack part and call for every part the right function in this class
	* 
	* @param	array	mapping configuration for the current part includes required option and stack
	* @param	string	field with the data
	* @return	array	modificated field data/exception if a required field is empty for example
	*/
	function main($stackPartConf=false, $object_handler) {
		global $LANG;
		
		$this->object_handler = $object_handler;
		
		if($stackPartConf != false) {
			$this->conf = $stackPartConf;
		} else {
			throw new Exception($LANG->getLL('fieldProc.exception.noConfig'));
		}
		
		// check singleFile string from config and add absolute path
		if(empty($this->conf['singleFile'])) {
			throw new Exception($LANG->getLL('fieldProc.exception.singleFileNotSet'));
		} else {
			$this->conf['singleFile'] = PATH_site.$this->conf['singleFile'];
		}
		
		// check if singleFile exists
		if(!file_exists($this->conf['singleFile'])){
			throw new Exception($LANG->getLL('fieldProc.exception.fileNotFound').'-'.$this->conf['singleFile']);
		}
		
		// check if sql is availavle to select the uid to relate to
		if(empty($this->conf['selectUidToRelateTo'])){
			throw new Exception($LANG->getLL('fieldProc.exception.selectUidToRelateToNotSet'));
		}
		
		// get preset data from configuration
		if(!empty($this->conf['damPresetData.'])) {
			$damPresetData = $this->conf['damPresetData.'];
		} else {
			$damPresetData = array();
		}
		
		// check if sql is availavle to select the uid to relate to
		if(empty($this->conf['tableNameToRelateTo'])){
			throw new Exception($LANG->getLL('fieldProc.exception.tableNameToRelateToNotSet'));
		}
		
		// check if sql is availavle to select the uid to relate to
		if(empty($this->conf['fieldNameToRelateTo'])){
			throw new Exception($LANG->getLL('fieldProc.exception.fieldNameToRelateToNotSet'));
		}
		
		// lookup uid of the record to relate the new DAM record to
		$uidToRelateTo = $this->getRelateToUid($this->conf['selectUidToRelateTo']);
		
		if($object_handler->dryMode == false){
			// index singleFile
			$uidDAMResource = $this->indexFile($this->conf['singleFile'], $damPresetData);
		
			// finaly create the relation and be happy
			$this->createDAMRelation($uidDAMResource, $uidToRelateTo, $this->conf['tableNameToRelateTo'], $this->conf['fieldNameToRelateTo']);
		
			// set the DAM record as currentFieldValue
			$object_handler->currentFieldValue = $uidDAMResource;
		} else {
			$object_handler->currentFieldValue = true;
		}
		
		return true;
	}
	
	/** 
	 * getRelateToUid()
	 *
	 * - Calls the DAM indexer
	 * 
	 * @param string sql statement
	 * @return uid of the relateToUid
	 */
	private function getRelateToUid($sql) {
		global $LANG;

		$selectRes = $GLOBALS['TYPO3_DB']->sql_query($sql);
		$result = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($selectRes);

		if(empty($result['uid'])) {			
			throw new Exception($LANG->getLL('fieldProc.exception.selectUidToRelateToNoResult'));
		}
		
		return $result['uid'];
	}
	
	/** 
	 * indexFile()
	 *
	 * - Calls the DAM indexer
	 * 
	 * @param string path to the file (relative/absolute?)
	 * @param array with data to set within the DAM
	 * @return boolean TRUE/FALSE if succeded or not
	 */
	private function indexFile($filePath, $damPresetData) {
		
		// get indexer object of DAM
		$this->damIndexer = t3lib_div::makeInstance('tx_dam_indexing');
		$this->damIndexer->init();
		
		// set to manual mode
		$this->damIndexer->setRunType('man');
		
		// set pid for dam record storage
		$this->damIndexer->setPid(tx_dam_db::getPid());
		
		// set preset of met information for indexing
		$this->damIndexer->dataPreset = $damPresetData;
		
		// index and get metdata of indexed file
		$this->damIndexer->indexFile($filePath);
		
		// get meta information with uid
		$meta = tx_dam::meta_getDataForFile($filePath);
		
		return $meta['uid'];
	}
	
	/** 
	 * createDAMRelation()
	 *
	 * - Creates relation record from the imported record to the DAM record
	 * 
	 * @param int dam resource uid
	 * @param int of the record
	 * @param string table name
	 * @param string field name
	 * @return boolean TRUE/FALSE if succeded or not
	 */
	private function createDAMRelation($uidDAMResource, $uidToRelateTo, $table, $field) {
		$GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid_local',
			'tx_dam_mm_ref',
			'uid_local = \''.$uidDAMResource.'\'
				AND uid_foreign = \''.$uidToRelateTo.'\'
				AND tablenames = \''.$table.'\'
				AND ident = \''.$field.'\''
			);
		$existing = $GLOBALS['TYPO3_DB']->sql_num_rows($res);
		if($existing == 0) {
			$insertArray = array(
					'uid_local' => $uidDAMResource,
					'uid_foreign' => $uidToRelateTo,
					'tablenames' => $table,
					'ident' =>  $field,
					'sorting_foreign' => 1,
					'sorting' => 0
				);
			$mmInsertQuery = $GLOBALS['TYPO3_DB']->exec_INSERTquery(
				'tx_dam_mm_ref',
				$insertArray
			);
		}
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cabag_import/lib/class.tx_cabagimport_fieldproc_text.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cabag_import/lib/class.tx_cabagimport_fieldproc_text.php']);
}
?>
