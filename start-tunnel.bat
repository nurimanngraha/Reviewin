@echo off
title Reviewin - Cloudflare Tunnel
color 0b
cls
echo ============================================================
echo         REVIEWIN - CLOUDFLARE TUNNEL (NFC ^& QR TESTING)
echo ============================================================
echo.
echo  [1/2] Pastikan Laragon sudah berjalan (Klik "Start All").
echo  [2/2] Membuka Tunnel HTTPS publik ke port 8000...
echo.
echo ------------------------------------------------------------
echo  Tunggu beberapa detik, salin URL berakhiran .trycloudflare.com
echo  yang muncul di bawah ini ke HP atau ke .env (APP_URL).
echo ------------------------------------------------------------
echo.

"C:\laragon\bin\cloudflared\cloudflared.exe" tunnel --url http://127.0.0.1:8000
pause
