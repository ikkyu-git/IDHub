@extends('layouts.admin')

@section('title', 'SSO Clients - Admin Console')

@section('content')
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ showCreateModal: false, showEditModal: false, editClient: null }">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-bold text-gray-800">SSO Clients</h2>
                <p class="text-sm text-gray-500">จัดการแอปพลิเคชันที่เชื่อมต่อผ่าน SSO</p>
            </div>
            <button @click="showCreateModal = true" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Client
            </button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                        <th class="px-6 py-4 font-semibold">Name</th>
                        <th class="px-6 py-4 font-semibold">Client ID</th>
                        <th class="px-6 py-4 font-semibold">Redirect URI</th>
                        <th class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($clients as $client)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-lg mr-3">
                                    {{ substr($client->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $client->name }}</div>
                                    <div class="text-xs text-gray-500">Created {{ $client->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div x-data="{ showId: false }">
                                <button @click="showId = !showId" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded-lg transition-colors flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path x-show="!showId" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path x-show="!showId" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        <path x-show="showId" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    </svg>
                                    <span x-text="showId ? 'Hide' : 'Show'"></span>
                                </button>
                                <div x-show="showId" x-transition class="mt-2 font-mono text-xs text-gray-800 bg-gray-50 px-3 py-2 rounded-lg border border-gray-200 select-all break-all">
                                    {{ $client->client_id }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate" title="{{ $client->redirect }}">
                            {{ $client->redirect }}
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button @click="editClient = {{ json_encode($client) }}; showEditModal = true" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</button>
                            <form action="{{ route('admin.clients.regenerate', $client->id) }}" method="POST" class="inline-block" onsubmit="return confirm('การสร้าง Secret ใหม่จะทำให้แอปพลิเคชันที่ใช้งานอยู่หลุดการเชื่อมต่อ ยืนยัน?');">
                                @csrf
                                <button type="submit" class="text-amber-600 hover:text-amber-900 text-sm font-medium">Regenerate Secret</button>
                            </form>
                            <form action="{{ route('admin.clients.destroy', $client->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this client?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">No clients found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Create Client Modal -->
        <div x-show="showCreateModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showCreateModal" class="fixed inset-0 transition-opacity" @click="showCreateModal = false">
                    <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
                <div x-show="showCreateModal" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    <form action="{{ route('admin.clients.store') }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Create New SSO Client</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Client Name</label>
                                    <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Redirect URI</label>
                                    <input type="url" name="redirect" required placeholder="https://app.example.com/callback" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                    <p class="text-xs text-gray-500 mt-1">Callback URL for OAuth2 flow</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Create Client
                            </button>
                            <button type="button" @click="showCreateModal = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Client Modal -->
        <div x-show="showEditModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showEditModal" class="fixed inset-0 transition-opacity" @click="showEditModal = false">
                    <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
                <div x-show="showEditModal" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    <form :action="'{{ route('admin.clients.update', ['id' => 0]) }}'.replace('/0', '/' + (editClient ? editClient.id : ''))" method="POST">
                        @csrf @method('PATCH')
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Edit SSO Client</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Client Name</label>
                                    <input type="text" name="name" x-model="editClient ? editClient.name : ''" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Redirect URI</label>
                                    <input type="url" name="redirect" :value="editClient ? (editClient.redirect || (editClient.redirect_uris && editClient.redirect_uris[0]) || '') : ''" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Save Changes
                            </button>
                            <button type="button" @click="showEditModal = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Secret Display Modal (If redirected with new client secret) -->
        @if(session('client_secret'))

        <div x-data="{ open: true }" x-show="open" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" @click="open = false">
                    <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 text-center mb-2">Client Created Successfully</h3>
                        <p class="text-sm text-gray-500 text-center mb-4">Please copy your client secret now. It will not be shown again.</p>
                        
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 space-y-3">
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase">Client ID</label>
                                <div class="font-mono text-sm text-gray-800 select-all">
                                    {{ session('client_id') ?: (session('client_secret') ? \App\Models\SsoClient::where('client_secret', session('client_secret'))->value('client_id') : '') }}
                                </div>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase">Client Secret</label>
                                <div class="font-mono text-sm text-gray-800 break-all select-all font-bold">{{ session('client_secret') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="open = false" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            I have copied it
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection
