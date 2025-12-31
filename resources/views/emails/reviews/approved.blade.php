@component('mail::message')

Review Approved!

Hello {{ $review->user->name }},

We are pleased to inform you that your review for the book {{ $review->book->title }} has been approved and is now visible to the community.

Thank you for your contribution!

@component('mail::button', ['url' => url('/books/' . $review->book_id)])
View Review
@endcomponent

Best regards,

The Library Team
@endcomponent