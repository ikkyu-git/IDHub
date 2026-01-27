@extends('layouts.admin')

@section('title', 'ประกาศถาวร - Admin')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 bg-gray-50">
        <h2 class="text-lg font-bold text-gray-800">สร้างประกาศถาวร</h2>
        <p class="text-sm text-gray-500">สร้างประกาศหรือแจ้งเตือนให้ผู้ใช้หนึ่งคนหรือทุกคน</p>
    </div>

    <div class="p-6">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-medium mb-2">รายการประกาศ</h3>
            <a href="{{ route('admin.alerts.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">สร้างประกาศใหม่</a>
        </div>
    </div>

    <div class="p-6 border-t border-gray-100">
        <h3 class="text-lg font-medium mb-4">รายการประกาศ</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-xs text-gray-500 uppercase">
                        <th class="py-2">ID</th>
                        <th class="py-2">หัวเรื่อง</th>
                        <th class="py-2">ข้อความ</th>
                        <th class="py-2">ผู้รับ</th>
                        <th class="py-2">สถานะ</th>
                        <th class="py-2 text-right">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($alerts as $a)
                    <tr>
                        <td class="py-3 text-sm text-gray-600">{{ $a->id }}</td>
                        <td class="py-3 font-medium">{{ $a->title ?? '-' }}</td>
                        <td class="py-3 text-sm text-gray-700">{{ Str::limit($a->message, 140) }}</td>
                        <td class="py-3 text-sm text-gray-600">{{ $a->user_id ? ($a->user->name ?? $a->user_id) : 'ทุกคน' }}</td>
                        <td class="py-3 text-sm">
                            @if($a->is_resolved)
                                <span class="text-xs px-2 py-1 rounded bg-green-100 text-green-800">ดำเนินการแล้ว</span>
                            @else
                                <span class="text-xs px-2 py-1 rounded bg-yellow-100 text-yellow-800">ยังไม่ดำเนินการ</span>
                            @endif
                        </td>
                        <td class="py-3 text-right">
                            <form action="{{ route('admin.alerts.destroy', $a->id) }}" method="POST" class="inline-block" onsubmit="return confirm('ยืนยันการลบประกาศนี้?');">
                                @csrf @method('DELETE')
                                <button class="px-3 py-1 rounded bg-red-50 text-red-600 border border-red-100 hover:bg-red-100">ลบ</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $alerts->links() }}</div>
    </div>
</div>
@endsection
