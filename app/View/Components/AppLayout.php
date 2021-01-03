<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AppLayout extends Component
{
    public $html_attributes = [
        'title' => 'Willkommen zu deinem Serienerlebnis',
        'description' => 'Alle Infos zu deinen Filmen, Serien, Folgen und Schauspielern. Markiere deine Serien und Filme als gesehen und behalte so den Überblick!'
    ];

    public function __construct($htmlAttributes = null)
    {
        if (isset($htmlAttributes)) {
            $this->html_attributes = $htmlAttributes;
        }
    }

    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('layouts.app');
    }
}
