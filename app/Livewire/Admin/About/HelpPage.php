<?php

namespace App\Livewire\Admin\About;

use Livewire\Component;

class HelpPage extends Component
{
    public function render()
    {
        return view('livewire.admin.about.help-page')
            ->layout('layouts.admin', ['title' => 'Help']);
    }
}
