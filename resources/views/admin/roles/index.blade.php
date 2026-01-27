@extends('layouts.admin')

@section('title', 'จัดการ Role - Admin Console')

@section('content')
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <div>
                <h2 class="text-lg font-bold text-gray-800">จัดการ Role</h2>
                <p class="text-sm text-gray-500">กำหนดบทบาทและสิทธิ์การเข้าถึง</p>
            </div>
            @if(auth()->user()->hasPermission('manage_roles'))
            <div x-data="{ open: false }">
                <button @click="open = true" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 flex items-center gap-2 shadow-lg shadow-purple-500/30 transition-all transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>สร้าง Role</span>
                </button>
                <!-- Create Role Modal -->
                <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm" x-cloak>
                    <div class="bg-white rounded-2xl p-8 max-w-lg w-full mx-4 shadow-2xl transform transition-all" @click.away="open = false" x-transition.scale.origin.center>
                        <h3 class="text-xl font-bold text-gray-900 mb-6">สร้าง Role ใหม่</h3>
                        <form action="{{ route('admin.roles.store') }}" method="POST" class="space-y-5" @submit="loading = true">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อ Role</label>
                                <input type="text" name="name" required placeholder="เช่น Manager" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">กำหนดสิทธิ์ (Permissions)</label>
                                <div class="grid grid-cols-2 gap-3 max-h-60 overflow-y-auto p-3 border border-gray-200 rounded-lg bg-gray-50">
                                    @foreach($allPermissions as $key => $label)
                                    <label class="inline-flex items-center space-x-2 p-2 rounded hover:bg-white transition-colors cursor-pointer">
                                        <input type="checkbox" name="permissions[]" value="{{ $key }}" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500 w-4 h-4">
                                        <span class="text-sm text-gray-700">{{ $label }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="flex justify-end space-x-3 pt-4">
                                <button type="button" @click="open = false" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 font-medium transition-colors">ยกเลิก</button>
                                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium shadow-md transition-colors">สร้าง Role</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                        <th class="px-6 py-4 font-semibold">Role Name</th>
                        <th class="px-6 py-4 font-semibold">Permissions</th>
                        <th class="px-6 py-4 font-semibold text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($roles as $role)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $role->name }}</div>
                            <div class="text-xs text-gray-400">{{ $role->slug }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1.5">
                                @if(in_array('*', $role->permissions ?? []))
                                    <span class="px-2.5 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full border border-red-200">All Access</span>
                                @else
                                    @foreach($role->permissions ?? [] as $perm)
                                        <span class="px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-full border border-blue-100">{{ $perm }}</span>
                                    @endforeach
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if(auth()->user()->hasPermission('manage_roles'))
                                    <!-- Edit Role -->
                                    <div x-data="{ 
                                        open: false, 
                                        roleId: '{{ $role->id }}', 
                                        roleName: @js($role->name), 
                                        roleSlug: @js($role->slug),
                                        rolePerms: @js($role->permissions ?? [])
                                    }">
                                        <button @click="open = true" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="แก้ไข">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        
                                        <!-- Edit Modal -->
                                        <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm text-left" x-cloak>
                                            <div class="bg-white rounded-2xl p-8 max-w-lg w-full mx-4 shadow-2xl transform transition-all" @click.away="open = false" x-transition.scale.origin.center>
                                                <h3 class="text-xl font-bold text-gray-900 mb-6">แก้ไข Role: <span x-text="roleName" class="text-indigo-600"></span></h3>
                                                <form :action="'{{ route('admin.roles.update', ['role' => 0]) }}'.replace('/0', '/' + roleId)" method="POST" class="space-y-5" @submit="loading = true">
                                                    @csrf @method('PATCH')
                                                    
                                                    <div x-show="['super-admin', 'admin', 'user'].includes(roleSlug)">
                                                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 flex items-start gap-3">
                                                            <svg class="w-5 h-5 text-amber-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                            <p class="text-sm text-amber-700">Role เริ่มต้นของระบบไม่สามารถเปลี่ยนชื่อได้</p>
                                                        </div>
                                                    </div>
                                                    <div x-show="!['super-admin', 'admin', 'user'].includes(roleSlug)">
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อ Role</label>
                                                        <input type="text" name="name" x-model="roleName" required class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                                    </div>

                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-3">กำหนดสิทธิ์ (Permissions)</label>
                                                        <div class="grid grid-cols-2 gap-3 max-h-60 overflow-y-auto p-3 border border-gray-200 rounded-lg bg-gray-50">
                                                            @foreach($allPermissions as $key => $label)
                                                            <label class="inline-flex items-center space-x-2 p-2 rounded hover:bg-white transition-colors cursor-pointer">
                                                                <input type="checkbox" name="permissions[]" value="{{ $key }}" 
                                                                       :checked="rolePerms.includes('*') || rolePerms.includes('{{ $key }}')"
                                                                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4">
                                                                <span class="text-sm text-gray-700">{{ $label }}</span>
                                                            </label>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="flex justify-end space-x-3 pt-4">
                                                        <button type="button" @click="open = false" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 font-medium transition-colors">ยกเลิก</button>
                                                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium shadow-md transition-colors">บันทึกการแก้ไข</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    @if(!in_array($role->slug, ['super-admin', 'admin', 'user']))
                                    <form action="{{ route('admin.roles.delete', $role) }}" method="POST" class="inline-block" onsubmit="return confirm('ยืนยันลบ Role นี้?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="ลบ">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
