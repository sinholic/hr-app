<?php

namespace App\View\Components\Content;

use Illuminate\View\Component;

class DataFilter extends Component
{
    /**
     * The lists content.
     *
     * @var array
     */
    public $filters;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($filters)
    {
        $this->filters = $filters;
    }


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.content.data-filter');
    }
}
