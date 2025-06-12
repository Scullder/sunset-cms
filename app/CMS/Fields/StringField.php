<?php

namespace App\CMS\Fields;

use App\CMS\Field;

class StringField extends Field
{
    public function render(): string
    {
        return <<<HTML
        <div class="field string-field">
            <label>{$this->getAdminAttribute('label')}</label>
            <input type="text" name="">
        </div>
        HTML;
    }

    protected function isValidType(mixed $value): bool
    {
        return is_string($value);
    }
}
