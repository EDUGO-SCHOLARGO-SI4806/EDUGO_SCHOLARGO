<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Folder dokumen legalitas penyelenggara (PBI-01 s/d PBI-03)
    |--------------------------------------------------------------------------
    |
    | Folder ini ada di disk "local" (storage/app/private), jadi TIDAK bisa
    | dibuka langsung lewat URL publik. Dokumen hanya bisa diunduh lewat
    | controller yang mengecek pemiliknya. Saat tes Dusk, nilainya diganti
    | lewat .env.dusk.local supaya file demo tidak ikut terhapus.
    |
    */

    'documents_directory' => env('DOCUMENTS_DIRECTORY', 'organizer-documents'),

];
