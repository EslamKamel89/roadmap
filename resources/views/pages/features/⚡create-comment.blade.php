<?php

use App\Models\Feature;
use Livewire\Component;

new class extends Component
{
    public Feature $feature;

    public function mount(Feature $feature)
    {
        $this->feature = $feature;
    }
};
?>
<div class="mx-auto max-w-4xl px-6 py-10">
    <div class="rounded-xl border border-gray-200 bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-gray-900">

        <span
            class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
            Lesson 1
        </span>

        <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
            Create Comment
        </h1>

        <p class="mt-2 text-gray-600 dark:text-gray-400">
            This page is powered by Livewire. In the next lesson we'll replace the placeholder
            content with a Filament Form.
        </p>

        <div class="mt-8 rounded-lg border border-gray-200 p-6 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                {{ $feature->name }}
            </h2>

            <p class="mt-3 leading-7 text-gray-600 dark:text-gray-300">
                {{ $feature->description }}
            </p>
        </div>

    </div>
</div>
