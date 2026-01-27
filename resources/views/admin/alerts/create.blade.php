@extends('layouts.admin')

@section('title', 'สร้างประกาศถาวร - Admin')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 bg-gray-50">
        <h2 class="text-lg font-bold text-gray-800">สร้างประกาศถาวร</h2>
        <p class="text-sm text-gray-500">สร้างประกาศหรือแจ้งเตือนให้ผู้ใช้หนึ่งคนหรือทุกคน</p>
    </div>

    <div class="p-6">
        <form action="{{ route('admin.alerts.store') }}" method="POST" class="space-y-4 max-w-2xl">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">หัวเรื่อง (ไม่บังคับ)</label>
                <input name="title" value="{{ old('title') }}" class="w-full rounded border-gray-200 p-2" />
                @error('title') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">ข้อความ</label>
                <textarea name="message" required class="w-full rounded border-gray-200 p-2" rows="6">{{ old('message') }}</textarea>
                @error('message') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">ส่งให้ผู้ใช้ (ว่าง = ทุกคน)</label>
                <select name="user_id" class="w-full rounded border-gray-200 p-2">
                    <option value="">ทุกคน</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
                <div class="mt-2">
                    <label class="block text-sm font-medium mb-1">หรือส่งให้ Role (ว่าง = ไม่ระบุ)</label>
                    <select name="role_id" class="w-full rounded border-gray-200 p-2">
                        <option value="">ไม่ระบุ</option>
                        @foreach($roles as $r)
                            <option value="{{ $r->id }}" {{ old('role_id') == $r->id ? 'selected' : '' }}>{{ $r->name }} ({{ $r->slug }})</option>
                        @endforeach
                    </select>
                </div>
                @error('user_id') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                @error('role_id') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">สร้าง</button>
                <a href="{{ route('admin.alerts.index') }}" class="px-4 py-2 bg-gray-100 rounded">ยกเลิก</a>
            </div>
        </form>
    </div>
</div>
@endsection
