#!/bin/bash
awk '
/<!-- Tambahan Riwayat Pembayaran -->/,/<!-- Batas Akhir Riwayat -->/ {
    next
}
{ print }
' resources/views/cek-status.blade.php > resources/views/cek-status_clean.blade.php
mv resources/views/cek-status_clean.blade.php resources/views/cek-status.blade.php
