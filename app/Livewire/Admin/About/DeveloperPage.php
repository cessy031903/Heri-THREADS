<?php

namespace App\Livewire\Admin\About;

use Livewire\Component;

class DeveloperPage extends Component
{
    /**
     * Static roster for now — placeholder names/contact/photos. Fill in
     * real details before this goes live; consider moving to a small
     * admin-editable table later if the team roster changes often.
     */
    public array $developers = [
        [
            'name'    => 'Your Name Here',
            'role'    => 'Developer',
            'email'   => 'you@example.com',
            'contact' => '+63 9XX XXX XXXX',
            'photo'   => null,
        ],
    ];

    public function render()
    {
        return view('livewire.admin.about.developer-page')
            ->layout('layouts.admin', ['title' => 'Developer']);
    }
}
