<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-black text-gray-800 uppercase tracking-tight">Order Management</h2>
        
        <div class="flex space-x-4">
            <input wire:model.live="search" type="text" placeholder="Search orders..." class="rounded-xl border-gray-200 text-sm">
            <select wire:model.live="statusFilter" class="rounded-xl border-gray-200 text-sm">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="paid">Paid</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-50 text-green-700 rounded-xl border border-green-100">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="p-4 text-xs font-bold text-gray-400 uppercase">Order #</th>
                    <th class="p-4 text-xs font-bold text-gray-400 uppercase">Customer</th>
                    <th class="p-4 text-xs font-bold text-gray-400 uppercase">Total</th>
                    <th class="p-4 text-xs font-bold text-gray-400 uppercase">Status</th>
                    <th class="p-4 text-xs font-bold text-gray-400 uppercase">Date</th>
                    <th class="p-4 text-xs font-bold text-gray-400 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 font-bold text-gray-900">{{ $order->order_number }}</td>
                        <td class="p-4">
                            <p class="text-sm font-medium text-gray-700">{{ $order->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $order->user->email }}</p>
                        </td>
                        <td class="p-4 font-black text-indigo-600">${{ number_format($order->total_amount, 2) }}</td>
                        <td class="p-4">
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase
                                {{ $order->status === 'paid' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}
                            ">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="p-4 text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                        <td class="p-4">
                            <div class="flex space-x-2">
                                @if($order->status === 'pending')
                                    <button wire:click="updateStatus({{ $order->id }}, 'paid')" class="text-xs font-bold text-indigo-600 hover:underline">Mark as Paid</button>
                                @endif
                                <button wire:click="updateStatus({{ $order->id }}, 'cancelled')" class="text-xs font-bold text-red-400 hover:underline">Cancel</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-10 text-center text-gray-400 italic">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 bg-gray-50">
            {{ $orders->links() }}
        </div>
    </div>
</div>