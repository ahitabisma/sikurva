<?php

return [
    'disable' => env('CAPTCHA_DISABLE', false),
    'characters' => ['2', '3', '4', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'j', 'm', 'n', 'p', 'q', 'r', 't', 'u', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'M', 'N', 'P', 'Q', 'R', 'T', 'U', 'X', 'Y', 'Z'],
    // 'default' => [
    //     'length' => 4,
    //     'width' => 120,
    //     'height' => 36,
    //     'quality' => 720,
    //     'math' => false,
    //     'expire' => 60,
    //     'encrypt' => false,
    // ],
    'default' => [
        'length' => 4,
        'width' => 120,
        'height' => 40,
        'quality' => 90,
        'angle' => 0, // Tidak ada rotasi karakter
        'blur' => 0, // Tidak ada efek blur
        'lines' => 1, // Kurangi garis yang mengganggu
        'math' => false,
        'sharpen' => 0,
        'invert' => false,
        'expire' => 60,
        'encrypt' => false,
        'fontColors' => ['#000000'], // Warna hitam yang lebih mudah dilihat
        'bgColor' => '#ffffff', // Background putih
        'contrast' => 0, // Tanpa pengurangan kontras
    ],

    'math' => [
        'length' => 9,
        'width' => 120,
        'height' => 36,
        'quality' => 90,
        'math' => true,
    ],

    'flat' => [
        'length' => 6,
        'width' => 160,
        'height' => 46,
        'quality' => 90,
        'lines' => 6,
        'bgImage' => false,
        'bgColor' => '#ecf2f4',
        'fontColors' => ['#2c3e50', '#c0392b', '#16a085', '#c0392b', '#8e44ad', '#303f9f', '#f57c00', '#795548'],
        'contrast' => -5,
    ],
    'mini' => [
        'length' => 3,
        'width' => 60,
        'height' => 28,
    ],
    'inverse' => [
        'length' => 5,
        'width' => 120,
        'height' => 36,
        'quality' => 90,
        'sensitive' => true,
        'angle' => 12,
        'sharpen' => 10,
        'blur' => 2,
        'invert' => true,
        'contrast' => -5,
    ]
];
