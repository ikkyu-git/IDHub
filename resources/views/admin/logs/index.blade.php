@extends('layouts.admin')

@section('title', 'Audit Logs - Admin Console')

@section('content')
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-bold text-gray-800">Audit Logs</h2>
                <p class="text-sm text-gray-500">ประวัติการใช้งานระบบ</p>
            </div>
            @if(auth()->user()->hasPermission('manage_roles'))
            <form action="{{ route('admin.logs.clear') }}" method="POST" onsubmit="return confirm('ยืนยันที่จะล้างประวัติทั้งหมด? การกระทำนี้ไม่สามารถย้อนกลับได้');">
                @csrf @method('DELETE')
                <button type="submit" class="px-3 py-1.5 text-sm text-red-600 hover:bg-red-50 border border-red-200 rounded-lg transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    ล้างประวัติ
                </button>
            </form>
            @endif
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                        <th class="px-6 py-4 font-semibold">เวลา</th>
                        <th class="px-6 py-4 font-semibold">ผู้ใช้</th>
                        <th class="px-6 py-4 font-semibold">การกระทำ</th>
                        <th class="px-6 py-4 font-semibold">รายละเอียด</th>
                        <th class="px-6 py-4 font-semibold">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50/80 transition-colors text-sm">
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap font-mono text-xs">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $log->user->name ?? 'Unknown' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $log->action == 'create' ? 'bg-green-100 text-green-800' : 
                                  ($log->action == 'delete' ? 'bg-red-100 text-red-800' : 
                                  ($log->action == 'login' ? 'bg-indigo-100 text-indigo-800' : 'bg-blue-100 text-blue-800')) }}">
                                {{ strtoupper($log->action) }}
                            </span>
                            <div class="text-xs text-gray-400 mt-1">{{ class_basename($log->model_type) }} #{{ Str::limit($log->model_id, 8) }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-600 max-w-xs truncate font-mono text-xs" title="{{ json_encode($log->details) }}">
                            {{ Str::limit(json_encode($log->details), 60) }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs">{{ $log->ip_address }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">ไม่พบประวัติการใช้งาน</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $logs->links() }}
        </div>
    </div>
@endsection
