@echo off
set filename=develop
set /p filename="Enter file name: "
php ../dump.php %filename%
pause