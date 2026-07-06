<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name', 'email', 'phone', 'password_hash', 'city', 'role',
        'saved_vehicles', 'quiz_result', 'email_verified',
        'status', 'last_login',
    ];

    protected $useTimestamps = true;

    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }

    public function getSavedVehicles(int $userId): array
    {
        $user = $this->find($userId);
        if (!$user || empty($user['saved_vehicles'])) {
            return [];
        }
        return json_decode($user['saved_vehicles'], true) ?? [];
    }

    public function toggleSavedVehicle(int $userId, int $vehicleId): bool
    {
        $user  = $this->find($userId);
        $saved = json_decode($user['saved_vehicles'] ?? '[]', true) ?? [];

        if (in_array($vehicleId, $saved)) {
            $saved = array_values(array_filter($saved, fn($id) => $id !== $vehicleId));
            $added = false;
        } else {
            $saved[] = $vehicleId;
            $added   = true;
        }

        $this->update($userId, ['saved_vehicles' => json_encode($saved)]);

        return $added;
    }
}
