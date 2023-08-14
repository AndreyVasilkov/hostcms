<?php

$Site = Core_Entity::factory("Site", 1);

$structures = [
    "О компании",
    "Новости",
    "Вакансии",
    "Медиа",
    "Контакты",
    "Блог",
    "Продукция"
];

foreach ($structures as $name) {
    $Structure = Core_Entity::factory("Structure");
    $Structure->name = $name;
    $Site->add($Structure);
}


$menus = [
    "Основное меню",
    "Нижнее меню"
];

foreach ($menus as $name) {
    $Menu = Core_Entity::factory("Structure_Menu");
    $Menu->name = $name;
    $Site->add($Menu);
}