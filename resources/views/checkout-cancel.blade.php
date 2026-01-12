<x-app-layout>
    <div class="py-12 text-center text-rose-600">
        <h2 class="text-3xl font-black">PAYMENT CANCELED</h2>
        <p class="mt-4">The transaction was not completed. Your items are still in the cart.</p>
        <a href="{{ route('cart') }}" class="mt-6 inline-block bg-gray-900 text-white px-8 py-3 rounded-xl">Return to Cart</a>
    </div>
</x-app-layout>