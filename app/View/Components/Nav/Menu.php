<?php

namespace App\View\Components\Nav;

use Illuminate\View\Component;

class Menu extends Component
{
    /**
     * The lists menus.
     *
     * @var string
     */
    public $menus;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($menus)
    {
        $this->menus = $menus;
    }


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.nav.menu');
    }
}
