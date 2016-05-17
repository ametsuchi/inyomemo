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

    
    @foreach($results as $item)
    <section>
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
				<div class="mdl-cell mdl-cell--8-col mdl-layout--large-screen-only"></div>
				<div class="mdl-cell mdl-cell--4-col mdl-cell--8-col-tablet mdl-cell--4-col-phone doc-add-list">
				<button class="mdl-button mdl-js-button add-list-button" id="{{$item['isbn']}}">
					<i class="material-icons">add</i><span>リストに追加</span>
				</button>
				</div>

				<input type="hidden" name="isbn" value="{{ $item['isbn'] }}" >
				<input type="hidden" name="title" value="{{ $item['title'] }}" id="title{{ $item['isbn'] }}">
				<input type="hidden" name="author" value="{{ $item['author'] }}" id="author{{ $item['isbn'] }}">
				<input type="hidden" name="publicationDate" value="{{ str_replace('-','/',$item['publicationDate']) }}" id="publicationDate{{ $item['isbn'] }}">
				<input type="hidden" name="imageUrl" value="{{ $item['image'] }}" id="imageUrl{{ $item['isbn'] }}">
				<input type="hidden" name="amazonUrl" value="{{ $item['url'] }}" id="amazonUrl{{ $item['isbn'] }}">
            </div>
        </div>
    </section>
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
    <meta name="csrf-token" content="{{ csrf_token() }}">


<!-- dialog -->
<dialog class="mdl-dialog" id="addDialog">
    <div class="mdl-dialog__content">
      <p>
        ほしいものリストに追加
      </p>
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
    </div>
   	<div class="mdl-dialog__actions">
      <button type="button" class="mdl-button mdl-button--raised close" style="width:80px">閉じる</button>
      <button type="button" id="addlist" class="mdl-button mdl-button--raised mdl-button--colored addListButton" style="width:80px">追加</button>
    </div>

</dialog>


<script type="text/javascript">
var new_flg = false;
$(function(){
	$('.add-list-button').on("click",function(){

		// show dialog
		var dialog = document.getElementById("addDialog");
		dialog.showModal();
		var isbn = $(this).attr("id");
		$(".addListButton").attr("id","add" + isbn);
	});
	$(".close").on("click",function(){
		var dialog = document.getElementById("addDialog");
		dialog.close();
	});

	$(".addListButton").on("click",function(){
		var name = $("#select-section").val();
		var isbn = $(this).attr("id").replace("add","");
		var dialog = document.getElementById("addDialog");
		dialog.close();
		$.post(
				'/wishlist/add',
				{
					'isbn':isbn,
					'title':$('#title'+isbn).val(),
					'author':$('#author'+isbn).val(),
					'imageUrl':$('#imageUrl'+isbn).val(),
					'amazonUrl'	:$('#amazonUrl'+isbn).val(),
					'publicationDate':$('#publicationDate'+isbn).val(),
					'name':name,
					'new_flg':new_flg
				}
			);

	});

	$('div.mdl-select > ul > li').click(function(e) {
        var text = $(e.target).text();
        $(e.target).parents('.mdl-select').addClass('is-dirty').children('input').val(text);

        if($(this).hasClass("new")){
			new_flg = true;
		}else{
			new_flg = false;
		}
    });
});



			// 	$.post(
			// 	'/wishlist/add',
			// 	{
			// 		'isbn':isbn,
			// 		'title':$('#title'+isbn).val(),
			// 		'author':$('#author'+isbn).val(),
			// 		'imageUrl':$('#imageUrl'+isbn).val(),
			// 		'amazonUrl'	:$('#amazonUrl'+isbn).val(),
			// 		'publicationDate':$('#publicationDate'+isbn).val()
			// 	}
			// );	
</script>

</main>
@stop