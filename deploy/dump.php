<?php

require __DIR__ . '/bootstrap.php';

if ($argv && isset($argv[1])) {
    
    $truncateTables = [
        "sessions",
        "counter_visits",
        "revisions",
        "search_words",
        "counter_pages",
        "counter_sessions",
        "search_logs",
        "counter_useragents",
        "search_pages",
    ];
    
    foreach ($truncateTables as $tableName) {
        if ($aTables = Core_DataBase::instance()->getTables($tableName)) {
            Sql_Controller::instance()->execute("TRUNCATE `$tableName`");
        }
    }
    
    $fileName = basename($argv[1]) . ".sql";
    $filePath = DUMP_DIR_PATH . DIRECTORY_SEPARATOR . $fileName;

    $oCore_Out_File = new Core_Out_File();
    $oCore_Out_File->filePath($filePath);

    $oCore_Out_File
        ->open()
        ->write(
              'SET NAMES utf8;' . "\r\n"
            . 'SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";' . "\r\n"
            . 'SET SQL_NOTES=0;'
        );

    $aTables = Core_DataBase::instance()->getTables();
    foreach ($aTables as $sTablesName)
    {
        Core_DataBase::instance()->dump($sTablesName, $oCore_Out_File);
    }

    $oCore_Out_File->close();
    
}



