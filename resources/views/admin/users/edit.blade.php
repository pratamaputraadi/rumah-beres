@extends('layouts.main')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-white">Edit User: {{ $user->name }}</h2>
        <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-white">Cancel</a>
    </div>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="bg-gray-800 p-8 rounded-2xl border border-gray-700 space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-gray-400 text-sm mb-1">Full Name</label>
            <input type="text" name="name" value="{{ $user->name }}" class="w-full bg-gray-900 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 outline-none">
        </div>

        <div>
            <label class="block text-gray-400 text-sm mb-1">Email</label>
            <input type="email" name="email" value="{{ $user->email }}" class="w-full bg-gray-900 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 outline-none">
        </div>

        <div>
            <label class="block text-gray-400 text-sm mb-1">Role</label>
            <select name="role" class="w-full bg-gray-900 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 outline-none">
                <option value="customer" {{ $user->role == 'customer' ? 'selected' : '' }}>Customer</option>
                <option value="technician" {{ $user->role == 'technician' ? 'selected' : '' }}>Technician</option>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <div>
            <label class="block text-gray-400 text-sm mb-1">Verification Status</label>
            <select name="is_verified" class="w-full bg-gray-900 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 outline-none">
                <option value="0" {{ $user->is_verified == 0 ? 'selected' : '' }}>Pending / Not Verified</option>
                <option value="1" {{ $user->is_verified == 1 ? 'selected' : '' }}>Verified (Approved)</option>
            </select>
        </div>

        <div>
            <label class="block text-gray-400 text-sm mb-1">Address</label>
            <textarea name="address" rows="2" class="w-full bg-gray-900 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 outline-none">{{ $user->address }}</textarea>
        </div>

        <div class="pt-4 border-t border-gray-700">
            <label class="block text-gray-400 text-sm mb-1">Change Password (Optional)</label>
            <input type="password" name="password" placeholder="Leave blank to keep current password" class="w-full bg-gray-900 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 outline-none">
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-xl transition">
            Update User Data
        </button>
    </form>
</div>
@endsection