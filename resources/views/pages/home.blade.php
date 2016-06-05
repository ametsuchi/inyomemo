@extends('common.base')
@section('content')
<div class="max-width center">
<div class="home-main">
<!-- search -->
<form action="/search" method="post">
            {{ csrf_field() }}
              <div class="mdl-shadow--2dp doc-serach-card">
                <div class="mdl-grid mdl-grid--no-spacing ">
                        <input class="mdl-cell mdl-cell--10-col mdl-cell--6-col-tablet mdl-cell--3-col-phone" type="text" id="card_search" name="keyword" placeholder="本を検索">

                        <button type="submit" class="mdl-cell mdl-cell--2-col mdl-button mdl-js-button mdl-button--raised mdl-button--colored
                         mdl-cell--2-col-tablet mdl-cell--1-col-phone">
                            <i class="material-icons search">search</i>
                        </button>
                </div>
              </div>
</form>


<h5 class="doc-page-title"><i class="fa fa-book"></i>最近読んだ本</h5>
 @foreach($notes as $note)
<section class="mdl-grid mdl-grid--no-spacing mdl-shadow--2dp doc-card">
            <header class="section__book-image">
              
              <img src="{{$note->image_url}}" >
              
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
</div>
</div>
<script type="text/javascript">
  $(function(){
    document.title = "最近読んだ本 - honmemo!";
  });
</script>
@stop
