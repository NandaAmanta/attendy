<?php

namespace App\Filament\Employee\Resources;

use App\Consts\Action as ConstsAction;
use App\Consts\AttendanceType;
use App\Consts\Module;
use App\Filament\Employee\Resources\AttendanceResource\Pages;
use App\Filament\Filters\RangeDateFilter;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('employee_id')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('note')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('type')
                    ->required(),
                Forms\Components\Toggle::make('is_ontime')
                    ->required(),
                Forms\Components\Toggle::make('is_in_office')
                    ->required(),
                Forms\Components\TextInput::make('lat')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('lng')
                    ->required()
                    ->numeric(),
                Forms\Components\DateTimePicker::make('present_at')
                    ->required(),
                Forms\Components\FileUpload::make('image_path')
                    ->image(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultGroup(
                Group::make('present_at')
                    ->label('Date')
                    ->date(), )
            ->modifyQueryUsing(fn ($query) => $query->whereNot('employee_id', Auth::guard('employee_web')->user()->id))
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('employee.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('employee.office.name'),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(function (Attendance $record) {
                        if ($record->type == AttendanceType::IN->value) {
                            return 'success';
                        }

                        return 'warning';
                    }),
                Tables\Columns\IconColumn::make('is_ontime')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_in_office')
                    ->boolean(),
                Tables\Columns\TextColumn::make('present_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image_path'),
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
                RangeDateFilter::make('present_at'),
            ])
            ->actions([
                Action::make('check_location')
                    ->icon('heroicon-s-map-pin')
                    ->label('Location')
                    ->color(Color::Blue)
                    ->url(fn (Attendance $record) => "https://maps.google.com?q=$record->lat,$record->lng"),

                Action::make('check_selfie')
                    ->icon('heroicon-s-camera')
                    ->label('Selfie')
                    ->color(Color::Green)
                    ->url(fn (Attendance $record) => Storage::url($record->selfie_path)),
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
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return logged_in_employee_has_permission(ConstsAction::READ, Module::ATTENDANCE);
    }

    public static function canCreate(): bool
    {
        return logged_in_employee_has_permission(ConstsAction::CREATE, Module::ATTENDANCE);
    }

    public static function canEdit(Model $record): bool
    {
        return logged_in_employee_has_permission(ConstsAction::UPDATE, Module::ATTENDANCE)
            && $record->user_id == Auth::user()->user_id;
    }

    public static function canView(Model $record): bool
    {
        return logged_in_employee_has_permission(ConstsAction::READ, Module::ATTENDANCE)
        && $record->user_id == Auth::user()->user_id;
    }

    public static function canDelete(Model $record): bool
    {
        return logged_in_employee_has_permission(ConstsAction::DELETE, Module::ATTENDANCE)
        && $record->user_id == Auth::user()->user_id;
    }
}
