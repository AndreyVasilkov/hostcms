<?php

require __DIR__ . '/bootstrap.php';

if ($argv && isset($argv[1])) {
    $fileName = basename($argv[1]) . ".sql";
    $filePath = DUMP_DIR_PATH . DIRECTORY_SEPARATOR . $fileName;

    if (is_file($filePath)) {
    
        $Config = Core_DataBase::instance()->getConfig();
        $aTables = Core_DataBase::instance()->getTables();
        foreach ($aTables as $sTablesName) {
            Core_DataBase::instance()->query("DROP TABLE `{$sTablesName}`");
        }

        Sql_Controller::instance()->executeByFile($filePath);
        
        $deploySql = "
            UPDATE structures SET https = 0;
            UPDATE constants SET active = 0 WHERE name = \"USE_ONLY_HTTPS_AUTHORIZATION\";

            UPDATE site_aliases SET name = REGEXP_REPLACE(name, '\.[a-z]+$', '.local');


            UPDATE modules SET active = 0 WHERE path = 'update';
            UPDATE modules SET active = 0 WHERE path = 'cache';
            UPDATE modules SET active = 0 WHERE path = 'market';

            INSERT INTO constants (`constant_dir_id`, `name`, `value`, `description`, `active`, `user_id`, `deleted`) VALUES (0,'SQL_LOG','true','',1,19,0) 
            ON DUPLICATE KEY UPDATE value = 'true';
        ";
        
        $User = Core_Entity::factory("User")->getFirst();
        if ($User) {
            $User->login = "demo";
            $User->password = Core_Hash::instance()->hash("demo");
            $User->save();
        }
        
        Sql_Controller::instance()->execute($deploySql);
        
        
        
    } else {
        die('File not found');
    }
}



