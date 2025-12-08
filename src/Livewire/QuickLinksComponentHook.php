<?php

namespace Niladam\QuickLinks\Livewire;

use Filament\Tables\Contracts\HasTable;
use Livewire\ComponentHook;
use Niladam\QuickLinks\Facades\QuickLinks;

class QuickLinksComponentHook extends ComponentHook
{
    public function skip(): bool
    {
        if (QuickLinks::isDisabled()) {
            return true;
        }

        return ! ($this->component instanceof HasTable);
    }

    public function render(): void
    {
        /** @var HasTable $component */
        $component = $this->component;

        $table = $component->getTable();

        QuickLinks::applyToTable($table);
    }
}
