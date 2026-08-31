@echo off

cd "C:\Users\User\Desktop\ces-app"

:: Start the Laravel server in a minimized CMD window
start /min cmd /k "php artisan serve --host=192.168.1.7 --port=8000"

:: Wait for a few seconds to ensure the Laravel server is running before opening the browser
timeout /t 1 /nobreak

:: Open the default web browser to the local server URL
start http://192.168.1.7:8000
