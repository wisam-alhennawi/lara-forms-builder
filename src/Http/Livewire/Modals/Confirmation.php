<?php

namespace WisamAlhennawi\LaraFormsBuilder\Http\Livewire\Modals;

use Livewire\Component;

class Confirmation extends Component
{
    public bool $showConfirmationModal = false;

    public string $title = '';

    public string $content = '';

    public array $callback = [];

    public bool $isDanger;

    protected $listeners = ['showConfirmationModal'];

    public function showConfirmationModal($title, $content, $callback, $isDanger = true)
    {
        $this->title = $title;
        $this->content = $content;
        $this->callback = $callback;
        $this->isDanger = $isDanger;
        $this->showConfirmationModal = true;
    }

    public function hideConfirmationModal()
    {
        $this->showConfirmationModal = false;
        $this->reset(['title', 'content', 'callback']);
    }

    public function confirm()
    {
        $this->dispatch('modalConfirmed', $this->callback);
        $this->hideConfirmationModal();
    }

    public function render()
    {
        return view('lara-forms-builder::livewire.modals.confirmation');
    }
}
