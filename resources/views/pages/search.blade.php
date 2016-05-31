@extends('common.base')
@section('content')
<div class="max-width center">
<div class="home-main">

<form action="/search" method="post">
            {{ csrf_field() }}
              <div class="mdl-shadow--2dp doc-serach-card">
                <div class="mdl-grid mdl-grid--no-spacing ">
                        <input class="mdl-cell mdl-cell--10-col mdl-cell--6-col-tablet mdl-cell--3-col-phone" type="text" id="card_search" name="keyword" placeholder="本を検索"
                        	value="{{$keyword}}">

                        <button type="submit" class="mdl-cell mdl-cell--2-col mdl-button mdl-js-button mdl-button--raised mdl-button--colored
                         mdl-cell--2-col-tablet mdl-cell--1-col-phone">
                            <i class="material-icons search">search</i>
                        </button>
                </div>
              </div>
</form>


<h5 class="doc-page-title"><i class="material-icons">search</i>検索結果</h5>

@foreach($results as $item)
<section class="mdl-grid mdl-grid--no-spacing mdl-shadow--2dp doc-card">
            <header class="section__book-image">
            	<a href="{{$item['url']}}">
            	<img src="{{$item['image']}}">
            	</a>
            </header>
            <div class="mdl-card mdl-cell section__text">
              <div class="mdl-card__supporting-text">

            <div class="mdl-card__menu">
    			<a href="#addListDialog" rel="leanModal" name="{{$item['isbn']}}" class="addListOpen mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" id="more{{$item['isbn']}}">
      				<i class="material-icons">more_vert</i>
    			</a>
  			</div>
                <h5>{{$item['title']}}</h5>
                <div>{{$item['author']}}<br/>{{ str_replace('-','/',$item['publicationDate']) }}</div>
              </div>
              <div class="mdl-card__actions">
                <a href="/memo/{{$item['isbn']}}" class="mdl-button">メモを編集</a>
              </div>
            </div>

</section>
				<input type="hidden" name="isbn" value="{{ $item['isbn'] }}" >
				<input type="hidden" name="title" value="{{ $item['title'] }}" id="title{{ $item['isbn'] }}">
				<input type="hidden" name="author" value="{{ $item['author'] }}" id="author{{ $item['isbn'] }}">
				<input type="hidden" name="publicationDate" value="{{ str_replace('-','/',$item['publicationDate']) }}" id="publicationDate{{ $item['isbn'] }}">
				<input type="hidden" name="imageUrl" value="{{ $item['image'] }}" id="imageUrl{{ $item['isbn'] }}">
				<input type="hidden" name="amazonUrl" value="{{ $item['url'] }}" id="amazonUrl{{ $item['isbn'] }}">
@endforeach


<ul class="pageNav01">
		
    	@if ($currentPage != 1)
		<li><a href="/search?keyword={{ $keyword }}&amp;page={{ $currentPage -1 }}">&laquo; 前</a></li>
		@endif

		@foreach($pages as $page)
		<li>
			@if ($page == $currentPage)
				<span>{{ $page }}</span>
			@else
				<a href="/search?keyword={{ $keyword }}&amp;page={{ $page }}">{{ $page }}</a>
			@endif
		</li>
		@endforeach

		@if ($currentPage != $totalPages)
		<li><a href="/search?keyword={{ $keyword }}&amp;page={{ $currentPage +1 }}">次 &raquo;</a></li>
		@endif
</ul>


</div>
</div>
    <script type="text/javascript" src="/js/search.js"></script>
    <script type="text/javascript">
    $(function(){
        document.title = "検索 - bkim";
    });
    </script>
@stop

@section('more')
    <!-- add wishlist dialog -->
    <div class="dialog" id="addListDialog">
        <h5 class="mdl-title">ほしいものリストに追加</h5>
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-select">
          <input class="mdl-textfield__input" type="text" id="select-section" value="未分類" readonly="readonly"/>
          <label class="mdl-textfield__label" for="select-section">リスト名</label>
          <ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect" for="select-section">
              <li class="mdl-menu__item list-select">未分類</li>
              @foreach($lists as $list)
              <li class="mdl-menu__item list-select">{{$list->name}}</li>
              @endforeach
              <li class="mdl-menu__item list-select new">新規作成…</li>
         </ul>
        </div>
        <div class="right">
          <button type="button" id="addListButton" name="addListButton" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" name="addList">追加</button>
        </div>
    </div>
@stop