<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Dimitri Koenig <dk@cabag.ch>
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

/**
 * Field proccessor class for the 'cabag_import' extension.
 *
 * @author	Dimitri Koenig <dk@cabag.ch>
 * @package	TYPO3
 * @subpackage	tx_cabagimport
 */
class tx_cabagimport_fieldproc_if_preg_match implements tx_cabagimport_ifieldproc {
	// contains the configuration
	var $conf;
	
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
		
		if($stackPartConf != false) {
			$this->conf = $stackPartConf;
		} else {
			throw new Exception($LANG->getLL('fieldProc.exception.noConfig'));
		}
		
		// use value or current field value
		if(!empty($this->conf['value'])){
			$object_handler->currentFieldValue = $this->conf['value'];
		}
		
		if(substr($this->conf['pattern'],0,1) != '/') {
			$this->conf['pattern'] = '/'.$this->conf['pattern'].'/';
		}

		preg_match($this->conf['pattern'], $object_handler->currentFieldValue, $matches);
		if (count($matches) == 0) {
			$object_handler->currentFieldValue = '';
		} else {
			if ($this->conf['returnMatchPosition']) {
				$returnMatchPosition = intval($this->conf['returnMatchPosition']);
				if ($matches[$returnMatchPosition]) {
					$object_handler->currentFieldValue = $matches[$returnMatchPosition];
				} else {
					$object_handler->currentFieldValue = '';
				}
			}
		}

		return TRUE;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cabag_import/lib/class.tx_cabagimport_fieldproc.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cabag_import/lib/class.tx_cabagimport_fieldproc.php']);
}
?>
