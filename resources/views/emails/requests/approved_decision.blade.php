@component('mail::message')

Request Approved!

Hello {{ $user->name }},

We are pleased to confirm that your request for the book {{ $book->title }} has been APPROVED by the administration.

You can now proceed to collect your book.

Important Details

@component('mail::panel')
| Item | Detail |
| :--- | :--- |
| Request No. | {{ $request->request_number }} |
| Book Title | {{ $book->title }} |
| Current Status | APPROVED |
| Due Date (Return) | {{ $due_date }} |
@endcomponent

@if ($book->cover_image)
[Book Cover: {{ $book->title }}]({{ $book->cover_image }})
@endif

Please ensure the book is returned by the due date.

Sincerely,

The Library Team
@endcomponent