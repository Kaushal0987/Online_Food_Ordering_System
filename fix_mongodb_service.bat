@echo off
REM ============================================
REM MongoDB Service Fix Script
REM Run this as Administrator
REM ============================================

echo.
echo ============================================
echo MongoDB Service Repair Script
echo ============================================
echo.

REM Check for administrator privileges
net session >nul 2>&1
if %errorLevel% NEQ 0 (
    echo ERROR: This script must be run as Administrator!
    echo Right-click this file and select "Run as administrator"
    echo.
    pause
    exit /b 1
)

echo [Step 1/6] Stopping MongoDB processes...
taskkill /F /IM mongod.exe >nul 2>&1
timeout /t 2 /nobreak >nul
echo Done.

echo.
echo [Step 2/6] Clearing corrupted data directory...
if exist "C:\Program Files\MongoDB\Server\8.0\data" (
    del /F /S /Q "C:\Program Files\MongoDB\Server\8.0\data\*" >nul 2>&1
    echo Done.
) else (
    echo Data directory not found - skipping.
)

echo.
echo [Step 3/6] Clearing log file...
if exist "C:\Program Files\MongoDB\Server\8.0\log\mongod.log" (
    del /F "C:\Program Files\MongoDB\Server\8.0\log\mongod.log" >nul 2>&1
    echo Done.
) else (
    echo Log file not found - skipping.
)

echo.
echo [Step 4/6] Removing old service...
sc delete MongoDB >nul 2>&1
timeout /t 2 /nobreak >nul
echo Done.

echo.
echo [Step 5/6] Installing MongoDB service...
"C:\Program Files\MongoDB\Server\8.0\bin\mongod.exe" --config "C:\Program Files\MongoDB\Server\8.0\bin\mongod.cfg" --install
if %errorLevel% NEQ 0 (
    echo ERROR: Failed to install MongoDB service!
    echo.
    echo Trying alternative method with LocalSystem account...
    "C:\Program Files\MongoDB\Server\8.0\bin\mongod.exe" --dbpath "C:\Program Files\MongoDB\Server\8.0\data" --logpath "C:\Program Files\MongoDB\Server\8.0\log\mongod.log" --install --serviceName MongoDB --serviceDisplayName "MongoDB Server (MongoDB)"
)
timeout /t 2 /nobreak >nul
echo Done.

echo.
echo [Step 6/6] Setting permissions...
icacls "C:\Program Files\MongoDB\Server\8.0\data" /grant "NT AUTHORITY\NetworkService:(OI)(CI)F" /T >nul 2>&1
icacls "C:\Program Files\MongoDB\Server\8.0\log" /grant "NT AUTHORITY\NetworkService:(OI)(CI)F" /T >nul 2>&1
echo Done.

echo.
echo [OPTIONAL] Changing service to run as LocalSystem (more reliable)...
sc config MongoDB obj= "LocalSystem" >nul 2>&1
echo Done.

echo.
echo ============================================
echo Starting MongoDB service...
echo ============================================
net start MongoDB

if %errorLevel% EQU 0 (
    echo.
    echo ============================================
    echo SUCCESS! MongoDB service is now running.
    echo ============================================
    echo.
    echo Checking service status...
    sc query MongoDB | findstr "STATE"
    echo.
    echo You can now connect to MongoDB at:
    echo mongodb://localhost:27017
    echo.
) else (
    echo.
    echo ============================================
    echo ERROR: MongoDB service failed to start!
    echo ============================================
    echo.
    echo Please check the log file at:
    echo C:\Program Files\MongoDB\Server\8.0\log\mongod.log
    echo.
    echo Common issues:
    echo 1. MongoDB is already running manually (check Task Manager)
    echo 2. Port 27017 is in use by another application
    echo 3. Data directory permissions issue
    echo.
    echo Try manually starting MongoDB with:
    echo "C:\Program Files\MongoDB\Server\8.0\bin\mongod.exe"
    echo.
)

echo.
pause
