<?php

namespace App\Livewire\Admin;

use App\Models\Guest;
use Livewire\Component;

class OnlineGuestComponent extends Component
{
    public function render()
    {
        return view('livewire.admin.online-guest-component', [
            'guests' => Guest::where('last_activity', '>=', now()->subMinutes(10))->count() ?? 0,
        ]);
    }
}
