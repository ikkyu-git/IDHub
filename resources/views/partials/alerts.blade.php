@php
    $types = [
        'alert' => 'bg-blue-50 border-blue-200 text-blue-700',
        'success' => 'bg-green-50 border-green-200 text-green-700',
        'error' => 'bg-red-50 border-red-200 text-red-700',
        'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
        'info' => 'bg-indigo-50 border-indigo-200 text-indigo-700',
    ];

    // Normalize session keys
    $flash = session('alert') ?? session('message') ?? null;
    $success = session('success') ?? null;
    $errorsBag = $errors->any() ? $errors->all() : null;
    $warning = session('warning') ?? session('alert_warning') ?? null;

    // Persistent DB alerts for authenticated user (only if table exists)
    $persistentAlerts = [];
    if (auth()->check() && \Illuminate\Support\Facades\Schema::hasTable('persistent_alerts')) {
        try {
            $user = auth()->user();
            $roleIds = $user->roles->pluck('id')->toArray();

            $persistentAlerts = \App\Models\PersistentAlert::where('is_resolved', false)
                ->where(function($q) use ($roleIds) {
                    $q->where('user_id', auth()->id())
                      ->orWhere(function($q2) use ($roleIds) {
                          $q2->whereNull('user_id')
                             ->where(function($q3) use ($roleIds) {
                                 $q3->whereNull('role_id')
                                    ->orWhereIn('role_id', $roleIds ?: [0]);
                             });
                      });
                })->orderByDesc('created_at')->get();
        } catch (\Throwable $e) {
            // If DB/migration isn't available or another error occurs, silently skip persistent alerts
            $persistentAlerts = [];
        }
    }
@endphp

<div x-data="{}">
    {{-- session flashes (auto-dismiss) --}}
    <div x-show="true" x-data="{show:true}" x-init="setTimeout(()=> show = false, 8000)">
        @if($flash)
            <div class="mb-4 p-4 rounded-lg border {{ $types['alert'] }} flex items-start justify-between">
                <div class="text-sm">{!! e($flash) !!}</div>
                <button @click="$el.parentElement.style.display='none'" class="ml-4 text-sm font-medium text-current">ปิด</button>
            </div>
        @endif

        @if($success)
            <div class="mb-4 p-4 rounded-lg border {{ $types['success'] }} flex items-start justify-between">
                <div class="text-sm">{!! e($success) !!}</div>
                <button @click="$el.parentElement.style.display='none'" class="ml-4 text-sm font-medium text-current">ปิด</button>
            </div>
        @endif

        @if($warning)
            <div class="mb-4 p-4 rounded-lg border {{ $types['warning'] }} flex items-start justify-between">
                <div class="text-sm">{!! e($warning) !!}</div>
                <button @click="$el.parentElement.style.display='none'" class="ml-4 text-sm font-medium text-current">ปิด</button>
            </div>
        @endif

        @if($errorsBag)
            <div class="mb-4 p-4 rounded-lg border {{ $types['error'] }}">
                <div class="font-medium mb-2">เกิดข้อผิดพลาด</div>
                <ul class="list-disc list-inside text-sm">
                    @foreach($errorsBag as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    {{-- Persistent DB alerts (require action until dismissed) --}}
    @foreach($persistentAlerts as $p)
        @php
            $cls = $types[$p->type] ?? $types['info'];
        @endphp
        <div id="persistent-alert-{{ $p->id }}" class="mb-4 p-4 rounded-lg border {{ $cls }} flex items-start justify-between">
            <div class="flex-1">
                @if($p->title)
                    <div class="font-semibold mb-1">{{ $p->title }}</div>
                @endif
                <div class="text-sm">{!! nl2br(e($p->message)) !!}</div>
                @if($p->require_action)
                    <div class="mt-2 text-xs text-yellow-800 font-medium">ต้องดำเนินการ</div>
                @endif
            </div>
            <div class="ml-4 flex-shrink-0">
                <button @click.prevent="(function(){
                    fetch('{{ route('alerts.dismiss', ['id' => 'ALERT_ID']) }}'.replace('ALERT_ID', '{{ $p->id }}'), { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } })
                        .then(r => r.json())
                        .then(j => { if (j.ok) { document.getElementById('persistent-alert-{{ $p->id }}').style.display = 'none'; } })
                        .catch(()=>{});
                })()" class="px-3 py-1 bg-white/60 rounded font-medium text-sm">ปิด</button>
            </div>
        </div>
    @endforeach
</div>
