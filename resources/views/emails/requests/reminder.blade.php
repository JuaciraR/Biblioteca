@component('mail::message')

Return Reminder (Due Tomorrow)

Hello {{ $user->name }},

This is an automated reminder. The book "{{ $book->title }}" is due for return TOMORROW ({{ $due_date }}).

Please ensure the book is returned on time to avoid late fees.

Book Details

Item

Detail

Book Title

{{ $book->title }}

Due Date

{{ $due_date }}

@component('mail::button', ['url' => route('requests')])
View My Requests
@endcomponent

Thank you for your cooperation.

Sincerely,

The Library Team
@endcomponent