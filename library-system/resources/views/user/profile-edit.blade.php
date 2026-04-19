@extends('layouts.app')
@section('title', 'Edit Profile')
@section('page-title', 'Edit Profile')

@section('content')
@php($profileRoutePrefix = auth()->user()->isAdmin() ? 'admin' : 'user')
<div style="max-width:680px;">

    {{-- Update Profile --}}
    <div class="card" style="margin-bottom:20px;">
        <div class="card-header"><span class="card-title">Personal Information</span></div>
        <div class="card-body">
            <form action="{{ route($profileRoutePrefix . '.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div style="display:flex;align-items:center;gap:20px;margin-bottom:24px;padding-bottom:24px;border-bottom:1px solid var(--cream-2);">
                    <img src="{{ $user->avatar_url }}" id="avatarPreview"
                         style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:2px solid var(--cream-2);">
                    <div>
                        <label class="btn btn-outline btn-sm" style="cursor:pointer;">
                            <i class="fas fa-camera"></i> Change Photo
                            <input type="file" name="avatar" accept="image/*" style="display:none;" onchange="previewAvatar(this)">
                        </label>
                        <p style="font-size:12px;color:var(--text-muted);margin-top:6px;">JPG, PNG or WebP. Max 2MB.</p>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email <span style="color:var(--text-muted);font-weight:400">(read-only)</span></label>
                        <input type="email" class="form-control" value="{{ $user->email }}" disabled style="background:var(--cream);cursor:not-allowed;">
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="+1 (555) 000-0000">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}" placeholder="123 Main Street, City">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
            </form>
        </div>
    </div>

    {{-- Change Password --}}
    <div class="card" style="margin-bottom:20px;">
        <div class="card-header"><span class="card-title">Change Password</span></div>
        <div class="card-body">
            <form action="{{ route($profileRoutePrefix . '.profile.password') }}" method="POST">
                @csrf @method('PUT')
                <div class="form-group">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Min. 8 characters" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-key"></i> Update Password</button>
            </form>
        </div>
    </div>

    {{-- Danger Zone --}}
    @unless(auth()->user()->isAdmin())
        <div class="card" style="border-color:#fee2e2;">
            <div class="card-header"><span class="card-title" style="color:var(--danger);">Danger Zone</span></div>
            <div class="card-body">
                <p style="font-size:14px;color:var(--text-muted);margin-bottom:16px;">
                    Permanently delete your account and all associated data. This action cannot be undone.
                </p>
                <button class="btn btn-danger btn-sm" onclick="document.getElementById('deleteModal').style.display='flex'">
                    <i class="fas fa-trash"></i> Delete Account
                </button>
            </div>
        </div>
    @endunless
</div>

@unless(auth()->user()->isAdmin())
    {{-- Delete Account Modal --}}
    <div id="deleteModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
        <div style="background:white;border-radius:16px;padding:32px;max-width:380px;width:90%;margin:20px;">
            <h3 style="font-family:'DM Serif Display',serif;font-size:22px;color:var(--danger);margin-bottom:8px;">Delete Account</h3>
            <p style="font-size:14px;color:var(--text-muted);margin-bottom:20px;">This will permanently delete your account. Enter your password to confirm.</p>
            <form action="{{ route('user.profile.delete') }}" method="POST">
                @csrf @method('DELETE')
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div style="display:flex;gap:10px;">
                    <button type="submit" class="btn btn-danger" style="flex:1;justify-content:center;">Confirm Delete</button>
                    <button type="button" class="btn btn-outline" onclick="document.getElementById('deleteModal').style.display='none'">Cancel</button>
                </div>
            </form>
        </div>
    </div>
@endunless

@push('scripts')
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('avatarPreview').src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
