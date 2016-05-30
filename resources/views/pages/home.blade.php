@extends('common.base')
@section('content')
<div class="max-width center">
<h5 class="doc-page-title"><i class="fa fa-book"></i>最近読んだ本</h5>
 @foreach($notes as $note)
<section class="mdl-grid mdl-grid--no-spacing mdl-shadow--2dp doc-card">
            <header class="section__book-image">
              <img src="{{$note->image_url}}">
            </header>
            <div class="mdl-card mdl-cell section__text">
              <div class="mdl-card__supporting-text">
                <h5>{{$note->title}}</h5>
                <span>{{$note->author}}</span>
              </div>
              <div class="mdl-card__actions">
                <a href="/memo/{{$note->isbn}}" class="mdl-button">メモを編集</a>
              </div>
            </div>
          </section>
@endforeach
</div>
@stop
