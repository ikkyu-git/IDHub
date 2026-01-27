@extends('layouts.admin')

@section('title', 'Manage Attributes - ' . $user->name)

@section('content')
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <div class="flex items-center gap-4">
                @if($user->avatar_url)
                    <img src="{{ $user->avatar_url }}" class="w-12 h-12 rounded-full object-cover border-2 border-indigo-100">
                @else
                    <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-lg font-bold">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                @endif
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Custom Attributes</h2>
                    <p class="text-sm text-gray-500">จัดการข้อมูลเพิ่มเติมสำหรับ {{ $user->name }} ({{ $user->email }})</p>
                </div>
            </div>
            <a href="{{ route('admin.users') }}" class="text-gray-500 hover:text-gray-700 text-sm flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Users
            </a>
        </div>

        <div class="p-6">
            @if(session('alert'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('alert') }}
                </div>
            @endif

            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6">
                <h3 class="text-blue-800 font-semibold mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    คำแนะนำ
                </h3>
                <p class="text-sm text-blue-700">
                    ข้อมูลเหล่านี้จะถูกใช้เมื่อ Client ขอ Scope ที่มีชื่อตรงกับ Key <br>
                    เช่น ถ้ากำหนด Key = <code>department</code> และ Value = <code>IT</code> <br>
                    เมื่อ Client ขอ Scope <code>department</code> ระบบจะส่งค่า <code>IT</code> กลับไปให้
                </p>
            </div>

            <form action="{{ route('admin.users.attributes.update', $user->id) }}" method="POST" x-data="attributeManager({{ json_encode(collect($user->attributes ?? [])->map(fn($v, $k) => ['key' => $k, 'value' => $v])->values()) }})">
                @csrf
                
                <div class="space-y-4 mb-8">
                    <template x-for="(attr, index) in attributes" :key="index">
                        <div class="flex gap-4 items-start p-4 bg-gray-50 rounded-xl border border-gray-100 group hover:border-indigo-200 transition-colors">
                            <div class="flex-1">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Attribute Key</label>
                                <input type="text" name="keys[]" x-model="attr.key" placeholder="e.g., employee_id" class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all font-mono text-sm">
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Value</label>
                                <input type="text" name="values[]" x-model="attr.value" placeholder="Value" class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                            </div>
                            <button type="button" @click="remove(index)" class="mt-6 p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </template>
                    
                    <div x-show="attributes.length === 0" class="text-center py-8 text-gray-400 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                        ยังไม่มี Custom Attributes
                    </div>
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                    <button type="button" @click="add()" class="px-4 py-2 text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-xl font-medium transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Add Attribute
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-0.5">
                        Save Attributes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function attributeManager(initialData) {
            return {
                attributes: initialData.length ? initialData : [],
                add() {
                    this.attributes.push({ key: '', value: '' });
                },
                remove(index) {
                    this.attributes.splice(index, 1);
                }
            }
        }
    </script>
@endsection
