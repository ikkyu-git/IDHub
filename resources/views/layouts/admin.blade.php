<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Admin Console')</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
      body { font-family: 'Sarabun', sans-serif; }
      [x-cloak] { display: none !important; }
      ::-webkit-scrollbar { width: 6px; height: 6px; }
      ::-webkit-scrollbar-track { background: #f1f1f1; }
      ::-webkit-scrollbar-thumb { background: #c7c7c7; border-radius: 3px; }
      ::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
  </style>
</head>
<body class="bg-gray-50 text-gray-800" x-data="{ sidebarOpen: false, loading: false }" x-init="window.addEventListener('beforeunload', () => loading = true)">

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

  <!-- Loader -->
  <div x-show="loading" class="fixed inset-0 z-[100] flex items-center justify-center bg-white/80 backdrop-blur-sm" x-transition.opacity style="display: none;">
    <div class="animate-spin rounded-full h-12 w-12 border-4 border-indigo-200 border-t-indigo-600"></div>
  </div>

  <div class="flex h-screen overflow-hidden">
    
    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white transition-transform duration-300 ease-in-out md:relative md:translate-x-0 shadow-xl flex flex-col">
      <div class="h-16 flex items-center justify-center border-b border-slate-800 bg-slate-900">
        <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-400 to-cyan-400">Admin Console</span>
      </div>

      <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-all">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            Dashboard
        </a>

        @if(auth()->user()->hasPermission('view_users'))
        <a href="{{ route('admin.users') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.users*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-all">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            จัดการผู้ใช้
        </a>
        @endif

        @if(auth()->user()->hasPermission('view_roles'))
        <a href="{{ route('admin.roles') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.roles*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-all">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            จัดการ Role & สิทธิ์
        </a>
        @endif

        @if(auth()->user()->hasPermission('view_logs'))
        <a href="{{ route('admin.logs') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.logs*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-all">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            Audit Logs
        </a>
        @endif

        @if(auth()->user()->hasPermission('manage_roles'))
        <a href="{{ route('admin.alerts.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.alerts*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-all">
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h11z"></path></svg>
          จัดการประกาศ
        </a>
        @endif

        <a href="{{ route('admin.settings') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.settings*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-all">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            ตั้งค่าระบบ
        </a>

        <a href="{{ route('admin.clients') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.clients*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-all">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            SSO Clients
        </a>

        <div class="pt-4 mt-4 border-t border-slate-800">
            <a href="{{ route('user.dashboard') }}" @click="loading = true" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                มุมมองผู้ใช้
            </a>

            @if(auth()->user()->hasPermission('manage_clients'))
            <a href="{{ route('developer.docs') }}" target="_blank" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                SSO Implementation
            </a>
            @endif
        </div>
      </nav>

      <div class="p-4 border-t border-slate-800">
        <div class="flex items-center gap-3 mb-4 px-2">
            <!-- Sidebar Avatar -->
            @if(auth()->user()->avatar_url)
                <img src="{{ auth()->user()->avatar_url }}" alt="Admin" class="w-8 h-8 rounded-full object-cover border border-slate-600">
            @else
                <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-xs font-bold">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            @endif
            <div class="overflow-hidden">
                <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" @submit="loading = true">
            @csrf
            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm font-medium text-red-400 rounded-lg hover:bg-red-500/10 hover:text-red-300 transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                ออกจากระบบ
            </button>
        </form>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      
      <!-- Mobile Header -->
      <header class="flex items-center justify-between p-4 bg-white shadow-sm md:hidden z-40">
        <span class="font-bold text-lg text-slate-800">Admin Console</span>
        <button @click="sidebarOpen = !sidebarOpen" class="text-slate-500 focus:outline-none">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
      </header>

      <!-- Content Scrollable Area -->
      <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6 md:p-8">
        
        <!-- Alerts -->
        @include('partials.alerts')

        @yield('content')

      </main>
    </div>
  </div>
</body>
</html>
