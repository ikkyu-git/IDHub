@extends('layouts.admin')

@section('title', 'Settings - Admin Console')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- General Settings -->
        <div class="lg:col-span-2 space-y-6">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <!-- General Info -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <h2 class="text-lg font-bold text-gray-800">General Settings</h2>
                        <p class="text-sm text-gray-500">ตั้งค่าทั่วไปของระบบ</p>
                    </div>
                    <div class="p-6 space-y-6">
                        @include('partials.alerts')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Site Name</label>
                                <input type="text" name="site_name" value="{{ $settings['site_name'] ?? config('app.name') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Support Email</label>
                                <input type="email" name="support_email" value="{{ $settings['support_email'] ?? '' }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                            </div>
                            
                            <!-- Site Icon -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Site Icon / Logo</label>
                                <div class="flex items-center gap-4">
                                    @if(isset($settings['site_icon']))
                                        <img src="{{ $settings['site_icon'] }}" alt="Site Icon" class="h-12 w-12 rounded-lg object-cover border border-gray-200">
                                    @endif
                                    <input type="file" name="site_icon" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">แนะนำขนาด 512x512px (PNG/JPG)</p>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Announcement Message</label>
                                <textarea name="announcement" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">{{ $settings['announcement'] ?? '' }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">ข้อความประกาศที่จะแสดงหน้า Login (รองรับ HTML)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security & User Policy -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <h2 class="text-lg font-bold text-gray-800">นโยบายความปลอดภัยและการใช้งาน</h2>
                        <p class="text-sm text-gray-500">กำหนดนโยบายความปลอดภัยของระบบและสิทธิ์ที่ผู้ใช้สามารถแก้ไขได้</p>
                    </div>
                    @php
                        $editableFields = [];
                        if (!empty($settings['user_editable_fields'])) {
                            try {
                                $editableFields = json_decode($settings['user_editable_fields'], true) ?? [];
                            } catch (\Throwable $e) {
                                $editableFields = [];
                            }
                        }
                    @endphp
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">ระยะเวลาหมดอายุรหัสผ่าน (วัน)</label>
                                <input type="number" name="password_expiry_days" value="{{ $settings['password_expiry_days'] ?? 90 }}" min="0" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                                <p class="text-xs text-gray-500 mt-1">ตั้งค่าเป็น 0 หากไม่ต้องการให้รหัสผ่านหมดอายุ</p>
                            </div>

                            <div class="space-y-4 pt-2">
                                <label class="flex items-center p-3 rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="checkbox" name="allow_registration" value="1" {{ ($settings['allow_registration'] ?? true) ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                                    <div class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900">อนุญาตให้สมัครสมาชิกสาธารณะ</span>
                                        <span class="block text-xs text-gray-500">เปิดให้ผู้ใช้ภายนอกสามารถลงทะเบียนด้วยตนเอง</span>
                                    </div>
                                </label>

                                <label class="flex items-center p-3 rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="checkbox" name="force_2fa" value="1" {{ ($settings['force_2fa'] ?? false) ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                                    <div class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900">บังคับใช้ 2FA สำหรับผู้ดูแลระบบ</span>
                                        <span class="block text-xs text-gray-500">หากเลือก จะต้องตั้งค่า 2FA ก่อนผู้ดูแลจะใช้งานระบบ</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">ฟิลด์ที่อนุญาตให้ผู้ใช้แก้ไขได้</label>
                            <p class="text-xs text-gray-500 mb-3">เลือกรายการที่ผู้ใช้ทั่วไปสามารถแก้ไขได้จากหน้าโปรไฟล์</p>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach(['name' => 'ชื่อ', 'email' => 'อีเมล', 'avatar' => 'รูปโปรไฟล์', 'password' => 'รหัสผ่าน'] as $key => $label)
                                    <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" name="editable_fields[]" value="{{ $key }}" {{ in_array($key, $editableFields) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                                        <span class="text-sm text-gray-700">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Login Settings -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <h2 class="text-lg font-bold text-gray-800">Social Login Settings</h2>
                        <p class="text-sm text-gray-500">ตั้งค่าการเข้าสู่ระบบผ่านโซเชียลมีเดีย</p>
                    </div>
                    <div class="p-6 space-y-8">
                        @foreach(['google' => 'Google', 'facebook' => 'Facebook', 'line' => 'LINE', 'github' => 'GitHub'] as $provider => $label)
                        <div x-data="{ enabled: {{ ($settings["social_login_{$provider}_enable"] ?? false) ? 'true' : 'false' }} }" class="border-b border-gray-100 last:border-0 pb-6 last:pb-0">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <!-- Icon Placeholder -->
                                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-xs">
                                        {{ substr($label, 0, 1) }}
                                    </div>
                                    <h3 class="font-bold text-gray-800">{{ $label }} Login</h3>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="social_login_{{ $provider }}_enable" value="1" x-model="enabled" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                </label>
                            </div>
                            
                            <div x-show="enabled" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-4 pl-11">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Client ID / Channel ID</label>
                                    <input type="text" name="social_login_{{ $provider }}_client_id" value="{{ $settings["social_login_{$provider}_client_id"] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Client Secret / Channel Secret</label>
                                    <input type="password" name="social_login_{{ $provider }}_client_secret" value="{{ $settings["social_login_{$provider}_client_secret"] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Callback URL (Copy to Provider)</label>
                                    <div class="flex items-center gap-2">
                                        <code class="flex-1 bg-gray-50 px-3 py-2 rounded-lg border border-gray-200 text-xs text-gray-600 font-mono select-all">
                                            {{ route('social.callback', $provider) }}
                                        </code>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end pt-4 pb-12">
                    <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        บันทึกการตั้งค่าทั้งหมด
                    </button>
                </div>
            </form>
        </div>

        <!-- System Status / Info -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h2 class="text-lg font-bold text-gray-800">System Info</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-sm text-gray-500">Laravel Version</span>
                        <span class="text-sm font-mono font-medium text-gray-800">{{ app()->version() }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-sm text-gray-500">PHP Version</span>
                        <span class="text-sm font-mono font-medium text-gray-800">{{ phpversion() }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-sm text-gray-500">Environment</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ app()->environment() }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-sm text-gray-500">Debug Mode</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ config('app.debug') ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ config('app.debug') ? 'Enabled' : 'Disabled' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl shadow-lg text-white p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-10 rounded-full blur-xl"></div>
                <h3 class="text-lg font-bold mb-2 relative z-10">Need Help?</h3>
                <p class="text-indigo-100 text-sm mb-4 relative z-10">Check the documentation for advanced configuration options.</p>
                <a href="#" class="inline-block px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-lg text-sm font-medium transition-colors relative z-10">
                    View Documentation
                </a>
            </div>
        </div>
    </div>
@endsection
