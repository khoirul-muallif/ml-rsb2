@echo off
REM Launch Multiple Display Windows
REM Setiap display akan buka di window terpisah

set CHROME="C:\Program Files\Google\Chrome\Application\chrome.exe"

REM Kill existing Chrome processes (optional)
REM taskkill /F /IM chrome.exe 2>nul
REM timeout /t 2 /nobreak >nul

echo Starting Multiple Displays...

REM Display 1: Loket Regular
start "" %CHROME% --kiosk --autoplay-policy=no-user-gesture-required --disable-features=PreloadMediaEngagementData --user-data-dir=%TEMP%\chrome-display-1 "http://localhost:8001/anjungan/display?show=loket"

REM Delay antar launch (opsional)
timeout /t 1 /nobreak >nul

REM Display 2: Loket VIP
start "" %CHROME% --kiosk --autoplay-policy=no-user-gesture-required --disable-features=PreloadMediaEngagementData --user-data-dir=%TEMP%\chrome-display-2 "http://localhost:8001/anjungan/display?show=loket_vip"

timeout /t 1 /nobreak >nul

REM Display 3: CS Regular
start "" %CHROME% --kiosk --autoplay-policy=no-user-gesture-required --disable-features=PreloadMediaEngagementData --user-data-dir=%TEMP%\chrome-display-3 "http://localhost:8001/anjungan/display?show=cs"

timeout /t 1 /nobreak >nul

REM Display 4: CS VIP
start "" %CHROME% --kiosk --autoplay-policy=no-user-gesture-required --disable-features=PreloadMediaEngagementData --user-data-dir=%TEMP%\chrome-display-4 "http://localhost:8001/anjungan/display?show=cs_vip"

echo.
echo All displays launched!
echo Press any key to close all displays...
pause >nul

REM Cleanup: Kill all Chrome processes
taskkill /F /IM chrome.exe 2>nul