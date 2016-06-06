@extends('common.base')
@section('title')
<title>読んだ本 - honmemo!</title>
@stop

@section('content')
<div class="max-width center">
<div class="home-main wishlist">
  <h5 class="doc-page-title"><i class="fa fa-archive"></i>読んだ本</h5>
    <!--card -->
    @foreach($results as $note)
    <section class="mdl-grid mdl-grid--no-spacing mdl-shadow--2dp doc-card" id="card{{$note->isbn}}">
            <header class="section__book-image">
              
              <img src="{{$note->image_url}}">
              
            </header>
            <div class="mdl-card mdl-cell section__text">
              <div class="mdl-card__supporting-text">
                <h5>{{$note->title}}</h5>
                <div>{{$note->author}}</div>
              </div>
              <div class="mdl-card__actions">
                <a href="/memo/{{$note->isbn}}" class="mdl-button">メモを見る</a>
              </div>
            </div>

    </section>
@endforeach

<ul class="pageNav01">
      @if ($currentPage != 1)
    <li><a href="/archive/{{ $currentPage -1 }}">&laquo; 前</a></li>
    @endif

    @foreach($pages as $page)
    <li>
      @if ($page == $currentPage)
        <span>{{ $page }}</span>
      @else
        <a href="/archive/{{ $page }}">{{ $page }}</a>
      @endif
    </li>
    @endforeach

    @if ($totalPages != 1 && $totalPages != 0)
    @if ($currentPage != $totalPages)
    <li><a href="/archive/{{ $currentPage +1 }}">次 &raquo;</a></li>
    @endif
    @endif
</ul>

</div>
</div>
@stop