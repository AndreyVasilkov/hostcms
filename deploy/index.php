<?php
require __DIR__ . '/bootstrap.php';

if (class_exists("Compression_Controller")) {
    $oTemplates = Core_Entity::factory("Template");
    $oTemplates->queryBuilder()->where("template_id", "=", 0);

    foreach($oTemplates->findAll(FALSE) as $oTemplate) {
        $oTemplate->updateTimestamp();
    }

    Compression_Controller::instance('css')->deleteAllCss();
    Compression_Controller::instance('js')->deleteAllJs();
}
?>