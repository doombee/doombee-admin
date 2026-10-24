<?php

namespace App\Filament\Resources\Merchants\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MerchantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Select::make('status')
                    ->options(['active' => 'Active', 'suspended' => 'Suspended'])
                    ->default('active')
                    ->required(),
                TextInput::make('settings'),
            ]);
    }
}
