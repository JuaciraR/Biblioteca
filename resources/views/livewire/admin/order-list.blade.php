<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">Orders Management</h1>
        
        <div class="mt-4 md:mt-0">
            <select wire:model.live="statusFilter" 
                class="rounded-2xl border-2 border-gray-500 text-base font-black text-gray-900 shadow-md focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600 bg-white px-5 py-3 min-w-[220px] cursor-pointer transition-all">
                <option value="">All Order Status</option>
                <option value="pending">Pending Payments</option>
                <option value="paid">Paid Orders</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-2xl border border-emerald-100 font-bold">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Order Info</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Customer</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Amount</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-5">
                            <p class="font-black text-gray-900">#{{ $order->order_number }}</p>
                            <p class="text-[10px] text-gray-400 mt-1 uppercase">{{ $order->created_at->format('M d, Y H:i') }}</p>
                        </td>
                        <td class="px-6 py-5">
                            <p class="text-sm font-bold text-gray-800">{{ $order->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $order->user->email }}</p>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-lg font-black text-indigo-600">€{{ number_format($order->total_amount, 2) }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase
                                {{ $order->status === 'paid' ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600' }}">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                @if($order->status === 'pending')
                                    <button wire:click="updateStatus({{ $order->id }}, 'paid')" class="text-[10px] font-black text-indigo-500 uppercase hover:text-indigo-700 underline underline-offset-4 tracking-tighter">Mark Paid</button>
                                @endif
                                <button wire:click="updateStatus({{ $order->id }}, 'cancelled')" class="text-[10px] font-black text-rose-400 uppercase hover:text-rose-600 underline underline-offset-4 tracking-tighter">Cancel</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center text-gray-300 italic font-medium">No orders found in this category.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $orders->links() }}
        </div>
    </div>
</div>