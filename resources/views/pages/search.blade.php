@extends('common.header')
@section('content')
<main class="mdl-layout__content doc-main">
    <!-- search -->
    <div class="mdl-grid portfolio-max-width">
        <div class="mdl-cell mdl-cell--12-col  mdl-card doc-search-card mdl-shadow--4dp">
            <form action="/search" method="post">
            {{ csrf_field() }}
              <div class="mdl-grid">
                    <div class="mdl-grid doc-search-field mdl-cell mdl-cell--10-col  mdl-cell--3-col-phone mdl-cell--7-col-tablet">
                        <input class="mdl-cell mdl-cell--12-col doc-search-text" type="text" id="card_search" name="keyword" placeholder="本を検索" value="{{ $keyword }}">
                    </div>
                        <button type="submit" class="mdl-cell mdl-cell--2-col mdl-cell--1-col-phone mdl-cell--1-col-tablet doc-search-button mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
                            <i class="material-icons">search</i>
                        </button>
              </div>
            </form>
        </div>
    </div>

    @foreach($results as $item)
        <div class="mdl-grid portfolio-max-width">
            <div class="mdl-grid mdl-cell mdl-cell--12-col  mdl-card mdl-shadow--4dp">
                <!-- row -->
                <div class="mdl-card__media mdl-cell--2-col mdl-cell--1-col-phone">
                    <a href="/memo/{{$item['isbn']}}"><img class="book-image" src=" {{ $item['image'] }}" border="0" alt="" style="max-width:113px;"></a>
                </div>
                <div class="mdl-cell mdl-cell--10-col mdl-cell--3-col-phone">
                    <a href="/memo/{{$item['isbn']}}"><h2 class="mdl-card__title-text">{{ $item['title'] }}</h2></a>
                    <div class="mdl-card__supporting-text padding-top">
                        <span>{{ $item['author'] }},{{ str_replace('-','/',$item['publicationDate']) }}</span>
                    </div>
                </div>
                <!-- row -->
				<div class="mdl-cell mdl-cell--10-col mdl-layout--large-screen-only"></div>
				<button type="button" id="{{ $item['isbn'] }}" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored add-list">
  					ADD WISH LIST
				</button>
				{{ csrf_field() }}
				<input type="hidden" name="isbn" value="{{ $item['isbn'] }}" >
				<input type="hidden" name="title" value="{{ $item['title'] }}" id="title{{ $item['isbn'] }}">
				<input type="hidden" name="author" value="{{ $item['author'] }}" id="author{{ $item['isbn'] }}">
				<input type="hidden" name="publicationDate" value="{{ str_replace('-','/',$item['publicationDate']) }}" id="publicationDate{{ $item['isbn'] }}">
				<input type="hidden" name="imageUrl" value="{{ $item['image'] }}" id="imageUrl{{ $item['isbn'] }}">
				<input type="hidden" name="amazonUrl" value="{{ $item['url'] }}" id="amazonUrl{{ $item['isbn'] }}">
            </div>
        </div>
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script type="text/javascript">
	$('.add-list').click(function(){
		var isbn = $(this).attr('id');
		var token = $('input[name=_token]').val();

		if($(this).text() === 'DELETE'){
			$(this).addClass('mdl-button--colored');
			$(this).text('ADD WISH LIST');

			$.post(
				'/wishlist/delete',
				{
					'isbn':isbn,
					'_token':token
				}
			);			

		}else{
			$(this).removeClass('mdl-button--colored');
			$(this).text('DELETE');

			$.post(
				'/wishlist/add',
				{
					'isbn':isbn,
					'title':$('#title'+isbn).val(),
					'author':$('#author'+isbn).val(),
					'imageUrl':$('#imageUrl'+isbn).val(),
					'amazonUrl'	:$('#amazonUrl'+isbn).val(),
					'publicationDate':$('#publicationDate'+isbn).val(),
					'_token':token
				}
			);			
		}
	});
</script>

</main>
@stop