@extends('frontend.layout')
@section('content')
    <main>
        <section class="static-page">
            <div class="container">
                <div class="big-h1">{{ $page->name }}</div>
                <div>
                    {!! $page->content !!}
                </div>
            </div>
        </section>

        <div class="info-blocks">
            <div class="container">
                <div class="row">

                    <div class="col-md-4">
                        <div class="big-h1">О компании</div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        <div class="info-blocks__additional"><b>работаем <span>с 2006 года</span></b></div>
                    </div>
                    <div class="col-md-4">
                        <div class="big-h1">Гарантии</div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        <div class="info-blocks__additional"><b><span>2 года</span> гарантии</b></div>
                    </div>
                    <div class="col-md-4">
                        <div class="big-h1">Возврат товара</div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        <div class="info-blocks__additional"><b>позвоните на <span>8 800 700 36 37</span></b></div>
                    </div>
                </div>
            </div>
        </div>

        @include('frontend.parts.question')
    </main>
@endsection