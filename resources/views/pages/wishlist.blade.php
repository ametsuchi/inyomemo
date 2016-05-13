@extends('common.header')
@section('content')
<main class="mdl-layout__content doc-main">
    <!-- search -->
    <div class="mdl-grid portfolio-max-width">
    	<h5 class="doc-sub-title">ほしいものリスト</h5>
    </div>
    @foreach($results as $item)
    <section id="card{{ $item->isbn }}">
        <div class="mdl-grid portfolio-max-width">
            <div class="mdl-grid mdl-cell mdl-cell--12-col  mdl-card mdl-shadow--4dp">
                <!-- row -->
                <div class="mdl-card__media mdl-cell--2-col mdl-cell--1-col-phone">
                    <a href="{{$item->amazon_url}}"><img class="book-image" src=" {{ $item->image_url }}" border="0" alt="" style="max-width:113px;"></a>
                </div>
                <div class="mdl-cell mdl-cell--10-col mdl-cell--3-col-phone">
                    <a href="{{$item->amazon_url}}"><h2 class="mdl-card__title-text">{{ $item->title }}</h2></a>
                    <div class="mdl-card__supporting-text padding-top">
                        <span>{{ $item->author }},{{ str_replace('-','/',$item->publication_date) }}</span>
                    </div>
                </div>
                <!-- row -->
				<div class="mdl-cell mdl-cell--8-col mdl-layout--large-screen-only"></div>
				<button type="button" id="{{ $item->isbn }}" class="mdl-button mdl-js-button mdl-button--raised add-list-button del">
  					<i class="material-icons">remove</i>ほしいものリストから削除
				</button>
				<input type="hidden" name="isbn" value="{{ $item->isbn }}" >
            </div>
        </div>
    </section>
    @endforeach

    <ul class="pageNav01">
		
    	@if ($currentPage != 1)
		<li><a href="/wishlist/{{ $currentPage -1 }}">&laquo; 前</a></li>
		@endif

		@foreach($pages as $page)
		<li>
			@if ($page == $currentPage)
				<span>{{ $page }}</span>
			@else
				<a href="/wishlist/{{ $page }}">{{ $page }}</a>
			@endif
		</li>
		@endforeach

		@if ($totalPages != 1)
		@if ($currentPage != $totalPages)
		<li><a href="/wishlist/{{ $currentPage +1 }}">次 &raquo;</a></li>
		@endif
		@endif
</ul>
<script type="text/javascript">

	$('.add-list-button').click(function(){
		var isbn = $(this).attr('id');
		$("#card"+isbn).hide("10");
		$.post(
			'/wishlist/delete',
			{
				'isbn':isbn
			}
		);			

	});
</script>

</main>
@stop