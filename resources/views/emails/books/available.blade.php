@component('mail::message')

The book is back! 

Hello,

You asked us to notify you when {{ $book->title }} became available.

We are happy to tell you that it has just been returned and is ready for a new request.

@component('mail::button', ['url' => route('books.show', $book->id)])
View Book and Request
@endcomponent

Thank you for using our Digital Library.

Best regards,

The Library Team

@endcomponent