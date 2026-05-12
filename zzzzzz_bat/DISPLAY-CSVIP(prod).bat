@echo off
:: ============================================================
::  DISPLAY Antrian - CSVIP
::  Fix: pakai PowerShell tulis Preferences (encoding aman)
:: ============================================================
set SHOW=cs_vip
set BASE_URL=http://192.168.1.236:8081
set CHROME=C:\Program Files\Google\Chrome\Application\chrome.exe
set USERDATA=%LOCALAPPDATA%\ChromeDisplay\%SHOW%
set PREFDIR=%USERDATA%\Default

:: Buat folder jika belum ada
if not exist "%PREFDIR%" mkdir "%PREFDIR%"

:: Tulis Preferences via PowerShell (encoding UTF-8 tanpa BOM, aman)
powershell -NoProfile -Command ^
  "$pref = '{\"profile\":{\"default_content_setting_values\":{\"sound\":1}},\"media\":{\"autoplay_enabled\":true}}'; " ^
  "[System.IO.File]::WriteAllText('%PREFDIR%\Preferences', $pref, [System.Text.Encoding]::UTF8)"

echo [OK] Preferences ditulis.

:: Tutup instance Chrome display ini dulu jika ada
taskkill /f /fi "WINDOWTITLE eq Display*" >nul 2>&1

:: Launch Chrome
start "" "%CHROME%" ^
  --kiosk ^
  --app="%BASE_URL%/anjungan/display?show=%SHOW%" ^
  --user-data-dir="%USERDATA%" ^
  --autoplay-policy=no-user-gesture-required ^
  --disable-features=PreloadMediaEngagementData ^
  --no-first-run ^
  --no-default-browser-check ^
  --disable-infobars ^
  --disable-session-crashed-bubble ^
  --disable-extensions

echo [OK] Chrome display %SHOW% dijalankan.
timeout /t 3 /nobreak >nul