<?php

namespace App\Services;

class ColorService
{
    private $backgroud_colors = [
        '#bf7062',
        '#989898',
        '#ff8028',
        '#9279cd',
        '#ec988d',
        '#4f9850',
        '#ff6117',
        '#c92c1b',
        '#adca65',
        '#03a9f4',
        '#fb3b3b'
    ];

    private $font_colors = [
        'white',
        'white',
        'white',
        'white',
        'white',
        'white',
        'white',
        'white',
        'white',
        'white',
        'white'
    ];

    public function getScheduleColor($int) {

        return [
            'background_color' => $this->backgroud_colors[$int],
            'font_color' => $this->font_colors[$int]
        ];
    }
}