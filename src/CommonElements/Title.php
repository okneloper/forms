<?php

namespace Okneloper\Forms\CommonElements;

use Okneloper\Forms\Elements\Select;

class Title extends Select
{
    public function __construct($name, $attributes = [], $label = null, $emptyOption = '')
    {
        $options = [];
        if (isset($emptyOption)) {
            $options[''] = $emptyOption;
        }
        foreach (['Miss', 'Mrs', 'Ms', 'Mr', 'Dr'] as $title) {
            $options[$title] = $title;
        }
        $attributes['options'] = $options;
        parent::__construct($name, $label, $attributes);
    }
}
