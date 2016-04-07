@extends('common.header')
@section('content')
<main class="mdl-layout__content doc-main">
    <!-- search -->
    <div class="mdl-grid portfolio-max-width">
        <div class="mdl-cell mdl-cell--12-col  mdl-card doc-search-card mdl-shadow--4dp">
            <form action="#">
              <div class="mdl-grid">
                    <div class="doc-search-field mdl-cell mdl-cell--10-col  mdl-cell--3-col-phone mdl-cell--7-col-tablet">
                        <input class="doc-search-text" type="text" id="card_search" name="search" placeholder="本を検索">
                    </div>
                        <button class="mdl-cell mdl-cell--2-col mdl-cell--1-col-phone mdl-cell--1-col-tablet doc-search-button mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
                            <i class="material-icons">search</i>
                        </button>
              </div>
            </form>
        </div>
    </div>

    @foreach($notes as $note)
    <section class="section--center">
        <div class="mdl-grid portfolio-max-width">
            <div class="mdl-grid mdl-cell mdl-cell--12-col  mdl-card mdl-shadow--4dp">
                <div class="mdl-card__media mdl-cell--2-col mdl-cell--1-col-phone">
                    <a href="/memo/{{$note->isbn}}"><img class="book-image" src=" {{ $note->image_url }}" border="0" alt="" style="max-width:113px;"></a>
                </div>
                <div class="mdl-cell mdl-cell--10-col mdl-cell--3-col-phone">
                    <a href="/memo/{{$note->isbn}}"><h2 class="mdl-card__title-text">{{ $note->title }}</h2></a>
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