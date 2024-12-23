<?php

namespace App\Filament\Employee\Resources;

use App\Consts\Action;
use App\Consts\LeaveStatus;
use App\Consts\LeaveType;
use App\Consts\Module;
use App\Filament\Employee\Resources\LeaveApplicationResource\Pages;
use App\Models\LeaveApplication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action as ActionsAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LeaveApplicationResource extends Resource
{
    protected static ?string $model = LeaveApplication::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('employee_id')
                    ->relationship('employee', 'name')
                    ->default(get_user_id_from_auth_user())
                    ->preload()
                    ->disabled()
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\Select::make('type')
                    ->options([
                        LeaveType::GENERAL->value => 'General',
                        LeaveType::SICK->value => 'Sick',
                    ])
                    ->required(),
                Forms\Components\DateTimePicker::make('start_at')
                    ->required(),
                Forms\Components\DateTimePicker::make('end_at')
                    ->required(),
                Forms\Components\FileUpload::make('image_path')
                    ->image(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('employee.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('start_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(function (Model $record): string {
                        return match ($record->status) {
                            LeaveStatus::DRAFT->value => 'gray',
                            LeaveStatus::WAITING_FOR_APPROVAL->value => 'warning',
                            LeaveStatus::APPROVED->value => 'success',
                            LeaveStatus::REJECTED->value => 'danger',
                            default => 'secondary',
                        };
                    }),
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
                ActionsAction::make('approve')
                    ->requiresConfirmation()
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn ($record): bool => $record->status == LeaveStatus::WAITING_FOR_APPROVAL->value
                    && logged_in_employee_has_permission(Action::APPROVAL, Module::LEAVE_APPLICATION))
                    ->action(fn (Model $record) => $record->update(['status' => LeaveStatus::APPROVED->value])),

                ActionsAction::make('reject')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->visible(fn ($record): bool => $record->status == LeaveStatus::WAITING_FOR_APPROVAL->value
                    && logged_in_employee_has_permission(Action::APPROVAL, Module::LEAVE_APPLICATION))
                    ->action(fn (Model $record) => $record->update(['status' => LeaveStatus::REJECTED->value])),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListLeaveApplications::route('/'),
            'create' => Pages\CreateLeaveApplication::route('/create'),
            'edit' => Pages\EditLeaveApplication::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return logged_in_employee_has_permission(Action::READ, Module::LEAVE_APPLICATION);
    }

    public static function canCreate(): bool
    {
        return logged_in_employee_has_permission(Action::CREATE, Module::LEAVE_APPLICATION);
    }

    public static function canEdit(Model $record): bool
    {
        return $record->user_id == Auth::user()->user_id;
    }

    public static function canView(Model $record): bool
    {
        return logged_in_employee_has_permission(Action::READ, Module::LEAVE_APPLICATION);
    }

    public static function canDelete(Model $record): bool
    {
        return logged_in_employee_has_permission(Action::DELETE, Module::LEAVE_APPLICATION);
    }
}
