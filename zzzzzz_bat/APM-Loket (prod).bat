@echo off
:: ============================================================
::  APM - Loket
::  EPSON TM-T82X | Silent Print
:: ============================================================
set URL=http://192.168.1.236:8081/anjungan/layanan/loket
set CHROME=C:\Program Files\Google\Chrome\Application\chrome.exe
set PROFILE=Default

taskkill /f /im chrome.exe >nul 2>&1
timeout /t 2 /nobreak >nul

start "" "%CHROME%" ^
  --app=%URL% ^
  --kiosk-printing ^
  --profile-directory="%PROFILE%" ^
  --no-first-run ^
  --no-default-browser-check ^
  --disable-infobars ^
  --disable-session-crashed-bubble ^
  --disable-translate ^
  --disable-extensions ^
  --disable-popup-blocking
