@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div style="max-width: 500px; margin: 100px auto; padding: 40px; background: linear-gradient(135deg, #1a1a3e, #0f1a2e); border-radius: 12px; border: 1px solid rgba(233, 75, 60, 0.2);">

    <h2 style="text-align: center; background: linear-gradient(135deg, #e94b3c, #00d4d4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: 30px;">Buat Akun Baru</h2>

    @if ($errors->any())
        <div style="background: rgba(233, 75, 60, 0.2); border-left: 4px solid #e94b3c; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
            @foreach ($errors->all() as $error)
                <p style="color: #ff6b6b; margin: 5px 0;">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('register.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 20px;">
        @csrf

        <div>
            <label for="name" style="display: block; margin-bottom: 8px; font-weight: bold;">Nama Lengkap</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required style="width: 100%; padding: 12px; background: rgba(0, 212, 212, 0.1); border: 1px solid #00d4d4; border-radius: 6px; color: #e5e5e5;">
        </div>

        <div>
            <label for="email" style="display: block; margin-bottom: 8px; font-weight: bold;">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required style="width: 100%; padding: 12px; background: rgba(0, 212, 212, 0.1); border: 1px solid #00d4d4; border-radius: 6px; color: #e5e5e5;">
        </div>

        <div>
            <label for="password" style="display: block; margin-bottom: 8px; font-weight: bold;">Password</label>
            <input type="password" id="password" name="password" required style="width: 100%; padding: 12px; background: rgba(0, 212, 212, 0.1); border: 1px solid #00d4d4; border-radius: 6px; color: #e5e5e5;">
        </div>

        <div>
            <label for="password_confirmation" style="display: block; margin-bottom: 8px; font-weight: bold;">Konfirmasi Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required style="width: 100%; padding: 12px; background: rgba(0, 212, 212, 0.1); border: 1px solid #00d4d4; border-radius: 6px; color: #e5e5e5;">
        </div>

        <button type="submit" style="padding: 12px; background: linear-gradient(135deg, #e94b3c, #d63a2a); color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; transition: all 0.3s;">
            Daftar
        </button>

        <div style="text-align: center; color: #e5e5e5; font-size: 0.9em; opacity: 0.8; margin-top: -10px;">
            atau
        </div>

        <a href="{{ route('google.login') }}" style="display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; padding: 12px; background: white; border: none; border-radius: 6px; color: #333; font-weight: bold; text-decoration: none; box-sizing: border-box; transition: all 0.3s;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 48 48">
                <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/>
                <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/>
                <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/>
                <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/>
            </svg>
            Daftar dengan Google
        </a>

    </form>

    <p style="text-align: center; margin-top: 20px; color: #b0b0b0;">
        Sudah punya akun? <a href="{{ route('login') }}" style="color: #00d4d4; text-decoration: none; font-weight: bold;">Login di sini</a>
    </p>
</div>
@endsection
