<?php

namespace App\View\Components\Content;

use Illuminate\View\Component;

class FormAdd extends Component
{

    /**
     * The lists content.
     *
     * @var array
     */
    public $contents;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($contents)
    {
        $this->contents = $contents;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.content.form-add');
    }
}
