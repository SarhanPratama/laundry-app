#!/bin/bash
awk '
/<!-- Riwayat Pembayaran -->/,/<\/div>\n\n                        <!-- Notes Section -->/ {
    if (/<!-- Notes Section -->/) {
        print "                        <!-- Notes Section -->"
    }
    next
}
/<!-- Modal Tambah Pembayaran -->/,/@endif\n\n@endsection/ {
    if (/@endsection/) {
        print "@endsection"
    }
    next
}
{ print }
' resources/views/transaksi/show.blade.php > resources/views/transaksi/show_clean.blade.php
mv resources/views/transaksi/show_clean.blade.php resources/views/transaksi/show.blade.php
