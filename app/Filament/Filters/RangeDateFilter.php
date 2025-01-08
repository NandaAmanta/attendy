<?php

namespace App\Filament\Filters;

use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class RangeDateFilter extends Filter
{
    protected function setUp(): void
    {
        parent::setUp();
        $name = $this->getName();
        $this
            ->form([
                DatePicker::make('start_date'),
                DatePicker::make('end_date'),
            ])
            ->query(function (Builder $query, array $data) use ($name): Builder {
                $startDate = $data['start_date'] ?? null;
                $endDate = $data['end_date'] ?? null;
                if ($startDate) {
                    $query->whereDate($name ?? 'created_at', '>=', $startDate);
                }

                if ($endDate) {
                    $query->whereDate($name ?? 'created_at', '<=', $endDate);
                }

                return $query;
            });
    }
}
