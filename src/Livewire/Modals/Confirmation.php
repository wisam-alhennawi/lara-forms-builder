<?php

namespace WisamAlhennawi\LaraFormsBuilder\Livewire\Modals;

use Livewire\Attributes\Locked;
use Livewire\Component;

class Confirmation extends Component
{
    public bool $showConfirmationModal = false;

    public string $title = '';

    public string $content = '';

    public array $callback = [];

    public bool $isDanger;

    // Rendered into the dialog (icon is used as a component name): only the server may set them.
    #[Locked]
    public ?string $icon = null;

    #[Locked]
    public ?string $iconClass = null;

    #[Locked]
    public ?string $iconWrapperClass = null;

    #[Locked]
    public ?string $confirmButtonClass = null;

    protected $listeners = ['showConfirmationModal'];

    /**
     * Icon params left null fall back to the app's <x-confirmation-modal> @props defaults.
     */
    public function showConfirmationModal(
        $title,
        $content,
        $callback,
        $isDanger = true,
        ?string $icon = null,
        ?string $iconClass = null,
        ?string $iconWrapperClass = null,
        ?string $confirmButtonClass = null,
    ) {
        $this->title = $title;
        $this->content = $content;
        $this->callback = $callback;
        $this->isDanger = $isDanger;
        $this->icon = $icon;
        $this->iconClass = $iconClass;
        $this->iconWrapperClass = $iconWrapperClass;
        $this->confirmButtonClass = $confirmButtonClass;
        $this->showConfirmationModal = true;
    }

    public function hideConfirmationModal()
    {
        $this->showConfirmationModal = false;
        // Only callback is reset; title, content and icon params stay for the closing animation, showConfirmationModal() overwrites them all.
        $this->reset('callback');
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
