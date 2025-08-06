<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CustomUserModel;

class ProfileController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new CustomUserModel();
    }

    public function index()
    {
        // Load avatar helper
        helper('avatar');
        
        // Get current user
        $userId = auth()->id();
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to(base_url('login'));
        }

        $data = [
            'title' => lang('Profile.userProfile'),
            'user' => $user
        ];

        return view('profile/index', $data);
    }

    public function edit()
    {
        // Load form helper for form functions
        helper('form');
        
        // Get current user
        $userId = auth()->id();
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to(base_url('login'));
        }

        $data = [
            'title' => lang('Profile.editProfile'),
            'user' => $user
        ];

        return view('profile/edit', $data);
    }

    public function update()
    {
        // Load avatar helper
        helper('avatar');
        
        // Get current user
        $userId = auth()->id();
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to(base_url('login'));
        }

        // Validation rules
        $rules = [
            'first_name' => 'permit_empty|string|max_length[255]',
            'last_name' => 'permit_empty|string|max_length[255]',
            'phone' => 'permit_empty|string|max_length[20]',
            'status_message' => 'permit_empty|string|max_length[255]',
            'avatar' => 'permit_empty|uploaded[avatar]|is_image[avatar]|max_size[avatar,2048]|ext_in[avatar,png,jpg,jpeg,gif,webp]',
            'date_format' => 'permit_empty|string|max_length[10]',
            'timezone' => 'permit_empty|string|max_length[50]',
            'avatar_style' => 'permit_empty|in_list[initials,gravatar,robohash,identicon]',
            'delete_avatar' => 'permit_empty|in_list[0,1]',
        ];

        // If avatar is empty, remove it from validation rules
        $avatar = $this->request->getFile('avatar');
        if ($avatar && !$avatar->isValid()) {
            unset($rules['avatar']);
        }

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Update user data
        $userData = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'phone' => $this->request->getPost('phone'),
            'status_message' => $this->request->getPost('status_message'),
            'web_notifications' => $this->request->getPost('web_notifications') !== null ? 1 : 0,
            'email_notifications' => $this->request->getPost('email_notifications') !== null ? 1 : 0,
            'sms_notifications' => $this->request->getPost('sms_notifications') !== null ? 1 : 0,
            'date_format' => $this->request->getPost('date_format') ?: 'M-d-Y',
            'timezone' => $this->request->getPost('timezone') ?: 'UTC',
            'avatar_style' => $this->request->getPost('avatar_style') ?: 'initials',
        ];

        // Handle avatar deletion
        $deleteAvatar = $this->request->getPost('delete_avatar');
        if ($deleteAvatar === '1') {
            // Delete current avatar file if exists
            if (!empty($user->avatar) && file_exists(FCPATH . 'assets/images/users/' . $user->avatar)) {
                unlink(FCPATH . 'assets/images/users/' . $user->avatar);
            }
            
            // Clear avatar field in database
            $userData['avatar'] = null;
            
            session()->setFlashdata('message', 'Avatar deleted successfully. Using fallback avatar style.');
        }

        // Handle avatar upload using the new system (only if not deleting and file provided)
        if ($deleteAvatar !== '1' && $avatar && $avatar->isValid() && !$avatar->hasMoved()) {
            $uploadResult = uploadAvatar($avatar, $userId, 2048);
            
            if ($uploadResult['success']) {
                // Delete old avatar if exists
                if (!empty($user->avatar) && file_exists(FCPATH . 'assets/images/users/' . $user->avatar)) {
                    unlink(FCPATH . 'assets/images/users/' . $user->avatar);
                }
                
                $userData['avatar'] = $uploadResult['filename'];
                
                if (isset($uploadResult['warning'])) {
                    session()->setFlashdata('warning', $uploadResult['warning']);
                }
                
                session()->setFlashdata('message', 'Avatar uploaded successfully.');
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', $uploadResult['error']);
            }
        }

        $this->userModel->update($userId, $userData);

        return redirect()->to(base_url('profile'))
            ->with('message', session('message') ?: lang('Profile.profileUpdated'));
    }

    /**
     * Show avatar demo page
     */
    public function avatarDemo()
    {
        // Load form helper for form functions
        helper('form');
        
        // Get current user
        $userId = auth()->id();
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to(base_url('login'));
        }

        $data = [
            'title' => 'Avatar Demo - Choose Your Style',
            'user' => $user
        ];

        return view('profile/avatar_demo', $data);
    }

    /**
     * Get the avatar URL for a user using the new system
     * 
     * @param object $user User object
     * @param int $size Avatar size
     * @return string URL to the avatar image
     */
    public function getAvatarUrl($user, $size = 150)
    {
        helper('avatar');
        $style = $user->avatar_style ?? 'initials';
        return getAvatarUrl($user, $size, $style);
    }
} 