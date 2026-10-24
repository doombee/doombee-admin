<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Facades\Filament;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required(),
                Select::make('merchant_id')
                    ->label('Merchant')
                    ->relationship('merchant', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->hidden(fn () => Filament::auth()->user()?->hasRole('merchant')),
                Select::make('role')
                    ->label('Role')
                    ->options([
                        'admin'    => 'Admin',
                        'merchant' => 'Merchant',
                    ])
                    ->visible(fn () => auth()->user()->hasRole('admin'))
                    ->required()
            ]);
    }
}
