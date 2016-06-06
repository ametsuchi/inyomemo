@extends('common.base')
@section('title')
<title>ほしいものリスト - honmemo!</title>
@stop


@section('content')
<div class="max-width center">
<div class="home-main wishlist">
  <h5 class="doc-page-title"><i class="fa fa-star"></i>ほしいものリスト</h5>

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
        <a rel="leanModal" href="#settingWishlistDialog" class="mdl-button mdl-js-button mdl-button--icon doc-setting">
          <i class="material-icons">settings</i>
        </a>
        @endif
    </div>

    <!--card -->
    @foreach($results as $note)
    <section class="mdl-grid mdl-grid--no-spacing mdl-shadow--2dp doc-card" id="card{{$note->isbn}}">
            <header class="section__book-image">
              <a href="{{$note->amazon_url}}">
              <img src="{{$note->image_url}}">
              </a>
            </header>
            <div class="mdl-card mdl-cell section__text">
              <div class="mdl-card__supporting-text">

            <div class="mdl-card__menu">
            <button type="button" id="{{$note->isbn}}" class="add-list-button mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect"
             >
              <i class="material-icons">close</i>
            </button>
        </div>
                <h5>{{$note->title}}</h5>
                <div>{{$note->author}}<br/>{{ str_replace('-','/',$note->publication_date) }}</div>
              </div>
              <div class="mdl-card__actions">
                <a href="{{$note->amazon_url}}" class="mdl-button">Amazonでみる</a>
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

</div>
</div>
<script type="text/javascript" src="/js/wishlist.js"></script>
@stop

@section('more')
    <!-- add wishlist dialog -->
    <div class="dialog" id="settingWishlistDialog">
        <h5 class="mdl-title">設定</h5>

        <span class="error">既に使用されている名前です</span> 
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" type="text" id="setting-name" name="{{$titleid}}" value="{{$selectedName}}" maxlength="20" />
            <label class="mdl-textfield__label" for="select-section">リスト名をを変更…</label>
        </div>

    <div class="middle">
      <div style="display:none" id="setting-loading"><div id="setting-loading-image" class="mdl-spinner mdl-js-spinner is-active"></div>loading</div>
      <button type="button" id="setting-rename" class="mdl-button mdl-button--raised mdl-button--colored" style="width:80px">変更</button>
      <button type="button" id="setting-delete" class="mdl-button mdl-button--raised mdl-button--colored mdl-button--accent" style="width:80px">削除</button>
      <button type="button" class="mdl-button mdl-button--raised close" style="width:80px">閉じる</button>
    </div>


    </div>

@stop
