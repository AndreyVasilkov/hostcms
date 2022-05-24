<?php

require __DIR__ . '/bootstrap.php';

$return = FALSE;
define("IS_ADMIN_PART", 1);

function executeSql(&$aSql, &$aReplace) {
    
    foreach ($aSql as $data) {
        $sql = is_array($data) ? $data["sql"] : $data;        
        foreach ($aReplace as $search => $replace) {
            if (preg_match("/\[{$search}\]/", $sql)) {
                $sql = str_replace("[" . $search . "]", $replace, $sql);
            }

            if (preg_match("/\{{$search}\}/", $sql)) {
                $aReplace[$search] = $replace + 1;
                $sql = str_replace("{" . $search . "}", $aReplace[$search], $sql);

            }
        }

        if (is_array($data)) {
            $oDatabase = Core_QueryBuilder::insert()->execute($sql);
            $aReplace[$data["lastInsert"]] = $oDatabase->getInsertId();
        } else {
            Core_DataBase::instance()->query($sql);
        }
    }
    
    $aSql = [];
}

function addAdminWord($word) {

    $oCore_QueryBuilder_Insert = Core_QueryBuilder::insert('admin_words')
        ->columns('user_id')
        ->values(19)
        ->execute();
    $admin_word_id = $oCore_QueryBuilder_Insert->getInsertId();

    $oCore_QueryBuilder_Insert = Core_QueryBuilder::insert('admin_word_values')
        ->columns('admin_word_id', 'admin_language_id', 'name', 'description', 'user_id')
        ->values($admin_word_id, 1, $word, '', 19)
        ->execute();

    return $admin_word_id;
}

try {
    if ($argv && isset($argv[1])) {
        $fileName = basename($argv[1]);

        isset($argv[2]) && define("CURRENT_SITE", $argv[2]);
        
        $result = Core_DataBase::instance()->query("SELECT id FROM migrations WHERE name = '$fileName'")->asAssoc()->result();
        if (!$result) {
            $path =  MIGRATION_DIR_PATH . DIRECTORY_SEPARATOR . $fileName;
            switch (pathinfo($argv[1], PATHINFO_EXTENSION)) {
                case "php" : 
                    if (is_file($path)) {
                        ob_start();
                        require_once $path;
                        $out = ob_get_clean();

                        if ($out == '') $return = TRUE;
                        else {
                            throw new Exception($out);
                        }
                    } else {
                        throw new Exception("Migration file don't exists!");
                    }
                    break;
                case "sql" :
                    if (is_file($path)) {
                        Core_DataBase::instance()->query(file_get_contents($path));
                        $return = TRUE;
                    } else {
                        throw new Exception("Migration file don't exists!");
                    }
                    break;
            }
        } else {
            throw new Exception("This migration already exists!");
        }

    } else {
        throw new Exception("Please enter migration file name!");
    }
    
    $return && $oDatabase = Core_QueryBuilder::insert()->execute("INSERT INTO `migrations` (`name`) VALUES ('$fileName')");
    
    
} catch (Exception $e) {
    echo $e->getMessage() . "\r\n";
    
}

if ($return) {
    echo $fileName .": success" . "\r\n";
} else {
    echo $fileName . ": failed" . "\r\n";
}

if (defined("MIGRATION_MESSAGE")) {
    echo MIGRATION_MESSAGE . "\r\n";
}

