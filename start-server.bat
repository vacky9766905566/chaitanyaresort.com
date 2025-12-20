@echo off
echo ========================================
echo Starting PHP Development Server
echo ========================================
echo.
echo Your files will be available at:
echo   http://localhost:8000/admin.html
echo   http://localhost:8000/get-visitors.php
echo   http://localhost:8000/test-db-connection.php
echo.
echo Press Ctrl+C to stop the server
echo.
cd /d "%~dp0"
php -S localhost:8000
pause

