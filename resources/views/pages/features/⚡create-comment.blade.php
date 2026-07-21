<?php

use App\Models\Feature;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Concerns\RestrictsFileUploadsToSchemaComponents;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Livewire\Component;

new class extends Component implements HasSchemas
{
    use InteractsWithSchemas;
    use RestrictsFileUploadsToSchemaComponents;

    public ?array $data = [];

    public Feature $feature;

    public function mount(Feature $feature)
    {
        $this->feature = $feature;
        $this->feature->load('comments.user');
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('body')
                    ->label('Comment')
                    ->placeholder('Add your comment')
                    ->helperText('Maximum 1000 chars')
                    ->rows(3)
                    ->maxLength(1000)
                    ->live(debounce: 900)
                    ->required(),
            ])
            ->statePath('data');
    }

    public function commentsInfoList(Schema $schema)
    {
        return $schema->record($this->feature)->components([
            RepeatableEntry::make('comments')
                ->table([
                    TableColumn::make('User name'),
                    TableColumn::make('Created at'),
                    TableColumn::make('Approved'),
                    TableColumn::make('Body'),
                ])
                ->schema([
                    TextEntry::make('user.name')
                        ->weight(FontWeight::Bold),

                    TextEntry::make('created_at')
                        ->since(),

                    IconEntry::make('is_approved')
                        ->boolean(),

                    TextEntry::make('body')
                        ->prose()
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public function create(): void
    {
        $data = $this->form->getState();
        $this->feature->comments()->create([
            'body' => $data['body'],
            'user_id' => auth()->id(),
        ]);
        $this->form->fill();
        $this->feature->load('comments.user');
        Notification::make()
            ->title('Comment is submitted successfully')
            ->success()
            ->send();
    }
};
?>

<div class="mx-auto max-w-5xl space-y-8 px-6 py-10">

    {{-- Feature Information --}}
    <section class="rounded-xl border border-gray-200 bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <span
            class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
            Feature
        </span>

        <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
            {{ $feature->name }}
        </h1>

        <p class="mt-5 leading-8 text-gray-600 dark:text-gray-300">
            {{ $feature->description }}
        </p>
    </section>

    {{-- Comment Form --}}
    <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">

        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                Add a Comment
            </h2>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Share your feedback or suggestions about this feature.
            </p>
        </div>

        <form wire:submit="create" class="space-y-6">

            {{ $this->form }}
            <div class="flex justify-end">
                <button type="submit"
                    class="rounded-lg bg-blue-600 px-5 py-2.5 font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                    Submit Comment
                </button>
            </div>

        </form>

    </section>

    {{-- Comments --}}
    <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">

        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Comments
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Community discussion for this feature.
                </p>
            </div>

            <span
                class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                {{ $this->feature->comments->count() }}
            </span>
        </div>

        {{ $this->commentsInfolist }}

    </section>

</div>