## Установка

1. Разворачиваем hostcms дистрибутив с сайта [hostcms.ru](https://www.hostcms.ru/hostcms/editions/free/) в директории сайта (во избежания конфликтов и переобозначения путей, советую расположить директорию сайта как hostcms.local/public_html)
2. Копируем git-репозиторий в папку `hostcms.local`
3. Разворачиваем базу данных командой `php deploy/restore.php init`
4. Применяем миграцию 00001 командой `php deploy/migration.php 00001.php`
5. Система готова к работе. Пользователь: `demo` Пароль: `demo`

## Описание папки deploy
- [deploy/migration](deploy/migration) - директория миграций базы данных
- [deploy/backups](deploy/backups) - директория бэкапов базы данных
- [deploy/bootstrap.php](deploy/bootstrap.php) - файл конфигурации
- [deploy/migration.php](deploy/migration.php) - применяет миграцию, название файла которого задано в параметре `php deploy/migration.php 00001.php`
- [deploy/restore.php](deploy/restore.php) - разворачивает базу данных из дампа, название (без разширения .sql) которого указано в параметре, например,  `php deploy/restore.php init`
- [deploy/dump.php](deploy/dump.php) - создает дамп базы данных, название (без разширения .sql) указавается в параметре, например,  `php deploy/dump.php 00001`
