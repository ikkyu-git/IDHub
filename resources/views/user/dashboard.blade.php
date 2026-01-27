<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings['site_name'] ?? 'User Dashboard' }}</title>
    @if(isset($settings['site_icon']))
        <link rel="icon" href="{{ $settings['site_icon'] }}">
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">

    <!-- Impersonation Banner -->
    @if(session('impersonator_id'))
    <div class="bg-amber-600 text-white px-4 py-3 text-center relative z-[60] shadow-md flex justify-center items-center gap-4">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
            <span class="font-medium">คุณกำลังใช้งานในชื่อ: <strong>{{ auth()->user()->name }}</strong></span>
        </div>
        <form action="{{ route('admin.users.stop_impersonate') }}" method="POST">
            @csrf
            <button type="submit" class="bg-white text-amber-700 px-3 py-1 rounded-md text-sm font-bold hover:bg-amber-50 transition-colors shadow-sm">
                ออกจากโหมดจำลอง
            </button>
        </form>
    </div>
    @endif

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="shrink-0 flex items-center">
                        <span class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600">
                            {{ $settings['site_name'] ?? 'MyAccount' }}
                        </span>
                    </div>
                    
                    <!-- Authorized Apps Link -->
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <a href="{{ route('user.apps.list') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Authorized Apps
                        </a>
                    </div>

                    <!-- Admin Console Link (Visible only to Admins) -->
                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin'))
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Admin Console
                        </a>
                    </div>
                    @endif
                </div>
                <div class="flex items-center gap-4">
                    <div class="hidden md:flex flex-col items-end mr-2">
                        <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                        <span class="text-xs text-gray-500">{{ auth()->user()->email }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                            ออกจากระบบ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        
        <!-- System Announcement -->
        @if(!empty($settings['announcement']))
        <div class="mb-8 bg-indigo-50 border-l-4 border-indigo-500 p-4 rounded-r-lg shadow-sm animate-fade-in-down">
            <div class="flex">
                <div class="shrink-0">
                    <svg class="h-5 w-5 text-indigo-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-indigo-700">
                        <span class="font-bold">ประกาศ:</span> {{ $settings['announcement'] }}
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">ตั้งค่าบัญชีผู้ใช้</h1>
            <p class="mt-2 text-sm text-gray-600">จัดการข้อมูลส่วนตัวและรหัสผ่านของคุณ</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Profile Card -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
                    <div class="p-6 text-center">
                        <div class="relative inline-block mb-4 group">
                            @if(auth()->user()->avatar_url)
                                <img class="h-32 w-32 object-cover rounded-full border-4 border-white shadow-lg" src="{{ auth()->user()->avatar_url }}" alt="Avatar">
                            @else
                                <div class="h-32 w-32 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-4xl border-4 border-white shadow-lg">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="absolute bottom-0 right-0 bg-green-500 h-5 w-5 rounded-full border-2 border-white"></div>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">{{ auth()->user()->name }}</h2>
                        <p class="text-sm text-gray-500 mb-4">{{ auth()->user()->email }}</p>
                        
                        <div class="flex flex-wrap justify-center gap-2 mb-4">
                            @foreach(auth()->user()->roles as $role)
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    {{ $role->name }}
                                </span>
                            @endforeach
                        </div>
                        
                        <div class="border-t border-gray-100 pt-4 mt-4 text-left">
                            <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                                <span>เข้าร่วมเมื่อ</span>
                                <span class="font-medium">{{ auth()->user()->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm text-gray-600">
                                <span>สถานะ</span>
                                <span class="text-green-600 font-medium flex items-center">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span> ปกติ
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Menu -->
                <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">ความปลอดภัย</h3>
                        <div class="space-y-3">
                            <a href="{{ route('user.2fa.show') }}" class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-indigo-50 transition-colors group">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-white rounded-md shadow-sm text-indigo-600 group-hover:text-indigo-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">Two-Factor Auth</div>
                                        <div class="text-xs text-gray-500">
                                            @if(auth()->user()->two_factor_confirmed_at)
                                                <span class="text-green-600">เปิดใช้งานแล้ว</span>
                                            @else
                                                <span class="text-gray-400">ยังไม่เปิดใช้งาน</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>

                            <a href="{{ route('user.apps.list') }}" class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-indigo-50 transition-colors group">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-white rounded-md shadow-sm text-indigo-600 group-hover:text-indigo-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">Authorized Apps</div>
                                        <div class="text-xs text-gray-500">จัดการแอปที่เชื่อมต่อ</div>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>

                            <a href="{{ route('user.sessions') }}" class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-indigo-50 transition-colors group">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-white rounded-md shadow-sm text-indigo-600 group-hover:text-indigo-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">Active Sessions</div>
                                        <div class="text-xs text-gray-500">จัดการอุปกรณ์ที่เข้าสู่ระบบ</div>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Edit Form -->
            <div class="lg:col-span-2">
                
                <!-- Alerts -->
                @if(session('alert'))
                    <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 px-4 py-3 shadow-sm flex items-center gap-3" x-data="{ show: true }" x-show="show">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="flex-1">{{ session('alert') }}</span>
                        <button @click="show = false" class="text-emerald-600 hover:text-emerald-800">&times;</button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 text-red-800 px-4 py-3 shadow-sm">
                        <div class="font-medium mb-2">พบข้อผิดพลาด:</div>
                        <ul class="list-disc list-inside text-sm space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
                    <div class="p-6 md:p-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            แก้ไขข้อมูลส่วนตัว
                        </h3>

                        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            
                            @php
                                $editable = json_decode($settings['user_editable_fields'] ?? '["name","email","password","avatar"]', true);
                            @endphp

                            <!-- Avatar Upload -->
                            @if(in_array('avatar', $editable))
                            <div x-data="{ photoName: null, photoPreview: null }" class="col-span-6 sm:col-span-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">รูปโปรไฟล์</label>
                                <input type="file" name="avatar" class="hidden" x-ref="photo" x-on:change="
                                        photoName = $refs.photo.files[0].name;
                                        const reader = new FileReader();
                                        reader.onload = (e) => {
                                            photoPreview = e.target.result;
                                        };
                                        reader.readAsDataURL($refs.photo.files[0]);
                                ">

                                <div class="flex items-center gap-4">
                                    <!-- Current Profile Photo -->
                                    <div class="mt-2" x-show="! photoPreview">
                                        @if(auth()->user()->avatar_url)
                                            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="rounded-full h-20 w-20 object-cover border border-gray-200">
                                        @else
                                            <div class="rounded-full h-20 w-20 bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-2xl border border-indigo-200">
                                                {{ substr(auth()->user()->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>

                                    <!-- New Profile Photo Preview -->
                                    <div class="mt-2" x-show="photoPreview" style="display: none;">
                                        <span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center border border-gray-200"
                                              x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                                        </span>
                                    </div>

                                    <button type="button" class="bg-white py-2 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors" x-on:click.prevent="$refs.photo.click()">
                                        เลือกรูปภาพใหม่
                                    </button>
                                </div>
                            </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">ชื่อ-นามสกุล</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" 
                                           @if(!in_array('name', $editable)) disabled class="w-full rounded-lg border-gray-200 bg-gray-100 text-gray-500 cursor-not-allowed" @else required class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors" @endif>
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">อีเมล</label>
                                    <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" 
                                           @if(!in_array('email', $editable)) disabled class="w-full rounded-lg border-gray-200 bg-gray-100 text-gray-500 cursor-not-allowed" @else required class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors" @endif>
                                </div>
                            </div>

                            @if(in_array('password', $editable))
                            <div class="border-t border-gray-100 pt-6 mt-6">
                                <h4 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    เปลี่ยนรหัสผ่าน (เว้นว่างหากไม่ต้องการเปลี่ยน)
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">รหัสผ่านใหม่</label>
                                        <input type="password" name="password" id="password" minlength="8" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors" placeholder="••••••••">
                                    </div>
                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">ยืนยันรหัสผ่าน</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" minlength="8" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors" placeholder="••••••••">
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="flex items-center justify-end pt-4 border-t border-gray-100">
                                <button type="submit" class="inline-flex justify-center py-2.5 px-6 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:-translate-y-0.5">
                                    บันทึกการเปลี่ยนแปลง
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Login History -->
                <div class="mt-8 bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                ประวัติการเข้าใช้งานล่าสุด
                            </h3>
                            <a href="{{ route('user.login-history') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">ดูทั้งหมด &rarr;</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">กิจกรรม</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">เวลา</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($loginActivities as $activity)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                            @if($activity->action == 'login_success')
                                                <span class="text-green-600 flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span> เข้าสู่ระบบสำเร็จ</span>
                                            @elseif($activity->action == 'login_failed')
                                                <span class="text-red-600 flex items-center"><span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span> เข้าสู่ระบบล้มเหลว</span>
                                            @else
                                                <span class="text-gray-600">{{ $activity->action }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                            {{ $activity->created_at->format('d M Y H:i') }}
                                            <span class="text-xs text-gray-400">({{ $activity->created_at->diffForHumans() }})</span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 font-mono">
                                            {{ $activity->ip_address }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-3 text-center text-sm text-gray-500">ไม่พบประวัติการใช้งาน</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
