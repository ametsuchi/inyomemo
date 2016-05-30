@extends('common.base')
@section('content')
<div class="max-width center doc-memo">

  <div class="mdl-grid mdl-cell mdl-cell--12-col" >
    <div class="mdl-cell--2-col mdl-cell--1-col-phone doc-image-div">
        <a class="" href="{{ $amazon_url }}" id="amazon_link"><img src=" {{ $image_url }}" border="0" alt="{{$title}}" style="max-width:113px;" id="book_image"></a>
    </div>
    <div class="mdl-cell mdl-cell--10-col mdl-cell--3-col-phone">
        <a href="{{ $amazon_url }}"><span class="mdl-card__title-text" id="title">{{ $title }}</span></a>
            <div class="mdl-card__supporting-text">
              <span id="author">{{ $author }}</span>
            </div>
    </div>
  </div>

  <hr class="doc-horizontal">
  <!-- edit -->
  <div class="mdl-grid mdl-cell mdl-cell--12-col left">
    <div class="mdl-cell mdl-cell--12-col"><i class="material-icons">mode_edit</i>Memo</div>
      <div class="mdl-cell mdl-cell--12-col">P. <input type="number" name="page" id="page" maxlength="5" class="doc-page-field"></div>
        <textarea class="mdl-cell mdl-cell--12-col mdl-cell--4-col-phone doc-textarea" type="text" id="quote" name="quote" rows="5" placeholder="引用を記録…"></textarea>
        <textarea class="mdl-cell mdl-cell--12-col mdl-cell--4-col-phone doc-textarea" type="text" id="note" name="note" rows="5" placeholder="メモを記録…"></textarea>
        <div class="mdl-cell mdl-cell--10-col mdl-layout--large-screen-only"></div>
            <button  id="save" type="button" class="mdl-cell mdl-cell--2-col mdl-cell--4-col-phone mdl-cell--3-col-tablet mdl-button mdl-js-button mdl-button--raised mdl-button--colored"
                name="save">
                        Save
            </button>
  </div>
  <input type="hidden" id="isbn" name="isbn" value="{{ $isbn }}">

  <hr class="doc-horizontal">
  
  <div id="memo-detail">
  <!-- memo -->
  @foreach ($notes as $note)
  <div class="doc-memo-detail mdl-color-text--blue-grey-900" id="note{{$note->id}}">
    <div class="doc-page">
    @if (!empty($note->page))
      P.{{$note->page}}
    @endif

      <div class="right mdl-color-text--grey-600">
        <a href="#memoDialog" name="menu{{$note->id}}" class="openEditDialog mdl-button mdl-js-button mdl-button--icon">
          <i class="material-icons">more_vert</i>
        </a>
      </div>
    </div>
    
    @if (!empty($note->quote))
    <blockquote>
      <span>{!! nl2br(e($note->quote)) !!}</span>
    </blockquote>
    @endif
    
    @if (!empty($note->note))
    <div>
        {!! nl2br(e($note->note)) !!}
    </div>
    @endif
    
    <div class="right">
        {{ date_format($note->created_at,'Y/m/d H:i')}}
      </div>
    <hr class="doc-horizontal">
  </div>
  @endforeach
  </div>

  <!-- post用 -->
  <div class="addDiv" id="add_div" >
  <div id="add" class="doc-memo-detail mdl-color-text--blue-grey-900" id="note{{$note->id}}">
    <div class="doc-page">
      <span id="add_page"><!-- page --></span>

      <div class="right mdl-color-text--grey-600" id="add_more">
        <a href="#memoDialog" name="menu" class="openEditDialog mdl-button mdl-js-button mdl-button--icon">
          <i class="material-icons">more_vert</i>
        </a>
      </div>
    </div>
    
    <blockquote id="add_quote">
      <span><!-- quote --></span>
    </blockquote>
    
    <div id="add_note">
      <!-- note -->
    </div>
    
    <div class="right" id="add_time">
        <!-- date -->
      </div>
    <hr class="doc-horizontal">
  </div>
  </div>

<span id="test">test</span>

</div>
<script type="text/javascript" src="/js/autosize.min.js"></script>
<script type="text/javascript" src="/js/memo.js"></script>
@stop