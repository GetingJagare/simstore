@extends('frontend.layout')
@section('content')
    <main class="cart-page">
        <div class="container">
            @php
                $offerDoc = "/uploads/%D0%94%D0%BE%D0%B3%D0%BE%D0%B2%D0%BE%D1%80%20%D0%BF%D0%BE%D0%BB%D1%8C%D0%B7%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D1%8F%20%D0%B0%D0%B1%D0%BE%D0%BD%D0%B5%D0%BD%D1%82%D1%81%D0%BA%D0%B8%D0%BC%20%D0%BD%D0%BE%D0%BC%D0%B5%D1%80%D0%BE%D0%BC.docx";
                $offerDocUrl = "https://docs.google.com/viewerng/viewer?url=https://sim-store.ru$offerDoc";
            @endphp
            <cart offer-doc="{{ $offerDocUrl }}" :lat="{{ $region['geo']['lat'] }}" :lng="{{ $region['geo']['lng'] }}"></cart>
        </div>

    </main>
@endsection