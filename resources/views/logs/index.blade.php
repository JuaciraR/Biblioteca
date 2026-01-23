<x-app-layout>
    <div class="p-8 bg-white min-h-screen">
        {{-- Cabeçalho Estilo Dashboard --}}
        <div class="mb-8">
            <h1 class="text-5xl font-black text-gray-900 uppercase italic tracking-tighter">
                System Audit Logs
            </h1>
            <p class="text-gray-900 font-bold uppercase text-xs tracking-widest mt-2">
                Traceability and security: monitoring every action in the system
            </p>
        </div>

        {{-- Tabela  --}}
        <div class="overflow-hidden rounded-[2.5rem] border-4 border-gray-900 shadow-2xl bg-white">
            <table class="table w-full border-collapse">
                <thead class="bg-gray-900 border-b-4 border-gray-900">
                    <tr class="text-white font-black uppercase text-xs tracking-widest text-center">
                        <th class="p-5">Date & Time</th>
                        <th class="p-5">User</th>
                        <th class="p-5">Module</th>
                        <th class="p-5">Object ID</th>
                        <th class="p-5 text-left">Action / Change</th>
                        <th class="p-5">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y-4 divide-gray-100 text-center">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 transition-colors">
                            {{-- Data e Hora --}}
                            <td class="p-5 font-black text-gray-900 text-sm">
                                {{ $log->created_at->format('d/m/Y') }}
                                <span class="block text-gray-500 text-[10px]">{{ $log->created_at->format('H:i:s') }}</span>
                            </td>

                            {{-- Utilizador --}}
                            <td class="p-5">
                                <span class="px-3 py-1 bg-blue-100 border-2 border-blue-900 text-blue-900 font-black rounded-lg text-xs uppercase">
                                    {{ $log->user->name ?? 'System' }}
                                </span>
                            </td>

                            {{-- Módulo --}}
                            <td class="p-5 font-black text-gray-900 uppercase text-[10px] italic">
                                {{ $log->module }}
                            </td>

                            {{-- ID do Objeto --}}
                            <td class="p-5 font-black text-gray-900">
                                #{{ $log->object_id }}
                            </td>

                            {{-- Alteração/Ação --}}
                            <td class="p-5 text-left">
                                <div class="text-sm font-bold text-gray-950 bg-gray-50 p-3 rounded-xl border-2 border-gray-200">
                                    {{ $log->action }}
                                </div>
                            </td>

                            {{-- IP e Browser --}}
                            <td class="p-5">
                                <code class="text-[10px] font-black text-gray-600 bg-gray-100 px-2 py-1 rounded border border-gray-300">
                                    {{ $log->ip_address }}
                                </code>
                                <span class="text-[9px] text-gray-400 font-medium truncate max-w-[150px]" title="{{ $log->browser }}">
                                        {{ $log->browser ?? 'Unknown Browser' }}
                                    </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-20 text-gray-400 font-black uppercase text-xl italic tracking-widest text-center">
                                No activity logs recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginação --}}
        <div class="mt-8">
            {{ $logs->links() }}
        </div>
    </div>
</x-app-layout>