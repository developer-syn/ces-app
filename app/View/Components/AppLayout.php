<?php

namespace App\View\Components;

use App\Models\SchoolInfo;
use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public $schoolInfo;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        // Fetch the first schoolInfo record (adjust this logic as needed)
        $this->schoolInfo = SchoolInfo::first();
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.app', [
            'schoolInfo' => $this->schoolInfo, // Pass the schoolInfo to the layout
        ]);
    }
}
