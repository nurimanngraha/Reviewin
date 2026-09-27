@echo off
title Reviewin - Cloudflare Tunnel (Apache Port 80)
color 0a
cls
echo ============================================================
echo      REVIEWIN - CLOUDFLARE TUNNEL (LARAGON APACHE PORT 80)
echo ============================================================
echo.
echo  [1/2] Pastikan Laragon sudah berjalan (Klik "Start All").
echo  [2/2] Membuka Tunnel HTTPS publik ke port 80...
echo.
echo ------------------------------------------------------------
echo  Tunggu beberapa detik, salin URL berakhiran .trycloudflare.com
echo  yang muncul di bawah ini ke HP.
echo ------------------------------------------------------------
echo.

"C:\laragon\bin\cloudflared\cloudflared.exe" tunnel --url http://127.0.0.1:80
pause
