<?php

return [
    'margin_left'   => 10,
    'margin_right'  => 10,
    'margin_top'    => 10,
    'margin_bottom' => 10,

    'pdf_renderer'  => 'DomPDF',

    'font_dir' => storage_path('fonts/'),

    'font_cache' => storage_path('fonts/'),

    'chroot' => public_path(),

    'logOutputFile' => storage_path('logs/dompdf.log'),

    'tempDir' => sys_get_temp_dir(),

    'defaultFont' => 'DejaVu Sans',

    'dompdf' => [
        'logOutputFile' => storage_path('logs/dompdf.log'),
        'defaultMediaType' => 'screen',
        'defaultPaperSize' => 'a4',
        'defaultFont' => 'DejaVu Sans',
        'dpi' => 96,
        'fontDir' => storage_path('fonts/'),
        'fontCache' => storage_path('fonts/'),
        'tempDir' => sys_get_temp_dir(),
        'chroot' => public_path(),
        'fonts' => [
            'DejaVu Sans' => [
                'normal' => storage_path('fonts/DejaVuSans.ttf'),
                'bold' => storage_path('fonts/DejaVuSans-Bold.ttf'),
                'italic' => storage_path('fonts/DejaVuSans-Oblique.ttf'),
                'bold_italic' => storage_path('fonts/DejaVuSans-BoldOblique.ttf'),
            ],
            'amiri' => [
                'normal' => storage_path('fonts/Amiri-Regular.ttf'),
                'bold' => storage_path('fonts/Amiri-Regular.ttf'),
                'italic' => storage_path('fonts/Amiri-Regular.ttf'),
                'bold_italic' => storage_path('fonts/Amiri-Regular.ttf'),
            ],
        ],
        'allowedProtocols' => [
            'file://' => ['rules' => []],
            'http://' => ['rules' => []],
            'https://' => ['rules' => []],
        ],
        'allowedRemoteHosts' => [
            'cdnjs.cloudflare.com',
            'cdn.jsdelivr.net',
            'fonts.googleapis.com',
            'fonts.gstatic.com',
            'res.cloudinary.com',
        ],
        'allowFrames' => false,
        'isRemoteEnabled' => true,
        'isFontSubsettingEnabled' => false,
        'debugPng' => false,
        'debugKeepTemp' => false,
        'debugCss' => false,
        'debugLayout' => false,
        'debugLayoutLines' => true,
        'debugLayoutBlocks' => true,
        'debugLayoutInline' => true,
        'debugLayoutPaddingBox' => true,
        'pdfBackend' => 'PDFLib',
        'pdflibLicense' => '',
        'rtl' => true,  // Enable RTL support
        // 'adminPassword' => 'password',
        // 'userPassword'  => 'password',
    ],
];
