<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class PublicLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        // This tells Laravel: "When I use <x-public-layout>, load 'resources/views/layouts/public.blade.php'"
        return view('layouts.public');
    }
}