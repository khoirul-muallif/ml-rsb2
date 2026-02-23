@echo off
:: ============================================================
::  APM - Apotek & Farmasi
::  EPSON TM-T82X | Silent Print
:: ============================================================
set URL=http://127.0.0.1:8081/anjungan/layanan/apotek
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
