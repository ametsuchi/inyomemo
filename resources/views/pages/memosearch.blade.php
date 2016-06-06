@extends('common.base')
@section('title')
<title>過去のメモから検索 - honmemo!</title>
@stop


@section('content')
<div class="max-width center">
<div class="home-main">

<h5 class="doc-page-title"><i class="material-icons">search</i>過去のメモから検索</h5>

<form action="/wordsearch" method="post">
            {{ csrf_field() }}
              <div class="mdl-shadow--2dp doc-serach-card">
                <div class="mdl-grid mdl-grid--no-spacing ">
                        <input class="mdl-cell mdl-cell--10-col mdl-cell--6-col-tablet mdl-cell--3-col-phone" type="text" id="card_search" name="keyword"
                         placeholder="タイトル,著者,メモから検索" value="{{ $keyword }}">

                        <button type="submit" class="mdl-cell mdl-cell--2-col mdl-button mdl-js-button mdl-button--raised mdl-button--colored
                         mdl-cell--2-col-tablet mdl-cell--1-col-phone">
                            <i class="material-icons search">search</i>
                        </button>
                </div>
              </div>
</form>

    @if (!empty($message))
    <div class="mdl-grid">
      <div class="mdl-cell mdl-cell--12-col error">
        {{ $message }}
      </div>
    </div>
    @endif



        @foreach($notes as $note)
        <section class="mdl-grid mdl-grid--no-spacing mdl-shadow--2dp doc-card">
            <header class="section__book-image">
              <img src="{{$note->image_url}}">
            </header>
            <div class="mdl-card mdl-cell section__text">
              <div class="mdl-card__supporting-text">
                <h5>{{$note->title}}</h5>
                <div>{{$note->author}}</div>
              </div>
              <div class="mdl-card__actions">
                <a href="/memo/{{$note->isbn}}" class="mdl-button">メモを編集</a>
              </div>
            </div>

</section>
    @endforeach


    <ul class="pageNav01">
    
      @if ($currentPage != 1 && $totalPages != 1)
    <li><a href="/wordsearch?keyword={{$keyword}}&amp;page={{ $currentPage -1 }}">&laquo; 前</a></li>
    @endif

    @foreach($pages as $page)
    <li>
      @if ($page == $currentPage)
        <span>{{ $page }}</span>
      @else
        <a href="/wordsearch?keyword={{$keyword}}&amp;page={{ $page }}">{{ $page }}</a>
      @endif
    </li>
    @endforeach

    @if ($totalPages != 1)
    @if ($currentPage != $totalPages && $totalPages != 0)
    <li><a href="/wordsearch?keyword={{$keyword}}&amp;page={{ $currentPage +1 }}">次 &raquo;</a></li>
    @endif
    @endif
</ul>
@stop