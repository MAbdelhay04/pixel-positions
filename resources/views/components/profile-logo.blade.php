@props([ 'width' => 50])

<img class="rounded-xl" src="{{ asset('storage/'. Auth::user()->logo) }}" width="{{ $width }}" alt="logo">
