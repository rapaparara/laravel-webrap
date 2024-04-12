<?php

use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    #[Validate('required|string|max:255')]
    public $title;

    #[Validate('required')]
    public $content;

    #[Validate('required|mimetypes:image/*|max:512')]
    public $image;

    public function store(): void
    {
        $validated = $this->validate();
        $validated['image'] = $this->image->store('images', 'public');
        auth()->user()->posts()->create($validated);
        $this->dispatch('posted');
        $this->reset();
    }
}; ?>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Add Post') }}
                </h2>
                <form wire:submit="store" class="mt-6 space-y-6">
                    <div>
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input wire:model="title" id="title" name="title" type="text"
                            class="mt-1 block w-full" required autofocus autocomplete="title"
                            placeholder="Your title here..." />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>
                    <div>
                        <x-input-label for="content" :value="__('Content')" />
                        <x-textarea wire:model="content" name="content" id="content" rows="5"
                            class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                            placeholder="Your content here..."></x-textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('content')" />
                    </div>
                    <div>
                        <x-input-label for="image" :value="__('Image')" />
                        @if($image)
                        <img class="my-3 h-80 w-full object-cover" src="{{ $image->temporaryUrl() }}" alt="image">
                        @endif
                        <input type="file" wire:model="image" name="image" accept="image/*"
                            class="w-full py-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <p class="mt-1
                                text-sm text-gray-500 dark:text-gray-300"
                            id="file_input_help">Only images file under 500KB allowed.</p>
                        <x-input-error class="mt-2" :messages="$errors->get('image')" />
                        <div wire:loading wire:target="image"
                            class="px-3 py-1 text-sm font-medium leading-none text-center text-green-800 bg-green-200 rounded-lg animate-pulse">
                            Uploading file...</div>
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
