@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')
@section('page-subtitle', 'Kelola informasi profil dan keamanan akun Anda')

@section('sidebar-nav')
    <a href="{{ route('user.dashboard') }}" class="sidebar-link">📊 Dashboard</a>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider px-4 pt-4 pb-1">Donasi</p>
    <a href="{{ route('user.catalog.index') }}" class="sidebar-link">🛒 Katalog Donasi</a>
    <a href="{{ route('user.requests.index') }}" class="sidebar-link">📋 Permintaan Saya</a>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow-sm border border-slate-100 sm:rounded-2xl">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-white shadow-sm border border-slate-100 sm:rounded-2xl">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-white shadow-sm border border-slate-100 sm:rounded-2xl">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection
