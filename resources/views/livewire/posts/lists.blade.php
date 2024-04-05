<?php

use Livewire\Volt\Component;
use App\Models\Posts; 
use Illuminate\Database\Eloquent\Collection; 
use Livewire\Attributes\On; 

new class extends Component {

    public Collection $posts; 
 
    public function mount(): void
    {
        $this->getPosts();
    } 
        #[On('posted')]
        public function getPosts(): void
    {
        $this->posts = Posts::with('user')
            ->latest()
            ->get();
    } 
}; ?>


<div class="py-3">
    @foreach ($posts as $post)
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-lg font-bold">{{ $post->title }} | {{ $post->user->name }}</h1>
                    <h3 class="text-sm mt-2">{{ $post->content }}</h3>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
