<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    protected UserRepository $users;
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function all(array $with = [])
    {
        return $this->users->all($with);
    }

    public function paginate(int $perPage = 15, array $with = [])
    {
        return $this->users->paginate($perPage, $with);
    }

    public function find($id, array $with = [])
    {
        return $this->users->findOrFail($id, $with);
    }

    public function create(array $attributes)
    {
        // منطق إضافي قبل الإنشاء (مثلاً: تحقق أو معالجة)
        return $this->users->create($attributes);
    }

    public function update($id, array $attributes)
    {
        // لا تقم بتحديث كلمة المرور إذا لم يتم إرسالها
        if (array_key_exists('password', $attributes) && empty($attributes['password'])) {
            unset($attributes['password']);
        }

        return $this->users->update($id, $attributes);
    }

    public function delete($id)
    {
        return $this->users->delete($id);
    }

    public function activate($id)
    {
        return $this->users->activate($id);
    }

    public function deactivate($id)
    {
        return $this->users->deactivate($id);
    }

    /**
     * Update user profile
     *
     * @param \App\Models\User $user
     * @param array $data
     * @return \App\Models\User
     */
    public function updateProfile($user, array $data)
    {
        // Remove password if empty
        if (array_key_exists('password', $data) && empty($data['password'])) {
            unset($data['password']);
        }

        // Handle avatar upload if present
        if (isset($data['avatar']) && $data['avatar'] instanceof \Illuminate\Http\UploadedFile) {
            $data['avatar'] = $this->users->uploadFile($data['avatar'], 'avatars', true);

            // Delete old avatar if exists
            if ($user->avatar) {
                $this->users->deleteFile($user->avatar, true);
            }
        }

        return $this->users->update($user->id, $data);
    }
}
