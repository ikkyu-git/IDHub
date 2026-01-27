@extends('layouts.admin')

@section('title', 'Dashboard - Admin Console')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-gray-500">ภาพรวมของระบบ</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 relative overflow-hidden group hover:shadow-md transition-shadow">
        <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-indigo-50 to-transparent opacity-50"></div>
        <div class="relative z-10">
            <div class="text-gray-500 text-sm font-medium mb-1">ผู้ใช้ทั้งหมด</div>
            <div class="text-3xl font-bold text-gray-800">{{ $stats['total_users'] }}</div>
            <div class="mt-2 text-xs text-indigo-600 font-medium flex items-center">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Active Users
            </div>
        </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 relative overflow-hidden group hover:shadow-md transition-shadow">
        <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-amber-50 to-transparent opacity-50"></div>
        <div class="relative z-10">
            <div class="text-gray-500 text-sm font-medium mb-1">ผู้ดูแลระบบ (Admin+)</div>
            <div class="text-3xl font-bold text-gray-800">{{ $stats['admins'] }}</div>
            <div class="mt-2 text-xs text-amber-600 font-medium flex items-center">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                Privileged Accounts
            </div>
        </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 relative overflow-hidden group hover:shadow-md transition-shadow">
        <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-purple-50 to-transparent opacity-50"></div>
        <div class="relative z-10">
            <div class="text-gray-500 text-sm font-medium mb-1">จำนวน Role</div>
            <div class="text-3xl font-bold text-gray-800">{{ $stats['roles_count'] }}</div>
            <div class="mt-2 text-xs text-purple-600 font-medium flex items-center">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                System Roles
            </div>
        </div>
        </div>
    </div>
@endsection
