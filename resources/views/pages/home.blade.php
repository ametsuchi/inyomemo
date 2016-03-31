@extends('common.header')
@section('content')
<main class="mdl-layout__content doc-main">
    <!-- search -->
    <div class="mdl-grid portfolio-max-width">
        <div class="mdl-cell mdl-cell--12-col  mdl-card doc-search-card ">
            <form action="#">
                <div class="mdl-cell mdl-cell--12-col">
                    <div class="doc-search-field">
                        <input class="doc-search-text" type="text" id="card_search" name="search" placeholder="本を検索">
                        <button class="doc-search-button mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
                            <i class="material-icons">search</i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @foreach($notes as $note)
    <section class="section--center">
        <div class="mdl-grid portfolio-max-width">
            <div class="mdl-grid mdl-cell mdl-cell--12-col  mdl-card mdl-shadow--4dp">
                <div class="mdl-card__media mdl-cell--2-col mdl-cell--1-col-phone">
                    <img class="book-image" src=" {{ $note->image_url }}" border="0" alt="" style="max-width:113px;">
                </div>
                <div class="mdl-cell mdl-cell--10-col mdl-cell--3-col-phone">
                    <h2 class="mdl-card__title-text">{{ $note->title }}</h2>
                    <div class="mdl-card__supporting-text padding-top">
                        <span>{{ $note->author }}</span>
                    </div>
                    @foreach($note->comments as $comment)
                    <div class="doc-about-card section__text mdl-card__supporting-text no-left-padding">
                        <p>{{ $comment["comment"]}} </p>
                        <span>{{ $comment["created_at"] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endforeach
</main>
@stop