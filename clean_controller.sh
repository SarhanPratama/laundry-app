#!/bin/bash
awk '
/function tambahPembayaran/,/catch \(\\Exception \$e\)/ {
    if (/catch \(\\Exception \$e\)/) {
        skip=1
    }
    next
}
{
    if (skip) {
        if (/}/) { skip=0; next }
        next
    }
    print
}
' app/Http/Controllers/TransaksiController.php > app/Http/Controllers/TransaksiController_clean.php
mv app/Http/Controllers/TransaksiController_clean.php app/Http/Controllers/TransaksiController.php
