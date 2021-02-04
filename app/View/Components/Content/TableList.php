<?php

namespace App\View\Components\Content;

use Illuminate\View\Component;

class TableList extends Component
{
    /**
     * The lists view_option.
     *
     * @var array
     */
    public $options;
    
    /**
     * The lists content.
     *
     * @var array
     */
    public $contents;

    /**
     * The lists data.
     *
     * @var array
     */
    public $datas;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($options, $contents, $datas)
    {
        $this->options = $options;
        $this->contents = $contents;
        $this->datas = $datas;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.content.table-list');
    }
}
