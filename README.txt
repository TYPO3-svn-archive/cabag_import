This is the cab services ag import extension.

- Check out manual.sxw for full documentation!
- Check out tca.php or create new configuration record to find all configuration options!!!

Add fieldprocs in your extensions by adding the class as hook in your ext_localconf.php

$TYPO3_CONF_VARS['SC_OPTIONS']['ext/cabag_import/lib/class.tx_cabagimport_handler.php']['fieldproc'][FIELDPROCKEY] = 'PATHTOCLASS:CLASSNAME';
