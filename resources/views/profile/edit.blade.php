@extends('layouts.app')

@section('page-title', 'My Profile')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-7">

        {{-- Profile Info --}}
        <div class="cc mb-3">
            <div class="cc-h">
                <div class="cc-t"><i class="bi bi-person-circle me-2" style="color:var(--blue)"></i>Profile Information</div>
            </div>
            <div style="padding:20px;">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf @method('PATCH')
                    <div style="margin-bottom:14px;">
                        <label style="display:block;font-size:12px;font-weight:600;color:var(--t2);margin-bottom:6px;text-transform:uppercase;letter-spacing:0.5px;">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            style="width:100%;background:var(--dark);border:1px solid var(--bdr);border-radius:8px;padding:10px 14px;color:var(--t1);font-size:14px;outline:none;"
                            onfocus="this.style.borderColor='var(--blue)'" onblur="this.style.borderColor='var(--bdr)'">
                        @error('name')<span style="font-size:12px;color:var(--red);display:block;margin-top:4px;">{{ $message }}</span>@enderror
                    </div>
                    <div style="margin-bottom:20px;">
                        <label style="display:block;font-size:12px;font-weight:600;color:var(--t2);margin-bottom:6px;text-transform:uppercase;letter-spacing:0.5px;">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            style="width:100%;background:var(--dark);border:1px solid var(--bdr);border-radius:8px;padding:10px 14px;color:var(--t1);font-size:14px;outline:none;"
                            onfocus="this.style.borderColor='var(--blue)'" onblur="this.style.borderColor='var(--bdr)'">
                        @error('email')<span style="font-size:12px;color:var(--red);display:block;margin-top:4px;">{{ $message }}</span>@enderror
                    </div>
                    <button type="submit" style="background:linear-gradient(135deg,var(--blue),var(--purple));color:white;border:none;padding:10px 22px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:7px;">
                        <i class="bi bi-check-lg"></i> Save Changes
                    </button>
                    @if(session('status') === 'profile-updated')
                        <span style="margin-left:12px;font-size:12px;color:var(--green);"><i class="bi bi-check-circle"></i> Saved!</span>
                    @endif
                </form>
            </div>
        </div>

        {{-- Change Password --}}
        <div class="cc mb-3">
            <div class="cc-h">
                <div class="cc-t"><i class="bi bi-lock me-2" style="color:var(--orange)"></i>Change Password</div>
            </div>
            <div style="padding:20px;">
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf @method('PUT')
                    <div style="margin-bottom:14px;">
                        <label style="display:block;font-size:12px;font-weight:600;color:var(--t2);margin-bottom:6px;text-transform:uppercase;letter-spacing:0.5px;">Current Password</label>
                        <input type="password" name="current_password"
                            style="width:100%;background:var(--dark);border:1px solid var(--bdr);border-radius:8px;padding:10px 14px;color:var(--t1);font-size:14px;outline:none;"
                            onfocus="this.style.borderColor='var(--orange)'" onblur="this.style.borderColor='var(--bdr)'">
                        @error('current_password','updatePassword')<span style="font-size:12px;color:var(--red);display:block;margin-top:4px;">{{ $message }}</span>@enderror
                    </div>
                    <div style="margin-bottom:14px;">
                        <label style="display:block;font-size:12px;font-weight:600;color:var(--t2);margin-bottom:6px;text-transform:uppercase;letter-spacing:0.5px;">New Password</label>
                        <input type="password" name="password"
                            style="width:100%;background:var(--dark);border:1px solid var(--bdr);border-radius:8px;padding:10px 14px;color:var(--t1);font-size:14px;outline:none;"
                            onfocus="this.style.borderColor='var(--orange)'" onblur="this.style.borderColor='var(--bdr)'">
                        @error('password','updatePassword')<span style="font-size:12px;color:var(--red);display:block;margin-top:4px;">{{ $message }}</span>@enderror
                    </div>
                    <div style="margin-bottom:20px;">
                        <label style="display:block;font-size:12px;font-weight:600;color:var(--t2);margin-bottom:6px;text-transform:uppercase;letter-spacing:0.5px;">Confirm New Password</label>
                        <input type="password" name="password_confirmation"
                            style="width:100%;background:var(--dark);border:1px solid var(--bdr);border-radius:8px;padding:10px 14px;color:var(--t1);font-size:14px;outline:none;"
                            onfocus="this.style.borderColor='var(--orange)'" onblur="this.style.borderColor='var(--bdr)'">
                    </div>
                    <button type="submit" style="background:rgba(245,158,11,0.15);color:var(--orange);border:1px solid rgba(245,158,11,0.3);padding:10px 22px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:7px;"
                        onmouseover="this.style.background='var(--orange)';this.style.color='white'"
                        onmouseout="this.style.background='rgba(245,158,11,0.15)';this.style.color='var(--orange)'">
                        <i class="bi bi-lock"></i> Update Password
                    </button>
                    @if(session('status') === 'password-updated')
                        <span style="margin-left:12px;font-size:12px;color:var(--green);"><i class="bi bi-check-circle"></i> Password updated!</span>
                    @endif
                </form>
            </div>
        </div>

        {{-- Account Summary --}}
        <div class="cc">
            <div class="cc-h">
                <div class="cc-t"><i class="bi bi-shield-check me-2" style="color:var(--green)"></i>Account Summary</div>
            </div>
            <div style="padding:16px;display:flex;flex-direction:column;gap:8px;">
                <div style="display:flex;justify-content:space-between;padding:10px 14px;background:var(--dark);border-radius:8px;">
                    <span style="font-size:13px;color:var(--t2);">Name</span>
                    <span style="font-size:13px;font-weight:600;">{{ auth()->user()->name }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:10px 14px;background:var(--dark);border-radius:8px;">
                    <span style="font-size:13px;color:var(--t2);">Email</span>
                    <span style="font-size:13px;font-weight:600;">{{ auth()->user()->email }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:10px 14px;background:var(--dark);border-radius:8px;">
                    <span style="font-size:13px;color:var(--t2);">Role</span>
                    <span class="bg-b">{{ ucfirst(auth()->user()->role ?? 'Cashier') }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:10px 14px;background:var(--dark);border-radius:8px;">
                    <span style="font-size:13px;color:var(--t2);">Member Since</span>
                    <span style="font-size:13px;font-weight:600;">{{ auth()->user()->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
