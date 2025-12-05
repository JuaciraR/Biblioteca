@component('mail::message')

Request Update: REJECTED

Hello {{ $user->name }},

We regret to inform you that your request for the book {{ $book->title }} has been REJECTED by the administration.

This may be due to the book being recently picked up or reaching maximum concurrent capacity.

Request Details

@component('mail::panel')
| Item | Detail |
| :--- | :--- |
| Request No. | {{ $request->request_number }} |
| Book Title | {{ $book->title }} |
| Current Status | REJECTED |
@endcomponent

You are welcome to submit a new request at a later time.

Sincerely,

The Library Team
@endcomponent