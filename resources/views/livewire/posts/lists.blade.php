<?php

use Livewire\Volt\Component;
use App\Models\Posts;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;

new class extends Component {
    public $posts;
    public ?Posts $editing = null;

    public function mount(): void
    {
        $this->getPosts();
    }
    #[On('posted')]
    public function getPosts(): void
    {
        $this->posts = Posts::with('user')
            ->where('user_id', Auth::user()->id)
            ->latest()
            ->get();
    }
    public function edit(Posts $post): void
    {
        $this->editing = $post;
        $this->getPosts();
    }
    public function delete(Posts $post): void
    {
        $post->delete();
        $this->getPosts();
    }

    #[On('edit-canceled')]
    #[On('updated')]
    public function disableEditing(): void
    {
        $this->editing = null;

        $this->getPosts();
    }
}; ?>


<div class="py-3">
    @foreach ($posts as $post)
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800  overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 pt-2 flex justify-between text-gray-900 dark:text-gray-100">
                        <div>
                            <small>{{ $post->created_at->format('j M Y, g:i a') }}</small>
                            @unless ($post->created_at->eq($post->updated_at))
                                <small> &middot; {{ __('edited') }}</small>
                            @endunless
                        </div>
                        <x-dropdown>
                            <x-slot name="trigger">
                                <button>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path
                                            d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                    </svg>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link href="{{ $post->slug }}">
                                    {{ __('Preview') }}
                                </x-dropdown-link>
                                <x-dropdown-link wire:click="edit({{ $post->id }})">
                                    {{ __('Edit') }}
                                </x-dropdown-link>
                                <x-dropdown-link wire:click="delete({{ $post->id }})">
                                    {{ __('Delete') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                    <div class="px-6 py-12 text-gray-900 dark:text-gray-100">
                        <div class="w-full">
                            @if ($post->is($editing))
                                <div class="text-sm">
                                    <livewire:posts.edit :post="$post" :key="$post->id" />
                                </div>
                            @else
                                <h1 class="mb-3 text-xl font-bold">{{ $post->title }}</h1>
                                <img class="mb-3 h-80 w-full object-cover" src="{{ asset('storage/'.$post->image) }}" alt="{{ $post->title }}">
                                <h3 class="text-sm">{{ $post->content }}</h3>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
