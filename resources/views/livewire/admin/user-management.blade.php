<div>
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 font-luxury">User Management</h1>
        <button wire:click="create" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Add New User
        </button>
    </div>

    <!-- User Table -->
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-4 border-b border-gray-200">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search users by name or email..." class="w-full md:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 
                                       ($user->role === 'seller' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button wire:click="edit({{ $user->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                @if($user->id !== auth()->id())
                                    <button wire:click="confirmUserDeletion({{ $user->id }})" class="text-red-600 hover:text-red-900">Delete</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    </div>

    <!-- User Value Modal -->
    <x-dialog-modal wire:model="confirmingUserManagement">
        <x-slot name="title">
            {{ isset($this->user->id) ? 'Edit User' : 'Add User' }}
        </x-slot>

        <x-slot name="content">
            <!-- Name -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="name" value="{{ __('Name') }}" />
                <x-input id="name" type="text" class="mt-1 block w-full" wire:model="state.name" autocomplete="name" />
                <x-input-error for="state.name" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="col-span-6 sm:col-span-4 mt-4">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" type="email" class="mt-1 block w-full" wire:model="state.email" />
                <x-input-error for="state.email" class="mt-2" />
            </div>

            <!-- Role -->
            <div class="col-span-6 sm:col-span-4 mt-4">
                <x-label for="role" value="{{ __('Role') }}" />
                <select id="role" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" wire:model="state.role">
                    <option value="customer">Customer</option>
                    <option value="seller">Seller</option>
                    <option value="admin">Admin</option>
                </select>
                <x-input-error for="state.role" class="mt-2" />
            </div>

            <!-- Is Active -->
             <div class="col-span-6 sm:col-span-4 mt-4">
                <label for="is_active" class="flex items-center">
                    <x-checkbox id="is_active" wire:model="state.is_active" />
                    <span class="ml-2 text-sm text-gray-600">{{ __('Active') }}</span>
                </label>
            </div>

            <!-- Password -->
            <div class="col-span-6 sm:col-span-4 mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" type="password" class="mt-1 block w-full" wire:model="state.password" placeholder="{{ isset($this->user->id) ? 'Leave blank to keep current password' : '' }}" />
                <x-input-error for="state.password" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmingUserManagement', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ml-3" wire:click="{{ isset($this->user->id) ? 'update' : 'store' }}" wire:loading.attr="disabled">
                {{ isset($this->user->id) ? __('Update') : __('Save') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Delete User Confirmation Modal -->
    <x-confirmation-modal wire:model="confirmingUserDeletion">
        <x-slot name="title">
            {{ __('Delete User') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete this user? Once a user is deleted, all of their resources and data will be permanently deleted.') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmingUserDeletion', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ml-3" wire:click="deleteUser" wire:loading.attr="disabled">
                {{ __('Delete User') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</div>
