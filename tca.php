<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$GLOBALS['tx_cabag_import-availableOptions'] = '
# storage which writes the rows
storage = tce
storage {
	dontUpdateFields = password
	dontUsePidForKeyField = 0
	
	# needed for ordering records in TYPO3
	// moveAfterField = myFunnyFieldWithUIDofpreviousRecord
}

# Here, no mm fieldproc is possible!
storage = sql
storage {
	dontUpdateFields = password
	tablekeys = uid_local, uid_foreign
	
	# if isset to 1, tstamp will be inserted, needed for deleteObsolete!
	setTstamp = 0
	
	# sets the pid if enabled (sql storage does not set any default fields)
	setPid = 0
	
	# you can use a different database as storage (just mysql support for the moment)
	# Host of the mssql database
	//host = hostname,port
	# login user for the connection
	//user =
	# login password for the connection
	//password =
	# database to connect to
	//database =
}

# Here, no mm fieldproc and no update is possible!
storage = csv
storage {
	fields {
		# additional information needed for the fields
		
		# maps a field with a title that has spaces
		writeTitleRow = 1
	
		fields {
			doctorid.title = Doctor ID
			userid.title = User ID
		}
		
		# fills the field with the timestamp of the handler
		tstamp.isTstamp = 1
	}
	
	file {
		# path must exist and be relative to the TYPO3 directory
		path = fileadmin/user_uploads/tx_cabagimport/some_file.csv
		
		# if set to overwrite, the file (if it exists) will be cleared and overwritten
		mode = overwrite
		
		# if set to append, the file will have the rows appended
		mode = append
		
		# if neither overwrite nor append is set and the file exists, a new file will be created like \'some_file_1.csv\'
	}
	
	# csv delimiter between each field
	delimiter = ,
	// delimiter.chr = 9
	
	# enclosure for a field
	enclosure = "
	// enclosure.chr = 32
}
				
source = file
source {
	# in KB
	maxFileSize = 10000 
	archivePath = uploads/tx_cabagimport/
	
	# path to the file (overwrithen by mod1 input)
	// filePath = fileadmin/user_upload/import/alwaysthesamename.csv
	
	# set to 1 to prevent file source from doing this in php: PATH_site.filePath
	// absoluteFilePath = 0
	
	# search for file within path (overwrithen by filePath)
	searchPath = fileadmin/user_upload/import
	searchPath {
		preg_match = .*importname.*
	}
	
	# interpreter which parses the data
	interpret = csv
	interpret {
		# csv options
		delimiter = ,
		
		# for ascii codes like tab
		// delimiter.chr = 9
		
		enclosure = "
		
		# don\'t use the native fgetcsv (stores the whole file in the RAM -> DON\'T USE IF THAT IS AN ISSUE)
		dontUsePHPFunction = 0
		
		# uses trim() on the values of the first row -> only usable in conjunction with dontUsePHPFunction
		trimFirstRow = 0
	}
	
	# executes sed for the importing file before importing it, this example fixes mac line endings
	preImportSedExpression = s/\r/\n/g
	
	interpret = xml
	interpret {
		recordPath = channel,0,ch,item
	}
}

# select data from a mysql server
source = mysql
source {
	# if not access data is set the TYPO3 database connection is used (NO DBAL SUPPORT!)

	# Host of the mysql database
	host =
	# login user for the connection
	user =
	# login password for the connection
	password =
	# database to connect to
	database =
	# query for the import
	query =
}

# select records from a tree in the current typo3 system
source = recordsintree
source {
	# name of the record table
	table = tt_content
	
	# starting parent page uid
	pid = 44
	
	# additional where
	addWhere = AND hidden = 1
}


source = mssql
source {
	# Host of the mssql database
	host = hostname,port
	# login user for the connection
	user =
	# login password for the connection
	password =
	# database to connect to
	database =
	# do not select a database (database must be preselected by mssql server)
	noDatabase = 1
	# query for the import
	query =
	# The number of records to batch in the buffer.
	batchSize = 
}

# converts xls to csv
source = xls
source {
	# in KB
	maxFileSize = 10000 
	archivePath = uploads/tx_cabagimport/
	
	# force charset if you have problems with umlaute
	forceCharset = en_US.UTF-8
	
	# interpreter which parses the data
	interpret = csv
	interpret {
		# csv options
		delimiter = ,
		
		enclosure = "
	}
}

source = ldap
source {
	# IP Address of username
	server =
	# most commonly 389
	port = 389
	
	# username. This has to be the complete path to the user in the AD tree
	rdn = 
	# password
	password = 
	
	# path where to search in
	base_dn = OU=Switzerland,OU=Feintool,DC=ft,DC=feintool,DC=local
	
	# filter rules. See php ldap docu for further filters
	filter = (&(objectClass=user)(objectCategory=person))
}

handler {
	# table to import to
	table = tx_xy
	
	# row to start the import from
	// rangeFrom = 0
	
	# row to end the import
	// rangeTo = 1000
	
	# fields to identify records and rows
	keyFields = field_xy
	
	# delete records which are not within the import
	deleteObsolete = 0
	deleteObsolete.addWhere = sys_language_uid = 0 AND type = 0
	
	# does the first row contain the labels or data
	firstRowAreKeys = 1
	
	# pid where to place the records
	# if not set the pid selected in the backend module will be used!
	defaultPid = 2
	
	# continue with the next row if a row is invalid
	continueAfterInvalidRow = 0
	
	# import source charset
	in_charset = CP1252
	
	# database charset
	out_charset = UTF-8

	# if set {$currentRowNumber} contains the current number of the row starting from 1
	addCurrentRowNumber = 0
}

mapping {

	pid {
		# define dynamic pid here or use instead of defaultPid
	}

	if_preg_match_example {
		stack {
			1 = TEXT
			1.value = {$textfield}
			
			2 = if_preg_match
			2 {
				pattern = /(cabag)/i
				# optional returns specific match
				returnMatchPosition = 0
			}
		}
	}

	preg_match_keys_example {
		required = 1
	
		stack {
			2 = preg_match_keys
			2 {
				searchfor = /tx_cabag(.*)/
				# implode string must be between two #\'s
				implodeString = #, #
			}
		}
	}
	
	tx_templavoila_flex {
		stack {
			1 = maptranslations
			1 {
				flex = {$tx_templavoila_flex}
			}
		}
	}

	userhome_mkdir_example {
		stack {
			# creates directory from value or currentFieldValue and returns path as next currentFieldValue
			1 = mkdir
			1 {
				# creates all folders in the path if not existing
				deep = 1
				
				# path relative to PATH_site
				value = fileadmin/user_upload/users/{$username}
			}
		}
	}
	
	select_example {
		# if set the value has to be != 0 and not empty otherwise the import will be stoped 
		required = 1

		stack {
			# sql statement -> first field of the first row will be taken
			1 = select
			1.sql = SELECT field_x FROM table WHERE field_y = \'{$ImportFieldX}\'
		}
		
	}
	
	cachedtransformselect_example {
		# if set the value has to be != 0 and not empty otherwise the import will be stoped 
		required = 1

		stack {
			# selects the table at the first run and reuses the result for transformation!
			1 = cachedtransformselect
			1.sql = SELECT field_from, field_to FROM table WHERE deleted=0
			# the result will be the field_to which is in the same row as the field_from that matches your {$yourfunnyfield}
			1.transform = {$yourfunnyfield}
			# you can define a cache id so you can use the same cache for several fieldprocs
			1.cacheid = funnyid
		}
		
	}
	
	direct_selection = ImportFileX

	date_example {
		required = 1
		
		stack {
			1 = TEXT
			1.value = {$Startdatum}
			
			# perl regular expression replacement
			2 = preg_replace
			2 {
				from = (\d\d)\.(\d\d)\.(\d\d\d\d)
				to = $3-$2-$1
			}
			
			# strtotime for the current value
			3 = strtotime
			3.default = 0
		}
	}
	
	passwordgen_example {
		# unsets the field if it is empty (workaround for certain TCE defaults
		unsetIfEmpty = 1
		
		stack {
			1 = passwordgen
			1 {
				# how many chars do you want
				length = 8
				
				# if set alphanumeric password is generated, default is numeric
				alphanum = 1
			}
			
			2 = sendmail 
			2 {
				# the recipient adress (can be static)
				recipient = {$email}
				
				# from
				from = admin@cabag.ch
				
				# subject of the mail
				subject = This is a mail of the import system.
				
				# text for the mail
				bodytext (
Hello {$name}

This is your new password: {$currentFieldValue}

Best regards
Your cabag_import
				)
				
				# if this sql select returns a empty result the mail will be sent
				sendIfNoResultSQLSelect = SELECT uid FROM fe_users WHERE keyfield = {$keyfield}
			}
		}
	}
	
	transform_example {
		stack {
			1 = transform
			1 {
				# use value like TEXT fieldproc or do a own stack part for TEXT fieldproc before
				value = {$Kategorie}
				
				transform {
					sourcevalue1 = destvalue1
					sourcevalue2 = destvalue2
				}
				
				# a default value is always needed
				default = defaultvaluexyz
			}
		}
	}
	
	files_example {
		required = 1
		
		stack {
			1 = TEXT
			1.value = {$Bildname}
			
			2 = files
			2 {
				# find the right file in the sourceFolder
				preg_match = /.*{$currentFieldValue}[^\/]*.jpg/
				
				sourceFolder = fileadmin/user_upload/shop_images/
				
				# Search recursive from the sourceFolder
				recursive = 1
				
				# move the founded file to the destination folder
				destinationFolder = uploads/tx_cabagshop/
				
				# rename the founded file in the destination folder
				rename = {$currentFieldValue}-{$fieldProcFilesNumber}.jpg
				
				# Clear the currentFieldValue if no image was found
				clearCurrentFieldValueIfNothingFound = 0
				
				# uses exec with find/grep instead of php functions
				useFindGrep = pattern...

				# custom shell cmd
				useCustomCmd = shell cmd

				# returns imploded filelist
				onlyReturnFoundFiles = 0
				
				# implode char
				onlyReturnFoundFilesImplodeChr = ,
			}
		}
	}
	
	copy_file_example {
		required = 1
		
		stack {
			1 = copyfile
			1 {
				sourcebasepath = http://www.domain.ch/
				
				# if source path is empty nothing will done
				sourcepath = {$pdfpath}
				
				dontCopyIfExists = 1
				
				createFilename = {$filenametouse}.jpg

				# filename will be trimmed
				trimFilename = 1

				# continue if filename is empty
				skipEmptyFilename = 1

				# if set then only this filetypes are allowed
				allowedFiletypes = jpg,jpeg,gif,png

				destinationpath = /fileadmin/user_upload/events/
				
				# return just the filename if you have ie. a TCA file field
				returnJustFilename = 0
				
				# slow but supports curl/proxy
				//useGetURL = 1
				
				# enables splitting
				// split = ,
				
				# overwrithe if exists
				overwrithe = 1
			}
			
			2 = TEXT
			2 {
				value = <LINK $currentFieldValue>{$pdftitle}</LINK>
			}
		}
	}
	
	dam_copy_file_example {
		required = 1
		
		stack {
			1 = copyfile
			1 {
				sourcebasepath = http://www.domain.ch/
				
				# if source path is empty nothing will done
				sourcepath = {$pdfpath}
				
				dontCopyIfExists = 1
				
				createFilename = {$filenametouse}.jpg

				# filename will be trimmed
				trimFilename = 1

				# continue if filename is empty
				skipEmptyFilename = 1

				# if set then only this filetypes are allowed
				allowedFiletypes = jpg,jpeg,gif,png±

				destinationpath = /fileadmin/user_upload/events/
				
				# return just the filename if you have ie. a TCA file field
				returnJustFilename = 0
				
				# slow but supports curl/proxy
				//useGetURL = 1
				
				# enables splitting
				// split = ,
				
				# overwrithe if exists
				overwrithe = 1
			}
			
			# creates a dam realtion and index file
			# attention this has to be done in a secon import as you have the uid of the record in the storage
			2 = dam
			2 {
				# semicolon separated list of files
				singleFile = {$currentFieldValue}
				
				# field name to relate to
				fieldNameToRelateTo = picture
				
				selectUidToRelateTo = SELECT uid FROM tx_xxx WHERE somereferencefield = {$referencefromimportsomefieldXXX} AND deleted=0
				
				# table name to relate to
				tableNameToRelateTo = tx_xxx
				
				# data to put into DAM table
				damPresetData {
					copyright = {$img1_credit}
				}
			}
		}
	}
	
	1n_relation_example {
		required = 1

		stack {
			1 = TEXT
			1.value = {$Ort}
			
			# 1 to n relation
			2 = relation
			2 {
				# table of the related records
				relationtable = tx_xyz
				
				# field to search for and where the value is saved 
				# if the searched one is missing
				relationfield = fieldxyz
				
				# additional condition for searching the relation record
				relationaddwhere = AND sys_language_uid=0
				
				# pid of the related records 
				# (if not set global import pid will be taken)
				relationpid = 208
				
				# add record if not found
				addIfMissing = 1
			}
		}
	}
	
	mm_relation_example {
		required = 1
		
		stack {
			1 = TEXT
			1.value = {$1}

			# m to m relation
			2 = mm
			2 {
				# split value for relation
				split = ,
				
				# possibility to restrict the relation to a position within 
				# the value
				splitUseOnlyPosition = 1
				
				# table to relate to
				table = tx_cabagshop_category
				
				# field to relate to and add value if record is missing
				tablekeyfield = catalogkey
				
				# set to 1 so search in the tablekeyfield with LIKE %value%
				tablekeyfieldlike = 0
				
				# pid for the relation records
				# (if not set global import pid will be taken)
				tablepid = 109
				
				# add record if not found
				addIfMissing = 1
				
				# table for the mm relation records
				mmtable = tx_cabagshop_article_category_mm
				
				# field in the mm table which relates to the related table
				mmtablefield = uid_foreign
			}
		}
	}
	
	save_to_raw_row_example {
		stack {
			
			1 = TEXT
			1.value = $someField
			
			2 = save_to_raw_row
			2.field = random_field_name
			
			3 = preg_replace
			3.from = /{$random_field_name}/
			3.to = {$someOtherField}
		}
	}

	copypage_example {
		stack {
			1 = select
			1.sql = SELECT uid FROM pages WHERE pid = 1212 AND deleted = 0 AND hidden = 0

			2 = copypage
			2 {
				# destinationpid, otherwise its {$currentFieldValue}
				# destinationpid = 1287

				sourcepid = 345
				
				# Sets the number of branches on a page tree to copy.
				copyTree = 0

				# clear page cache afterwards
				clearPageCache = 0
			}
			
			3 = copypage
			3 {
				# destinationpid, otherwise its {$currentFieldValue}
				# destinationpid = 1287

				sourcepid = 51
				
				# Sets the number of branches on a page tree to copy.
				copyTree = 0

				# clear page cache afterwards
				clearPageCache = 0
			}
		}
	}

}
';

if(!function_exists('tx_cabagimport_showavailableOptions')) {
	function tx_cabagimport_showavailableOptions() {
		return '
			<div style="height:500px; width:600px; overflow:scroll;">
				<pre>'.htmlspecialchars($GLOBALS['tx_cabag_import-availableOptions']).'</pre>
			</div>';
	}
}

$TCA["tx_cabagimport_config"] = array (
	"ctrl" => $TCA["tx_cabagimport_config"]["ctrl"],
	"interface" => array (
		"showRecordFieldList" => "hidden,title,configuration"
	),
	"feInterface" => $TCA["tx_cabagimport_config"]["feInterface"],
	"columns" => array (
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		"title" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:cabag_import/locallang_db.xml:tx_cabagimport_config.title",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "required",
			)
		),
		"configuration" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:cabag_import/locallang_db.xml:tx_cabagimport_config.configuration",		
			"config" => Array (
				"type" => "text",
				"cols" => "300",	
				"rows" => "500",
				"default" => $GLOBALS['tx_cabag_import-availableOptions'],
			),
			"defaultExtras" => "fixed-font : enable-tab",
		),
		"availableOptions" => array(
			"exclude" => 0,
			"label" =>  "LLL:EXT:cabag_import/locallang_db.xml:tx_cabagimport_config.availableOptions",		
			"config" => Array (
				"type" => "user",
				"userFunc" => 'tx_cabagimport_showavailableOptions',
				"noTableWrapping" => 0,
			),
		)
	),
	"types" => array (
		"0" => array("showitem" => "hidden;;1;;1-1-1, title;;;;2-2-2, configuration, availableOptions")
	),
	"palettes" => array (
		"1" => array("showitem" => "")
	)
);
?>
