<?php

namespace App\CMS\Fields;

use App\CMS\Field;

class StringField extends Field
{
    public function render(): string
    {
        return <<<HTML
        <div class="field string-field">
            <label>{$this->getAttribute('label', 'String Field')}</label>
            <input type="text" name="">
        </div>
        HTML;
    }
}
