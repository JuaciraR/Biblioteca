<div class="max-w-xl mx-auto py-12 px-4">
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
        
        <div class="p-8 border-b border-gray-100 text-center bg-gray-50">
            <h2 class="text-xl font-bold text-gray-800 uppercase tracking-tight">Finalize Order</h2>
        </div>

        <form wire:submit.prevent="processCheckout" class="p-8 space-y-5">
            
            {{-- Street / Address --}}
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Street and Number</label>
                <input type="text" wire:model="street" placeholder="e.g. 123 Main Street, Apt 4"
                       class="w-full rounded-xl border-black-200 bg-black p-3 text-sm focus:ring-2 focus:ring-indigo-500">
                @error('street') <span class="text-red-600 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span> @enderror
            </div>

            {{-- Grid for ZIP and City --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Zip Code</label>
                    <input type="text" wire:model="zip_code" placeholder="0000-000"
                           class="w-full rounded-xl border-black-200 bg-black p-3 text-sm focus:ring-2 focus:ring-indigo-500">
                    @error('zip_code') <span class="text-red-600 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">City</label>
                    <input type="text" wire:model="city" placeholder="Lisbon"
                           class="w-full rounded-xl border-black-200 bg-black p-3 text-sm focus:ring-2 focus:ring-indigo-500">
                    @error('city') <span class="text-red-600 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Summary and Button --}}
            <div class="bg-indigo-50 rounded-xl p-5 border border-indigo-100 mt-6">
                <div class="flex justify-between items-center">
                    <span class="text-[10px] font-black text-indigo-400 uppercase">Total Amount:</span>
                    <span class="text-2xl font-black text-indigo-700 italic">€{{ number_format($total, 2) }}</span>
                </div>
            </div>

            <button type="submit" wire:loading.attr="disabled"
                    class="w-full bg-indigo-600 text-white font-black py-4 rounded-xl hover:bg-indigo-700 transition shadow-md uppercase tracking-wider text-sm mt-2">
                <span wire:loading.remove>Pay with Stripe</span>
                <span wire:loading>Processing...</span>
            </button>

            <p class="text-center text-[9px] text-gray-400 font-bold uppercase tracking-widest">
                SSL Secured • Secure Payment via Stripe
            </p>
        </form>
    </div>
</div>