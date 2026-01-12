<x-app-layout>
    <div class="py-12 text-center">
        <h2 class="text-3xl font-black text-green-600">THANK YOU!</h2>
        <p class="mt-4">Your order <strong>#{{ $order->order_number }}</strong> has been paid successfully.</p>
        <a href="{{ route('dashboard') }}" class="mt-6 inline-block bg-gray-900 text-green px-8 py-3 rounded-xl">Back to Home</a>
    </div>
</x-app-layout>