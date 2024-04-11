<?php

use App\Models\Posts;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public Posts $post;

    #[Validate('required|string')]
    public string $content = '';

    #[Validate('required|string|max:255')]
    public string $title = '';

    public $image_new;
    public $image;

    public function mount(): void
    {
        $this->content = $this->post->content;
        $this->title = $this->post->title;
        $this->image = $this->post->image;
    }

    public function update(): void
    {
        $validated = $this->validate();
        if (isset($this->image_new)) {
            $this->validateImage();
        }
        $validated += ['slug' => SlugService::createSlug(Posts::class, 'slug', $this->title)];
        $this->post->update($validated);

        $this->dispatch('updated');
    }
    public function validateImage()
    {
        $this->validate([
            'image_new' => ['required', 'mimetypes:image/*', 'max:512'],
        ]);
    }

    public function cancel(): void
    {
        $this->dispatch('edit-canceled');
    }
}; ?>

<div>
    <form wire:submit="update">
        <div>
            <x-input-label for="title" :value="__('Title')" />
            <x-text-input wire:model="title" id="title" name="title" type="text" class="mt-1 block w-full" required
                autofocus autocomplete="title" />
            <x-input-error class="mt-2" :messages="$errors->get('title')" />
        </div>
        <div>
            <x-input-label for="content" :value="__('Content')" />
            <x-textarea wire:model="content" name="content" id="content" rows="5"
                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"></x-textarea>
            <x-input-error class="mt-2" :messages="$errors->get('content')" />
        </div>

        <div>
            <x-input-label for="image_new" :value="__('Image')" />
            @if ($image_new)
                <img class="my-3 h-80 w-full object-cover" src="{{ $image_new->temporaryUrl() }}" alt="image">
            @else
                <img class="my-3 h-80 w-full object-cover" src="{{ asset('storage/' . $image) }}" alt="image">
            @endif
            <input type="file" wire:model="image_new" name="image_new" accept="image/*"
                class="w-full py-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
            <p class="mt-1
                        text-sm text-gray-500 dark:text-gray-300" id="file_input_help">Only
                images file under 500KB allowed.</p>
            <x-input-error class="mt-2" :messages="$errors->get('image_new')" />
            <div wire:loading wire:target="image_new"
                class="px-3 py-1 text-sm font-medium leading-none text-center text-green-800 bg-green-200 rounded-lg animate-pulse">
                Uploading file...</div>
        </div>
        <x-primary-button class="mt-4">{{ __('Save') }}</x-primary-button>
        <button class="mt-4" wire:click.prevent="cancel">Cancel</button>
    </form>
</div>
