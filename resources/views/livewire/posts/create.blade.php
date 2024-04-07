<?php

use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new class extends Component {
    #[Validate('required|string|max:255')]
    public $title;
    #[Validate('required')]
    public $content;

    public function store(): void
    {
        $validated = $this->validate();
        $validated += ['slug' => 'coba-saja'];
        auth()->user()->posts()->create($validated);

        $this->dispatch('posted');
        $this->reset();
    }
}; ?>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <form wire:submit="store" class="mt-6 space-y-6">
                    <div>
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input wire:model="title" id="title" name="title" type="text"
                            class="mt-1 block w-full" required autofocus autocomplete="title" />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>
                    <div>
                        <x-input-label for="content" :value="__('Content')" />
                        <x-textarea wire:model="content" name="content" id="content" rows="5"
                            class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"></x-textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('content')" />
                    </div>
                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Post') }}</x-primary-button>
                        <x-action-message class="me-3" on="posted">
                            {{ __('Posted.') }}
                        </x-action-message>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
