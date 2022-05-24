@echo off
set /p filename="Enter file name: "
php ../migration.php %filename%
php ../index.php
pause