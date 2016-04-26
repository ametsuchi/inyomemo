@extends('common.header')
@section('content')
<main class="mdl-layout__content doc-main">
    <!-- search -->
    <div class="mdl-grid portfolio-max-width">
    	<h5 class="doc-sub-title"><i class="material-icons">book</i>一覧</h5>
    </div>
    @foreach($results as $item)
    <section id="card{{ $item->isbn }}">
        <div class="mdl-grid portfolio-max-width">
            <div class="mdl-grid mdl-cell mdl-cell--12-col  mdl-card mdl-shadow--4dp">
                <!-- row -->
                <div class="mdl-card__media mdl-cell--2-col mdl-cell--1-col-phone">
                    <a href="/memo/{{$item->isbn}}"><img class="book-image" src=" {{ $item->image_url }}" border="0" alt="" style="max-width:113px;"></a>
                </div>
                <div class="mdl-cell mdl-cell--10-col mdl-cell--3-col-phone">
                    <a href="/memo/{{$item->isbn}}"><h2 class="mdl-card__title-text">{{ $item->title }}</h2></a>
                    <div class="mdl-card__supporting-text padding-top">
                        <span>{{ $item->author }}</span>
                    </div>
                </div>
				<input type="hidden" name="isbn" value="{{ $item->isbn }}" >
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

		@if ($totalPages != 1)
		@if ($currentPage != $totalPages)
		<li><a href="/archive/{{ $currentPage +1 }}">次 &raquo;</a></li>
		@endif
		@endif
</ul>
</main>
@stop