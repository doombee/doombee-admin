<?php

namespace App\Filament\Resources\Stores\RelationManagers;

use App\Integrations\Enums\ConnectorType;
use Filament\Actions\CreateAction;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class ConnectionsRelationManager extends RelationManager
{
    protected static string $relationship = 'connections';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([

            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('connector')
                ->options(
                    collect(ConnectorType::cases())
                        ->mapWithKeys(fn (ConnectorType $type) => [
                            $type->value => $type->name,
                        ])
                )
                ->required()
                ->live(),

            Forms\Components\Toggle::make('enabled')
                ->default(true),

            Group::make([

                Forms\Components\TextInput::make('credentials.client_id'),

                Forms\Components\TextInput::make('credentials.client_secret')
                    ->password(),

            ])->visible(
                fn (Get $get) =>
                    $get('connector') === ConnectorType::Shopware->value
            ),

        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),

                TextColumn::make('connector')
                    ->badge(),

                TextColumn::make('credentials.client_id')
                    ->label('Client ID'),

                TextColumn::make('client_secret')
                    ->label('Client Secret')
                    ->state(fn ($record) => $this->maskCredential(
                        data_get($record->credentials, 'client_secret')
                    )),

                IconColumn::make('enabled')
                    ->boolean(),

                TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->headerActions([
                CreateAction::make(),
            ]);
    }

    protected function maskCredential(?string $value, int $visible = 5): string
    {
        if (blank($value)) {
            return '-';
        }

        return '••••' . substr($value, -$visible);
    }
}
