<?php

return [
    'mode'                  => 'utf-8',
    'format'                => 'A4',
    'author'                => '',
    'subject'               => '',
    'keywords'              => '',
    'creator'               => 'Laravel Pdf',
    'display_mode'          => 'fullpage',
    'tempDir'               => storage_path('tmp'),
    'pdf_a'                 => false,
    'pdf_a_auto'            => false,
    'icc_profile_path'      => '',
	'font_path' => public_path('fonts'),
    'font_data' => [
        'droid-arabic-kufi' => [
            'R'  => 'Droid-Arabic-Kufi-Regular.ttf',
            'useOTL' => 0xFF,    
            'useKashida' => 75,  
        ],
        'almarai-light' => [
            'R' => 'Almarai-Light.ttf',
            'useOTL' => 0xFF,    
            'useKashida' => 75,  
        ],
    ]
];
