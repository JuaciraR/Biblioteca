@component('mail::message')

Review Moderation Update

Hello {{ $review->user->name }},

We have finished processing your review for the book {{ $review->book->title }}.

Unfortunately, we cannot publish your review at this time for the following reason:

@component('mail::panel')
{{ $review->rejection_reason }}
@endcomponent

You can check your dashboard to see your existing reviews or submit a new version.

@component('mail::button', ['url' => url('/dashboard')])
Go to My Dashboard
@endcomponent

Best regards,

The Library Team
@endcomponent