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


// Interfaces
require_once(t3lib_extMgm::extPath('cabag_import').'lib/interfaces/int.tx_cabagimport_ifieldproc.php');
require_once(t3lib_extMgm::extPath('cabag_import').'lib/interfaces/int.tx_cabagimport_iinterpret.php');
require_once(t3lib_extMgm::extPath('cabag_import').'lib/interfaces/int.tx_cabagimport_isource.php');
require_once(t3lib_extMgm::extPath('cabag_import').'lib/interfaces/int.tx_cabagimport_istorage.php');

/**
 * Handler class for the 'cabag_import' extension.
 *
 * @author	Sonja Scholz <ss@cabag.ch>
 * @package	TYPO3
 * @subpackage	tx_cabagimport
 */
class  tx_cabagimport_handler  {
	// Array with the configuration for the current import
	var $conf = array();
	
	// original configuration UID
	var $importConfUid;
	
	// Array with the current row data from the source
	var $currentRowRaw;
	
	// Array with the parsed row 
	var $currentRowParsed;
	
	// String with the current field value
	var $currentFieldValue;
	
	// Number of the current row
	var $currentRowNumber = 1;
	
	// Array with the current row conf
	var $currentRowConf;
	
	// Pid for the current row
	var $currentPid;
	
	// field proc object
	var $currentFieldProc;
	
	// Array with the first/keyfield row
	var $keyFieldRow;
	
	// if set, there won't be any changes to the db
	var $dryMode = true;
	
	// current timestamp
	var $time;
	
	// Array with status messages
	var $messages = array();
	
	// Objects
	var $source = false;
	var $interpret = false;
	var $db = false;

	var $countOfRecords = 0;

	// arrays with available "modules"
	// defined in ext_localconf.php as SC_OPTIONS
	var $availableFieldProcs = array();
	var $availableSources = array();
	var $availableInterprets = array();
	
	
	/**
	* Constructor
	* 
	* - Writes the configuration to the global $conf array
	* - Instanciate a new object of the class tx_cabagimport_db
	* - Instanciate a new object of the class tx_cabagimport_source_file
	* 
	* @param	array	configuration of the import
	*/
	function tx_cabagimport_handler($conf) {
		global $LANG, $TYPO3_CONF_VARS;
		
		// extension manager configuration
		$this->extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['cabag_import']);

		if (!empty($this->extConf['memoryLimit'])) {
			ini_set('memory_limit', $this->extConf['memoryLimit']);
		}
		
		// Set the time of this import process
		$this->time = time();

		// Set number of written entries to 0
		$this->countOfRecords = 0;

		// arrays with available "modules"
		$this->availableFieldProcs = $TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['fieldproc'];
		$this->availableSources = $TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['source'];
		$this->availableInterprets =  $TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['interpret'];
		$this->availableStorages =  $TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['storage'];
		
		if(is_array($conf)){
			$this->conf = $conf;
		} else {
			throw new Exception($LANG->getLL('handler.exception.emptyConfig'));
		}
		
		// Instanciate a new object of the class tx_cabagimport_db
		if(!empty($this->conf['storage'])){				 		
			$this->db = &t3lib_div::getUserObj($this->availableStorages[$this->conf['storage']]);
		} else {
			$this->db = &t3lib_div::getUserObj($this->availableStorages['tce']);
		}
		
		if(!($this->db instanceOf tx_cabagimport_istorage)){
			throw new Exception($LANG->getLL('handler.exception.nodbobject'));
		} else {
			if(is_array($this->conf['storage.'])){
				$this->db->main($this, $this->conf['storage.']);
			} else {
				$this->db->main($this);
			}
		}
		
		// Instanciate a new object of the class tx_cabagimport_source_file
		if(!empty($this->conf['source.']['interpret'])){
			if(!empty($this->availableInterprets[$this->conf['source.']['interpret']])){				 		
				$this->interpret = &t3lib_div::getUserObj($this->availableInterprets[$this->conf['source.']['interpret']]);
				
				if(!($this->interpret instanceOf tx_cabagimport_iinterpret)){
					$this->interpret = false;
				}
			}
			if(!$this->interpret){
				// debug
				if($TYPO3_CONF_VARS['SYS']['enable_DLOG']) t3lib_div::devLog('could not load interpret', 'cabag_import', 3, array('available' => $this->availableInterprets, 'source config' => $this->conf['source.']));
				throw new Exception($LANG->getLL('interpret.exception.noType'));
			}
		}
		
		// Instanciate a new object of the class tx_cabagimport_source_file
		if(!empty($this->conf['source'])){
			if(!empty($this->availableSources[$this->conf['source']])){				 		
				$this->source = &t3lib_div::getUserObj($this->availableSources[$this->conf['source']]);
				
				if(!($this->source instanceOf tx_cabagimport_isource)){
					$this->source = false;
				}
			}
		}
		
		if(!$this->source){
			throw new Exception($LANG->getLL('interpret.exception.noSourceObject'));
		}
	}
	
	/**
	* Main handler function
	* 
	* - Decide, if a dryRun should be done or not
	* - call main() from source class to check the source configuration
	* - Call getNextRow() from class tx_cabagimport_source_file to get the firstRowKeys
	* - Call getNextRow() from class tx_cabagimport_source_file with the mapping configuration
	* - Loop the mapping conf and replace the constants with the values
	* - Call the rowProc for every row
	* - If dryRun is false call writeRow() from class tx_cabagimport_db
	* - Catch exceptions from writeRow and source class or output a status for the requestet row
	* - Call deleteObsolete, if the handler part of the config consists this option
	* - Archive the import file, if the handler part of the config consists this option
	* 
	* @param	bool	flag to decide if a dryRun should be done(default) or not
	*/
	function main($dryRun=true) {
		global $LANG, $TYPO3_CONF_VARS;
		$this->dryMode = $dryRun;
		
		$this->source->open($dryRun, $this->conf['source.'], $this);
		
		// Check, if the first contains key fields
		if($this->conf['handler.']['firstRowAreKeys'] == 1) {
			// get first row as key fields
			$this->keyFieldRow = $this->source->getNextRow();
		}
		
		// If there is a mapping configuration, so get row per row from the source
		if(!empty($this->conf['mapping.'])) {
			
			// go trought the rows
			while($this->currentRowRaw = $this->source->getNextRow()) {
				
				// keep track of the rowNumber
				$this->currentRowNumber++;
				
				// check the range option of the handler configuration
				if(!empty($this->conf['handler.']['rangeTo'])) {
					// ignore rows which are before the start point in the configured range
					if($this->currentRowNumber < $this->conf['handler.']['rangeFrom']) {
						if($TYPO3_CONF_VARS['SYS']['enable_DLOG']) t3lib_div::devLog('skip row '.$this->currentRowNumber, 'cabag_import', -1);
						continue;
					}
					
					// stop importing if we are out of the configured range
					if($this->currentRowNumber > $this->conf['handler.']['rangeTo']) {
						if($TYPO3_CONF_VARS['SYS']['enable_DLOG']) t3lib_div::devLog('stop at row '.$this->currentRowNumber, 'cabag_import', -1);
						break;
					}
				}
				
				// set the keys of the data Array if there is a keyFieldRow
				if($this->conf['handler.']['firstRowAreKeys'] == 1) {
					// add cols if not the same amount
					if(count($this->keyFieldRow) < count($this->currentRowRaw)) {
						$difference = count($this->currentRowRaw) - count($this->keyFieldRow);
						for($i = 0; $i < $difference; $i ++) {
							$this->keyFieldRow[] = '';
						}
					} elseif(count($this->keyFieldRow) > count($this->currentRowRaw)) {
						$difference = count($this->keyFieldRow) - count($this->currentRowRaw);
						for($i = 0; $i < $difference; $i ++) {
							$this->currentRowRaw[] = '';
						}
					}
					// use keys for rows
					$this->currentRowRaw = array_combine($this->keyFieldRow, $this->currentRowRaw);
				}
				
				// debug
				if($TYPO3_CONF_VARS['SYS']['enable_DLOG']) t3lib_div::devLog('raw row '.$this->currentRowNumber, 'cabag_import', -1, $this->currentRowRaw);
				
				// add current row number if configured
				if(!empty($this->conf['handler.']['addCurrentRowNumber'])){
					$this->currentRowRaw['currentRowNumber'] = $this->currentRowNumber;
				}
				
				// replace constants
				$this->currentRowConf = $this->replaceConstants($this->conf['mapping.'], $this->currentRowRaw);
				
				// get page id and unset it
				if(!empty($this->currentRowConf['pid.'])){
					$this->currentPid = $this->fieldProc($this->currentRowConf['pid.'], 'pid');
					unset($this->currentRowConf['pid.']);
				}
				
				// try and catch if continue after invalid row is set
				try {
					
					// get page id from config if not available or throw error
					if(empty($this->currentPid)){
						if(empty($this->conf['handler.']['defaultPid'])){
							throw new Exception($LANG->getLL('handler.exception.noPageId').':'.$this->currentRowNumber);
						} else {
							$this->currentPid = $this->conf['handler.']['defaultPid'];
						}
					}
					
					// Call the rowProc for every row - to process the row with every field
					$this->currentRowParsed = false;
					$this->currentRowParsed = $this->rowProc($dryRun);
					
					// writhe row if array
					if(is_array($this->currentRowParsed)){
						// debug
						if($TYPO3_CONF_VARS['SYS']['enable_DLOG']) t3lib_div::devLog('parsed row '.$this->currentRowNumber, 'cabag_import', -1, $this->currentRowParsed);
						
						$this->db->writeRow($this->currentRowParsed, $this);

						// raise the number of written records
						$this->countOfRecords++;
					}
				} catch (Exception $exception) {
					if(!empty($this->conf['handler.']['continueAfterInvalidRow'])){
						$this->setMessage('<div style="color:red">'.$exception->getMessage().' - '.$this->currentRowNumber.'</div>');
					} else {
						throw $exception;
					}
				}
				
				// memory optimizing
				$this->currentRowRaw = NULL;
				$this->currentRowParsed = NULL;
				$this->currentFieldValue = NULL;
				$this->currentRowConf = NULL;
				$this->currentFieldProc = NULL;
			}
		
		
		} else {
			throw new Exception($LANG->getLL('handler.exception.noMappingConf'));
		}
		
		// delete obsolete records
		if(!empty($this->conf['handler.']['deleteObsolete'])){
			if(!empty($this->conf['handler.']['deleteObsolete.']['addWhere'])){
				$this->db->deleteObsolete($this->conf['handler.']['table'], $this->conf['handler.']['deleteObsolete.']['addWhere']);
			} else {
				$this->db->deleteObsolete($this->conf['handler.']['table']);
			}
		}
		
		$this->source->close();
		
		return true;
	}
	
	/**
	* Returns the configuration array from import record
	*
	* @param	int	uid of the import record
	* @return	array configuration
	*
	*/
	static function getConf($importConfUid){
		global $LANG;
		
		// Check if the importConfUid was given
		if($importConfUid == false) {
			throw new Exception($LANG->getLL('handler.exception.emptyimportConfUid'));
		} else {
			$confRes = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'tx_cabagimport_config', 'tx_cabagimport_config.uid = '.$importConfUid.' '.t3lib_BEfunc::deleteClause('tx_cabagimport_config'), '', 'title ASC');
			
			if($GLOBALS['TYPO3_DB']->sql_num_rows($confRes) > 0) {
				$importConfRecord = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($confRes);
			} else {
				throw new Exception($LANG->getLL('handler.exception.wrongimportConfUid'));
			}
		}
		
		// Puts the configuration through the TypoScript parser of TYPO3
		$tsParser  = t3lib_div::makeInstance('t3lib_TSparser');
		$tsParser->parse($importConfRecord['configuration']);
		
		// Writes the configuration to the global $conf array
		return $tsParser->setup;
	}
	
	/**
	* replaces the constants in $conf with the $data
	*
	* @param array configuration 
	* @param array data for the constants
	* @return array configuration
	*/
	function replaceConstants($conf, $data){
		$returnConf = $conf;
		$this->replaceConstantsWalkData = $data;
		array_walk_recursive($returnConf, array($this, 'replaceConstantsWalk'));
		$this->replaceConstantsWalkData = NULL;
		return $returnConf;
	}
	
	/**
	* replaces the constants in $conf with the $data
	*
	* @param string value to str_replace
	* @param mixed key in the array
	* @return null
	*/
	function replaceConstantsWalk(&$value, $key){
		reset($this->replaceConstantsWalkData);
		while(list($constant, $constantValue) = each($this->replaceConstantsWalkData)){
			$constantValue = $this->convertRawValue($constantValue);
			$value = str_replace('{$'.$constant.'}', $constantValue, $value);
		}
	}
	
	/**
	* converts raw values
	*
	* @param string to convert
	* @return string converted string
	*/
	function convertRawValue($value){
		// charset converstion
		if(!empty($this->conf['handler.']['in_charset']) && !empty($this->conf['handler.']['out_charset'])){  
			$value = iconv($this->conf['handler.']['in_charset'], 	$this->conf['handler.']['out_charset'], $value);
		}          
		
		return $value;
	}
	
	/**
	* rowProc()
	* 
	* - Loop the fields
	* - Loop the stack of every field and call the right fieldproc
	* - check if the value issn't empty if the required flag isset
	* - write the proccessed field values to the finishRowValue array
	*
	* - Throw an exception if an error occurs or the field is empty although it is required
	* 
	* @param	bool	dryMode
	* @return	array	finish array to write in the DB 
	*/
	function rowProc($dryRun=true){
		global $LANG;
		$finishRowValue = array();
		
		// Loop the fields
		reset($this->currentRowConf);
		while(list($field, $fieldconf) = each($this->currentRowConf)) {
			// direct mapping
			if(!is_array($fieldconf)){
				//if(!empty($this->currentRowRaw[$fieldconf])){
					$finishRowValue[$field] = $this->convertRawValue($this->currentRowRaw[$fieldconf]);
				//}
				
				// check if the value issn't empty if the required flag isset
				if(!empty($this->currentRowConf[$field.'.']['required']) && empty($finishRowValue[$field])) {
					throw new Exception($LANG->getLL('handler.exception.requiredFieldEmpty').' - '.$this->currentRowNumber.' - direct -'.$fieldconf);
				}
				
			// regular field 
			} else if(empty($this->currentRowConf[str_replace('.', '', $field)])){
				$fieldKey = str_replace('.', '', $field);
				// write the proccessed field values to the finishRowValue array
				$finishRowValue[$fieldKey] = $this->fieldProc($fieldconf, $field);
				
				if ($fieldconf['unsetIfEmpty'] && empty($finishRowValue[$fieldKey])) {
					unset($finishRowValue[$fieldKey]);
				}
				
				// check if the value issn't empty if the required flag isset
				if(!empty($fieldconf['required']) && empty($finishRowValue[str_replace('.', '', $field)])) {
					throw new Exception($LANG->getLL('handler.exception.requiredFieldEmpty').' - '.$this->currentRowNumber.' - stack - '.$field);
				}
			}
		}
		
		return $finishRowValue;
	}
	
	
	/**
	* Loads the fieldproc 
	*
	* @param $fieldconf configuration for the fieldproc
	* @param $fieldname name of the fieldproc
	*
	*/
	function fieldProc($fieldconf, $fieldname){
		global $LANG;
		$this->currentFieldValue = false;
		
		// go trought the stack
		foreach($fieldconf['stack.'] as $stackposition => $positionvalue) {
			// if the stack part isn't an array call a fieldproc
			if(!is_array($positionvalue) && !empty($positionvalue)) {
				switch($positionvalue) {
					default:
						// Dynamical call of an unknown fieldproc
						if(!empty($this->availableFieldProcs[$positionvalue])){
							$this->currentFieldProc = &t3lib_div::getUserObj($this->availableFieldProcs[$positionvalue]);
							
							if($this->currentFieldProc instanceof tx_cabagimport_ifieldproc) {
								// replace constant of current value
								if(!empty($fieldconf['stack.'][$stackposition.'.']) && is_array($fieldconf['stack.'][$stackposition.'.'])){
									$fieldconf['stack.'][$stackposition.'.'] = $this->replaceConstants($fieldconf['stack.'][$stackposition.'.'], array_merge($this->currentRowRaw, array('currentFieldValue' => $this->currentFieldValue)));
								} else {
									$fieldconf['stack.'][$stackposition.'.'] = false;
								}
								
								// call fieldproc
								$this->currentFieldProc->main($fieldconf['stack.'][$stackposition.'.'], $this);
							} else {
								throw new Exception($LANG->getLL('handler.exception.instanceOf').' '.$stackposition);
							}
						} else {
							throw new Exception($LANG->getLL('handler.exception.unknownFieldproc').' - '.$positionvalue);
						}
					break;
				}
			}
		}
			
		return $this->currentFieldValue;
	}
	
	/**
	* setMessage()
	*
	* @param	string	message to set
	* @return	bool	true
	*/
	function setMessage($message){
		global $LANG;
		if(count($this->messages) < $this->extConf['maxerrormessages']){
			$this->messages[] = $message;
			if(count($this->messages) == $this->extConf['maxerrormessages']-1){
				$this->messages[] = $LANG->getLL('handler.messageLimitReached');
			}
		}
		return true;
	}
	
	/**
	* getMessages()
	*
	* @return	array	messages
	*/
	function getMessages(){
		return $this->messages;
	}

	/**
	* getCountOfRecords()
	*
	* @return	int	countOfRecords
	*/
	function getCountOfRecords(){
		return $this->countOfRecords;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']);
}
?>
