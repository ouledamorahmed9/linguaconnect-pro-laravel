<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'phone' => ['required', 'string', 'max:20'], // <--- New Validation for Phone
            
            // --- SECURITY FIX: Validate Uploads ---
            // 1. Must be an image (jpg, png, etc)
            // 2. Max size 2MB (2048 KB) to prevent server overload
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], 
            'bio' => ['nullable', 'string', 'max:1000'], // New
        'banner_photo' => ['nullable', 'image', 'max:2048'], // New (Validation only)
        ];
    }
}