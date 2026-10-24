<?php

namespace App\Filament\Resources\Users\Pages;

use AllowDynamicProperties;
use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

#[AllowDynamicProperties]
class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->role = $data['role'];

        unset($data['role']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->assignRole($this->role);
    }
}
