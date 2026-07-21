<?php

use App\Enums\Feature\FeatureStatus;
use App\Enums\Feature\FeatureType;
use App\Models\Feature;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Concerns\RestrictsFileUploadsToSchemaComponents;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Livewire\Component;

new class extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;
    use RestrictsFileUploadsToSchemaComponents;

    public function table(Table $table)
    {
        return $table->query(Feature::query())->columns([
            TextColumn::make('name')
                ->searchable(),
            TextColumn::make('status')
                ->badge()
                ->searchable(),
            TextColumn::make('type')
                ->searchable(),
            TextColumn::make('effort_in_days')
                ->numeric()
                ->sortable(),
            TextColumn::make('priority')
                ->numeric()
                ->sortable(),
            TextColumn::make('cost')
                ->money()
                ->sortable(),
            TextColumn::make('target_delivery_date')
                ->date()
                ->sortable(),
            TextColumn::make('delivered_at')
                ->date()
                ->sortable(),
            TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
            ->stackedOnMobile()
            ->filters([
                SelectFilter::make('status')->options(FeatureStatus::class),
                SelectFilter::make('type')->options(FeatureType::class),
            ])
            ->recordActions([
                ViewAction::make()
                    ->url(fn (Feature $record) => route('features.create-comment', [
                        'feature' => $record,
                    ])),
                // We'll implement this when a frontend edit page exists.
                // EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
};
?>

<div class="mx-auto max-w-7xl space-y-8 px-6 py-10">

    {{-- Page Header --}}
    <section class="rounded-xl border border-gray-200 bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-gray-900">

        <span
            class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
            Features
        </span>

        <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
            Product Roadmap
        </h1>

        <p class="mt-4 max-w-3xl leading-8 text-gray-600 dark:text-gray-300">
            Explore planned, in-progress, and completed features. Browse the roadmap,
            review implementation details, and participate by sharing your feedback on
            each feature.
        </p>

    </section>

    {{-- Features Table --}}
    <section class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">

        <div
            class="flex flex-col gap-4 border-b border-gray-200 px-6 py-5 dark:border-gray-800 md:flex-row md:items-center md:justify-between">

            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Features
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Browse all roadmap items, search, filter, and sort features.
                </p>
            </div>

        </div>

        <div class="p-6">
            {{ $this->table }}
        </div>

    </section>

</div>