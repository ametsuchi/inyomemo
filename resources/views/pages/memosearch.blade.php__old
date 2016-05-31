@extends('common.header')
@section('content')
<main class="doc-main">
    <!-- search -->
    <div class="mdl-grid portfolio-max-width">
        <div class="mdl-cell mdl-cell--12-col  mdl-card doc-search-card mdl-shadow--4dp">
            <form action="/wordsearch" method="post">
            {{ csrf_field() }}
              <div class="mdl-grid">
                    <div class="mdl-grid mdl-cell mdl-cell--12-col  mdl-cell--4-col-phone mdl-cell--8-col-tablet">
                    	過去のメモから検索
                    </div>
                    <div class="mdl-grid doc-search-field mdl-cell mdl-cell--10-col  mdl-cell--3-col-phone mdl-cell--7-col-tablet">
                        <input class="mdl-cell mdl-cell--12-col doc-search-text" type="text" id="card_search" name="keyword" placeholder="タイトル,著者,メモから検索" value="{{ $keyword }}">
                    </div>
                        <button type="submit" class="mdl-cell mdl-cell--2-col mdl-cell--1-col-phone mdl-cell--1-col-tablet doc-search-button mdl-button mdl-js-button mdl-button--raised mdl-button--colored doc-search-button">
                            <i class="material-icons">search</i>
                        </button>
              </div>
            </form>
        </div>
    	<h5 class="doc-sub-title"><i class="material-icons">search</i>検索結果</h5>
    </div>

    @if (!empty($message))
    <div class="mdl-grid portfolio-max-width">
    	<div class="mdl-cell mdl-cell--12-col doc-error-message">
    		{{ $message }}
    	</div>
    </div>
    @endif

    
    @foreach($notes as $note)
    <section>
        <div class="mdl-grid portfolio-max-width">
            <div class="mdl-grid mdl-cell mdl-cell--12-col doc-search-card mdl-shadow--4dp">
                <!-- row -->
                <div class="">
                    <a href="/memo/{{$note->isbn}}"><img class="doc-search-note-book-image" src=" {{ $note->image_url }}" border="0" alt=""></a>
                </div>
                <div class="">
                    <a href="/memo/{{ $note->isbn }}"><span >{{ $note->title }}</span></a>
                    <div class="mdl-card__supporting-text padding-top">
                        <span>{{ $note->author }}</span>
                    </div>
                </div>
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
		@if ($currentPage != $totalPages)
		<li><a href="/wordsearch?keyword={{$keyword}}&amp;page={{ $currentPage +1 }}">次 &raquo;</a></li>
		@endif
		@endif
</ul>

</main>
@stop