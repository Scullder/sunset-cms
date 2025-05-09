<?php

namespace App\CMS;

use App\CMS\BaseComponent;
class Entity extends BaseComponent
{
    public function render(): string
    {
        $output = '<div class="entity" style="border:1px solid #ccc;padding:1rem;margin:1rem 0">';
        $output .= '<h3>' . $this->getAttribute('admin', 'label') . '</h3>';
        
        foreach ($this->getChildren() as $child) {
            $output .= $child->render();
        }
        
        return $output . '</div>';
    }
}