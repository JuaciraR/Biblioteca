@component('mail::message')

# Do you need help with your cart?

Hello {{ $user->name }},

We noticed that you have some books waiting in your shopping cart. 

If you have any questions about the checkout process or the books you've selected, please feel free to reach out. We are here to help!

@component('mail::button', ['url' => route('cart')])
Return to My Cart
@endcomponent

Thank you for using our Digital Library.

Best regards,

The Library Team
@endcomponent