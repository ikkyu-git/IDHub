@extends('layouts.admin')

@section('title', 'จัดการผู้ใช้ - Admin Console')

@section('content')
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-gray-50/50">
            <div>
                <h2 class="text-lg font-bold text-gray-800">รายชื่อผู้ใช้</h2>
                <p class="text-sm text-gray-500">จัดการบัญชีผู้ใช้และกำหนดสิทธิ์</p>
            </div>
            <div class="flex gap-3 w-full md:w-auto">
                <!-- Export Button -->
                <a href="{{ route('admin.users.export') }}" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 flex items-center gap-2 shadow-lg shadow-emerald-500/30 transition-all transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <span>Export CSV</span>
                </a>

                @if(auth()->user()->hasPermission('create_users'))
                <div x-data="{ open: false }">
                    <button @click="open = true" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 flex items-center gap-2 shadow-lg shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span>เพิ่มผู้ใช้</span>
                    </button>
                    <!-- Add User Modal -->
                    <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm" x-cloak>
                        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl transform transition-all" @click.away="open = false" x-transition.scale.origin.center>
                            <h3 class="text-xl font-bold text-gray-900 mb-6">เพิ่มผู้ใช้ใหม่</h3>
                            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-5" @submit="loading = true">
                                @csrf
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อ (First name)</label>
                                        <input type="text" name="first_name" required class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">นามสกุล (Last name)</label>
                                        <input type="text" name="last_name" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Username (ไม่จำเป็น)</label>
                                    <input type="text" name="username" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">อีเมล</label>
                                    <input type="email" name="email" required class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">รหัสผ่าน</label>
                                    <input type="password" name="password" required minlength="8" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                                    <select name="role_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex justify-end space-x-3 pt-4">
                                    <button type="button" @click="open = false" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 font-medium transition-colors">ยกเลิก</button>
                                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium shadow-md transition-colors">บันทึก</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
                
                <form action="{{ route('admin.users') }}" method="GET" class="relative flex-1 md:flex-none">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหา..." class="w-full md:w-64 pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition-all">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </form>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                        <th class="px-6 py-4 font-semibold">ชื่อผู้ใช้</th>
                        <th class="px-6 py-4 font-semibold">อีเมล</th>
                        <th class="px-6 py-4 font-semibold">Role</th>
                        <th class="px-6 py-4 font-semibold">สถานะ</th>
                        <th class="px-6 py-4 font-semibold text-right">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50/80 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($user->avatar_url)
                                    <img src="{{ $user->avatar_url }}" alt="{{ $user->full_name }}" class="h-8 w-8 rounded-full object-cover mr-3 border border-gray-200">
                                @else
                                    <div class="h-8 w-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold mr-3">
                                        {{ substr($user->full_name, 0, 1) }}
                                    </div>
                                @endif
                                <span class="font-medium text-gray-900">{{ $user->full_name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-sm">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            @if(auth()->user()->hasPermission('edit_users'))
                            <form action="{{ route('admin.users.role', $user) }}" method="POST" class="inline-block" @submit="loading = true">
                                @csrf @method('PATCH')
                                <select name="role_id" onchange="this.form.submit()" class="text-sm border-gray-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-1 pl-2 pr-8 bg-white cursor-pointer hover:border-indigo-300 transition-colors">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ $user->roles->contains('id', $role->id) ? 'selected' : '' }}>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </form>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $user->roles->pluck('name')->join(', ') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if(auth()->user()->hasPermission('edit_users') && $user->id !== auth()->id())
                                <form action="{{ route('admin.users.toggle_status', $user) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 {{ $user->is_active ? 'bg-green-500' : 'bg-gray-200' }}" title="{{ $user->is_active ? 'คลิกเพื่อระงับการใช้งาน' : 'คลิกเพื่อเปิดใช้งาน' }}">
                                        <span aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $user->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                    </button>
                                </form>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->is_active ? 'Active' : 'Suspended' }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if(auth()->user()->hasPermission('edit_users'))
                            
                            <!-- Impersonate Button (Login as User) -->
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.impersonate', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('ต้องการเข้าสู่ระบบในชื่อ {{ $user->name }} หรือไม่?');">
                                @csrf
                                <button type="submit" class="p-2 text-gray-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-all" title="เข้าสู่ระบบเป็นผู้ใช้นี้">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                            </form>
                            @endif

                            <!-- Quick Reset -->
                            <form action="{{ route('admin.users.quick_reset', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('ยืนยันรีเซ็ตรหัสผ่านของ {{ $user->name }} เป็นรหัสสุ่ม 8 หลัก?');">
                                @csrf
                                <button type="submit" class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all" title="รีเซ็ตรหัสผ่านด่วน">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                </button>
                            </form>

                            <!-- Reset 2FA -->
                            @if(auth()->user()->hasPermission('edit_users'))
                            <form action="{{ route('admin.users.reset_2fa', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('ยืนยันรีเซ็ตการตั้งค่า 2FA ของผู้ใช้ {{ $user->name }}? ผู้ใช้จะต้องตั้งค่า 2FA ใหม่อีกครั้ง.');">
                                @csrf
                                <button type="submit" class="p-2 text-gray-400 hover:text-pink-600 hover:bg-pink-50 rounded-lg transition-all" title="รีเซ็ต 2FA">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </button>
                            </form>
                            @endif

                            <!-- Email Verified Toggle -->
                            @if(auth()->user()->hasPermission('edit_users'))
                            <form action="{{ route('admin.users.toggle_verified', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('ยืนยันการเปลี่ยนสถานะการยืนยันอีเมลสำหรับ {{ $user->name }} ?');">
                                @csrf
                                <button type="submit" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Toggle Email Verified">
                                    @if($user->email_verified_at)
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path></svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"></path></svg>
                                    @endif
                                </button>
                            </form>
                            @endif

                            <!-- Manage Sessions -->
                            <div x-data="{ 
                                open: false, 
                                userId: '{{ $user->id }}', 
                                userName: @js($user->name),
                                sessions: [],
                                loading: false,
                                fetchSessions() {
                                    this.loading = true;
                                    this.open = true;
                                    fetch('{{ route('admin.users.sessions', ['user' => 0]) }}'.replace('/0', '/' + this.userId))
                                        .then(res => res.json())
                                        .then(data => {
                                            this.sessions = data;
                                            this.loading = false;
                                        });
                                }
                            }" class="inline-block">
                                <button @click="fetchSessions()" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="จัดการ Session">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </button>
                                
                                <!-- Sessions Modal -->
                                <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm text-left" x-cloak>
                                    <div class="bg-white rounded-2xl p-8 max-w-2xl w-full mx-4 shadow-2xl transform transition-all" @click.away="open = false" x-transition.scale.origin.center>
                                        <div class="flex justify-between items-center mb-6">
                                            <h3 class="text-xl font-bold text-gray-900">Active Sessions: <span x-text="userName" class="text-blue-600"></span></h3>
                                            <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>
                                        
                                        <div x-show="loading" class="text-center py-8">
                                            <svg class="animate-spin h-8 w-8 text-blue-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <p class="mt-2 text-gray-500">กำลังโหลดข้อมูล...</p>
                                        </div>

                                        <div x-show="!loading && sessions.length === 0" class="text-center py-8 text-gray-500">
                                            ไม่พบ Session ที่ใช้งานอยู่
                                        </div>

                                        <div x-show="!loading && sessions.length > 0" class="space-y-3 max-h-[60vh] overflow-y-auto pr-2">
                                            <template x-for="session in sessions" :key="session.id">
                                                <div class="flex items-center justify-between p-4 rounded-lg border border-gray-200 bg-gray-50 hover:bg-white hover:shadow-sm transition-all">
                                                    <div class="flex items-center gap-4">
                                                        <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                        </div>
                                                        <div>
                                                            <div class="font-semibold text-gray-900" x-text="session.platform + ' - ' + session.browser"></div>
                                                            <div class="text-xs text-gray-500 mt-1">
                                                                <span x-text="session.ip_address"></span> &bull; 
                                                                <span x-text="session.last_active"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <form :action="'{{ route('admin.users.sessions.destroy', ['user' => 0, 'sessionId' => 1]) }}'.replace('/0', '/' + userId).replace('/1', '/' + session.id)" method="POST" onsubmit="return confirm('ยืนยันที่จะเตะ Session นี้ออกจากระบบ?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-md border border-red-200 transition-colors">
                                                            Force Logout
                                                        </button>
                                                    </form>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Custom Attributes Page -->
                            <a href="{{ route('admin.users.attributes.edit', $user) }}" class="inline-flex items-center justify-center h-9 w-9 text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-all" title="จัดการ Custom Attributes">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                            </a>

                            <!-- Edit User -->
                            <div x-data="{ 
                                open: false, 
                                userId: '{{ $user->id }}', 
                                userFirstName: @js($user->first_name),
                                userLastName: @js($user->last_name),
                                userUsername: @js($user->username),
                                userEmail: @js($user->email),
                                mustChangePassword: {{ $user->must_change_password ? 'true' : 'false' }}
                            }" class="inline-block">
                                <button @click="open = true" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="แก้ไขข้อมูล">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm text-left" x-cloak>
                                    <div class="bg-white rounded-2xl p-8 max-w-lg w-full mx-4 shadow-2xl transform transition-all max-h-[90vh] overflow-y-auto" @click.away="open = false" x-transition.scale.origin.center>
                                        <h3 class="text-xl font-bold text-gray-900 mb-6">แก้ไขผู้ใช้: <span x-text="(userFirstName + ' ' + (userLastName || '')).trim()" class="text-indigo-600"></span></h3>
                                        <form :action="'{{ route('admin.users.update', ['user' => 0]) }}'.replace('/0', '/' + userId)" method="POST" class="space-y-5" @submit="loading = true">
                                            @csrf @method('PATCH')
                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อ (First name)</label>
                                                    <input type="text" name="first_name" x-model="userFirstName" required class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">นามสกุล (Last name)</label>
                                                    <input type="text" name="last_name" x-model="userLastName" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                                                <input type="text" name="username" x-model="userUsername" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">อีเมล</label>
                                                <input type="email" name="email" x-model="userEmail" required class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                            </div>
                                            
                                            <div class="pt-2 border-t border-gray-100">
                                                <label class="flex items-center space-x-2 cursor-pointer">
                                                    <input type="checkbox" name="must_change_password" value="1" x-model="mustChangePassword" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4">
                                                    <span class="text-sm font-medium text-gray-700">บังคับเปลี่ยนรหัสผ่านเมื่อล็อกอินครั้งถัดไป</span>
                                                </label>
                                            </div>
                                            <div class="pt-2 border-t border-gray-100">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">รีเซ็ตรหัสผ่าน (เว้นว่างถ้าไม่เปลี่ยน)</label>
                                                <input type="password" name="password" minlength="8" placeholder="รหัสผ่านใหม่" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm bg-gray-50">
                                            </div>
                                            <div class="flex justify-end space-x-3 pt-4">
                                                <button type="button" @click="open = false" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 font-medium transition-colors">ยกเลิก</button>
                                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium shadow-md transition-colors">บันทึก</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if(auth()->user()->hasPermission('delete_users'))
                            <form action="{{ route('admin.users.delete', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('ยืนยันลบผู้ใช้นี้?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="ลบผู้ใช้">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
    </div>
@endsection
