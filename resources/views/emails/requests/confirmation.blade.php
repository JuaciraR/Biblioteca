@component('mail::message')

{{ $is_admin ? 'New Book Request Notification' : 'Request Confirmation' }}

@if ($is_admin)
A new book request has been submitted by {{ $request->user->name }}. Please review and process this request on the Admin panel.
@else
Hello {{ $request->user->name }},

We have successfully received your book request. Your order is currently PENDING and awaiting approval.
@endif

Request Details

@component('mail::panel')
| Item | Detail |
| :--- | :--- |
| Request No. | {{ $request->request_number }} |
| Book Title | {{ $book->title }} |
| Current Status | {{ $request->status }} |
| Expected Due Date | {{ $request->due_date->format('Y-m-d') }} |
@endcomponent

@if ($book->cover_image)
[Book Cover: {{ $book->title }}]({{ $book->cover_image }})
@endif

@if (!$is_admin)
You will receive a new notification when your request is APPROVED.
@endif

Sincerely,

The Library Team
@endcomponent