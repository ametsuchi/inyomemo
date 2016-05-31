@extends('common.header')
@section('content')
<main class="doc-main">
    <!-- search -->
    <div class="mdl-grid portfolio-max-width">
    	<h5 class="doc-sub-title">ほしいものリスト</h5>
    </div>
    <div class="mdl-grid portfolio-max-width">
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-select">
  		    <input class="mdl-textfield__input" type="text" id="select-section" value="{{$selectedName}}" readonly="readonly"/>
  		    <label class="mdl-textfield__label" for="select-section">リスト名</label>
  		    <ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect" for="select-section">
      		    @foreach($list as $array)
      		    <a href="/wishlist/{{$array[0]}}" name="wishlist{{$array[0]}}">
                <li class="mdl-menu__item list-select menu{{$array[0]}}"　>{{$array[1]}}</li>
                </a>
      		    @endforeach
    	   </ul>
		</div>
        @if($titleid != 0)
		<button class="mdl-button mdl-js-button mdl-button--icon doc-setting">
		<i class="material-icons">settings</i>
		</button>
        @endif
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
  					<i class="material-icons">remove</i>削除
				</button>
				<input type="hidden" name="isbn" value="{{ $item->isbn }}" >
            </div>
        </div>
    </section>
    @endforeach

    <ul class="pageNav01">
    	@if ($currentPage != 1)
		<li><a href="/wishlist/{{$titleid}}/{{ $currentPage -1 }}">&laquo; 前</a></li>
		@endif

		@foreach($pages as $page)
		<li>
			@if ($page == $currentPage)
				<span>{{ $page }}</span>
			@else
				<a href="/wishlist/{{$titleid}}/{{ $page }}">{{ $page }}</a>
			@endif
		</li>
		@endforeach

		@if ($totalPages != 1 && $totalPages != 0)
		@if ($currentPage != $totalPages)
		<li><a href="/wishlist/{{$titleid}}/{{ $currentPage +1 }}">次 &raquo;</a></li>
		@endif
		@endif
</ul>

<!-- dialog -->
<dialog class="mdl-dialog" id="settingDialog">
    <div class="mdl-dialog__content">
      <p>
        設定
      </p>
      <span class="error">既に使用されている名前です</span> 
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" type="text" id="setting-name" name="{{$titleid}}" value="{{$selectedName}}" maxlength="20" />
            <label class="mdl-textfield__label" for="select-section">リスト名をを変更…</label>
        </div>
    </div>
    <div class="middle">
      <div style="display:none" id="setting-loading"><div id="setting-loading-image" class="mdl-spinner mdl-js-spinner is-active"></div>loading</div>
      <button type="button" id="setting-rename" class="mdl-button mdl-button--raised mdl-button--colored" style="width:80px">変更</button>
      <button type="button" id="setting-delete" class="mdl-button mdl-button--raised mdl-button--colored mdl-button--accent" style="width:80px">削除</button>
      <button type="button" class="mdl-button mdl-button--raised close" style="width:80px">閉じる</button>
    </div>
</dialog>
<script type="text/javascript">

	$('.add-list-button').click(function(){
		var isbn = $(this).attr('id');
        var titleid = $("#setting-name").attr("name");
		$("#card"+isbn).hide("10");
		$.post(
			'/wishlist/delete',
			{
				'isbn':isbn,
                'titleid':titleid
			}
		);			

	});

	$(function() {

        $(".doc-setting").on("click",function(){
            $(".error").hide();
            $("#setting-loading").hide();
            var dialog = document.getElementById("settingDialog");
            dialog.showModal();
        });

        $("#setting-rename").on("click",function(){
            var titleid = $("#setting-name").attr("name");
            var name = $("#setting-name").val();

            // ダイアログの中
            $("#setting-name").val(name);
            // リスト名
            $("#select-section").val(name);
            // メニューの中
            $(".menu" + titleid).text(name);

            var dialog = document.getElementById("settingDialog");
            dialog.close();
            $.get(
                '/wishlist/rename',
                {
                    'titleid':titleid,
                    'name':name
                }
                );
        });

        $("#setting-delete").on("click",function(){
            var titleid = $("#setting-name").attr("name");
            $("#setting-loading").show();
            $("#setting-loading-image").addClass("is-active");
            $.get(
                '/wishlist/deletelist',
                {
                    'titleid':titleid
                },
                function(){
                    window.location.href = "/wishlist/0";
                    $.get('/evernote/delete/'+titleid);
                }
            );
        });

        $(".close").on("click",function(){
            var dialog = document.getElementById("settingDialog");
            dialog.close();
        });
	});
</script>

</main>
@stop