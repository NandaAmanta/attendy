<?php

namespace App\Filament\Employee\Resources;

use App\Consts\AttendanceType;
use App\Filament\Employee\Resources\MyAttendanceResource\Pages;
use App\Filament\Filters\RangeDateFilter;
use App\Models\Attendance;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MyAttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'My Attendance';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultGroup(
                Group::make('present_at')
                    ->label('Date')
                    ->date(),
            )
            ->modifyQueryUsing(fn ($query) => $query->where('employee_id', Auth::guard('employee_web')->user()->id))
            ->columns([
                TextColumn::make('no')
                    ->rowIndex(),
                TextColumn::make('present_at')
                    ->dateTime(),
                TextColumn::make('type')
                    ->badge()
                    ->color(function (Attendance $record) {
                        if ($record->type == AttendanceType::IN->value) {
                            return 'success';
                        }

                        return 'warning';
                    }),
                IconColumn::make('is_ontime')
                    ->boolean(),
                IconColumn::make('is_in_office')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                RangeDateFilter::make('present_at'),
            ])
            ->actions([
            ])
            ->bulkActions([
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMyAttendances::route('/'),
            'create' => Pages\CreateMyAttendance::route('/create'),
            'edit' => Pages\EditMyAttendance::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canView(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }
}
