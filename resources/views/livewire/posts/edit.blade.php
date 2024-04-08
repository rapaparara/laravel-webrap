<?php

use App\Models\Posts; 
use Livewire\Attributes\Validate; 
use Livewire\Volt\Component;
use \Cviebrock\EloquentSluggable\Services\SlugService;

new class extends Component {
    
    public Posts $post; 

    #[Validate('required|string')]
    public string $content = '';
    
    #[Validate('required|string|max:255')]
    public string $title = '';
    
 
    public function mount(): void
    {
        $this->content = $this->post->content;
        $this->title = $this->post->title;
    }
 
    public function update(): void
    { 
        $validated = $this->validate();
        $validated += ['slug' => SlugService::createSlug(Posts::class, 'slug', $this->title)];
        $this->post->update($validated);
 
        $this->dispatch('updated');
    }
 
    public function cancel(): void
    {
        $this->dispatch('edit-canceled');
    }  
}; ?>

<div>
    <form wire:submit="update" > 
            <div>
                <x-input-label for="title" :value="__('Title')" />
                <x-text-input wire:model="title" id="title" name="title" type="text"
                    class="mt-1 block w-full" required autofocus autocomplete="title" />
                <x-input-error class="mt-2" :messages="$errors->get('title')" />
            </div>
            <div>
                <x-input-label for="content" :value="__('Content')" />
                <x-textarea wire:model="content" name="content" id="content" rows="5" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"></x-textarea>
                <x-input-error class="mt-2" :messages="$errors->get('content')" />
            </div>
        <x-primary-button class="mt-4">{{ __('Save') }}</x-primary-button>
        <button class="mt-4" wire:click.prevent="cancel">Cancel</button>
    </form> 
</div>
