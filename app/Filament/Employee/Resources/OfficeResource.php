<?php

namespace App\Filament\Employee\Resources;

use App\Consts\Action;
use App\Consts\Module;
use App\Filament\Employee\Resources\OfficeResource\Pages;
use App\Models\Office;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class OfficeResource extends Resource
{
    protected static ?string $model = Office::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('lat')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('lng')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('max_radius_attendance_in_meter')
                    ->required()
                    ->numeric(),
                Forms\Components\DateTimePicker::make('max_attendance_in_hour')
                    ->required()
                    ->date(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (EloquentBuilder $query) => $query->where('user_id', FacadesAuth::user()->user_id))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('max_attendance_in_hour')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListOffices::route('/'),
            'create' => Pages\CreateOffice::route('/create'),
            'edit' => Pages\EditOffice::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return logged_in_employee_has_permission(Action::READ, Module::EMPLOYEE);
    }

    public static function canCreate(): bool
    {
        return logged_in_employee_has_permission(Action::CREATE, Module::EMPLOYEE);
    }

    public static function canEdit(Model $record): bool
    {
        return logged_in_employee_has_permission(Action::UPDATE, Module::EMPLOYEE)
            && $record->user_id == FacadesAuth::user()->user_id;
    }

    public static function canView(Model $record): bool
    {
        return logged_in_employee_has_permission(Action::READ, Module::EMPLOYEE)
        && $record->user_id == FacadesAuth::user()->user_id;
    }

    public static function canDelete(Model $record): bool
    {
        return logged_in_employee_has_permission(Action::DELETE, Module::EMPLOYEE)
        && $record->user_id == FacadesAuth::user()->user_id;
    }
}
