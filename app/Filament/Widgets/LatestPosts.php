<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestPosts extends BaseWidget
{
    protected int|string|array $columnSpan = 'half';

    protected function getTableQuery(): Builder
    {
        return Post::query()->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('title')->limit(20)
                ->sortable(),
            TextColumn::make('created_at')
                ->dateTime('Y-m-d H:i:s'),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            Filter::make('created_at')
                ->form([
                    DatePicker::make('created_from'),
                    DatePicker::make('created_until'),
                ])
                ->query(function (Builder $query, array $data): Builder {

                    $query->limit(1);

                    return $query
                        ->when(
                            $data['created_from'],
                            fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['created_until'],
                            fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                })
        ];
    }
}
