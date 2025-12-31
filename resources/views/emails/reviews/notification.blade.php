@component('mail::message')

New Review Received

Hello Administrator,

A user has submitted a new review for the book {{ $book->title }}.

Review Details

@component('mail::panel')
Citizen: {{ $user->name }} ({{ $user->email }})

Rating: {{ $review->rating }} / 5 Stars

Comment: {{ $review->comment ?? 'No additional comment provided.' }}

@endcomponent

You can check this review and the full catalog by clicking the button below:

@component('mail::button', ['url' => $url])
View Details in Catalog
@endcomponent

Sincerely,

Library Management System

@endcomponent