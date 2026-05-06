<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        $user = new User;

        $user->forceFill([
            ...$data,
            'email_verified_at' => now(),
        ]);

        $user->save();

        $roleName = $this->form->getState()['role'] ?? null;

        if ($roleName) {
            $role = Role::findOrCreate($roleName);
            $user->syncRoles([$role]);
        }

        return $user;
    }
}
