<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Notifications extends Component
{
    public $notifications = [];

    protected $listeners = ['notify'];

    public function notify($type, $message)
    {
        $this->notifications[] = ['type' => $type, 'message' => $message];
    }

    public function render()
    {
        return view('livewire.notifications');
    }
}
