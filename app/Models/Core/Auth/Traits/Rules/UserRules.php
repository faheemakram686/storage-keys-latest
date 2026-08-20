<?php


namespace App\Models\Core\Auth\Traits\Rules;


trait UserRules
{
    public function createdRules()
    {
        return [
            'first_name' => 'required',
            'email' => ['required', 'email', unique_active_user_email_rule()],
            'password' => ['required', 'min:8'],
            'roles' => ['nullable', 'array'],
        ];
    }

    public function updatedRules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'nullable|min:2',
        ];
    }

    public function attachRoleRules()
    {
        return [
            'roles' => 'required'
        ];
    }

    public function userSettingsRules()
    {
        return [
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'first_name' => 'required|min:2',
            'email' => ['required', 'email', unique_active_user_email_rule(auth()->id())],
        ];
    }

    public function thumbnailRules()
    {
        return [
            'profile_picture' => 'required|image'
        ];
    }

    public function changePasswordRules()
    {
        return [
            'old_password' => 'required|min:6',
            'password' => 'required|min:8|confirmed',
        ];
    }

    public function loginRules()
    {
        return [
            'email' => 'required|min:5|email',
            'password'=> 'required'
        ];
    }

    public function resetPasswordRules()
    {
        return [
            'password' => 'required|min:8|confirmed',
            'token' => 'required|min:10',
            'email' => 'required|email'
        ];
    }

}
