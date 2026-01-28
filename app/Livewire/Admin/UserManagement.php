<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $confirmingUserManagement = false;
    public $confirmingUserDeletion = false;
    public $userIdBeingDeleted;
    
    public $state = [
        'name' => '',
        'email' => '',
        'password' => '',
        'role' => 'customer',
        'is_active' => true,
    ];

    public $user; // Model being edited

    protected $rules = [
        'state.name' => 'required|string|max:255',
        'state.email' => 'required|email|max:255|unique:users,email',
        'state.password' => 'required|string|min:8',
        'state.role' => 'required|in:admin,seller,customer',
        'state.is_active' => 'boolean',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::where('name', 'like', '%'.$this->search.'%')
            ->orWhere('email', 'like', '%'.$this->search.'%')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.user-management', [
            'users' => $users
        ]);
    }

    public function create()
    {
        $this->reset(['state', 'user']);
        $this->state['role'] = 'customer';
        $this->state['is_active'] = true;
        $this->confirmingUserManagement = true;
    }

    public function edit(User $user)
    {
        $this->user = $user;
        $this->state = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'is_active' => (bool) $user->is_active,
            'password' => '',
        ];
        $this->confirmingUserManagement = true;
    }

    public function store()
    {
        // Dynamic validation for email uniqueness ignoring current user if editing, but this is store so always unique
        $this->validate();

        User::create([
            'name' => $this->state['name'],
            'email' => $this->state['email'],
            'password' => $this->state['password'],
            'role' => $this->state['role'],
            'is_active' => $this->state['is_active'],
        ]);

        $this->confirmingUserManagement = false;
        $this->dispatch('saved'); 
        session()->flash('flash.banner', 'User created successfully.');
        session()->flash('flash.bannerStyle', 'success');
    }

    public function update()
    {
        $this->validate([
            'state.name' => 'required|string|max:255',
            'state.email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user->id)],
            'state.role' => 'required|in:admin,seller,customer',
            'state.is_active' => 'boolean',
            'state.password' => 'nullable|string|min:8',
        ]);

        $data = [
            'name' => $this->state['name'],
            'email' => $this->state['email'],
            'role' => $this->state['role'],
            'is_active' => $this->state['is_active'],
        ];

        if (!empty($this->state['password'])) {
            $data['password'] = $this->state['password'];
        }

        $this->user->update($data);

        $this->confirmingUserManagement = false;
        $this->dispatch('saved');
        session()->flash('flash.banner', 'User updated successfully.');
        session()->flash('flash.bannerStyle', 'success');
    }

    public function confirmUserDeletion($userId)
    {
        $this->userIdBeingDeleted = $userId;
        $this->confirmingUserDeletion = true;
    }

    public function deleteUser()
    {
        $user = User::find($this->userIdBeingDeleted);
        
        if ($user && $user->id !== auth()->id()) {
            $user->delete();
        }

        $this->confirmingUserDeletion = false;
        $this->reset('userIdBeingDeleted');
        session()->flash('flash.banner', 'User deleted successfully.');
        session()->flash('flash.bannerStyle', 'success');
    }
}
