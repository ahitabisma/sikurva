<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ModalDelete extends Component
{
    public ?string $textBtn;
    public string $title;
    public string $message;
    public string $confirmText;
    public string $cancelText;
    public ?string $url;
    public bool $isDelete;

    /**
     * Create a new component instance.
     */
    public function __construct(
        ?string $textBtn = null,
        string $title = 'Konfirmasi',
        string $message = 'Apakah Anda yakin ingin melanjutkan?',
        string $confirmText = 'Hapus',
        string $cancelText = 'Batal',
        ?string $url = null,
        bool $isDelete = true
    ) {
        $this->textBtn = $textBtn;
        $this->title = $title;
        $this->message = $message;
        $this->confirmText = $confirmText;
        $this->cancelText = $cancelText;
        $this->url = $url;
        $this->isDelete = $isDelete;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.modal-delete');
    }
}
