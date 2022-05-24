@echo off
set filename=develop
set /p filename="Enter file name: "
php ../restore.php %filename%
pause