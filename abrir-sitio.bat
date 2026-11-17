@echo off
setlocal
title Marea Celestial - abrir sitio

rem Uso:
rem   abrir-sitio.bat            -> sitio real (PHP + MySQL, servido por Apache)
rem   abrir-sitio.bat estatico   -> version HTML estatica exportada

set "URL=http://localhost/Marea_celestial/"
if /i "%~1"=="estatico" set "URL=http://localhost/Marea_celestial/html/"

where curl >nul 2>nul
if errorlevel 1 (
    echo curl no esta disponible. Abriendo %URL% directamente...
    start "" "%URL%"
    exit /b 0
)

set "CODE="
for /f %%c in ('curl -s -o NUL -w "%%{http_code}" --max-time 5 "%URL%"') do set "CODE=%%c"

if "%CODE%"=="200" (
    echo Sitio en linea. Abriendo %URL%
    start "" "%URL%"
    exit /b 0
)

echo.
echo No se pudo conectar con %URL%
echo Codigo de respuesta: %CODE%
echo.
echo Abri el panel de XAMPP, arranca Apache y MySQL y volve a ejecutar este archivo.
echo.
pause
exit /b 1
