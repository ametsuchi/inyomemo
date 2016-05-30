@extends('common.header')
@section('content')
<main class="">
            <div class="mdl-grid mdl-cell mdl-cell--12-col" >
                <div class="mdl-cell--2-col mdl-cell--1-col-phone doc-image-div">
                    <a class="" href="{{ $amazon_url }}" id="amazon_link"><img class="doc-detail-image book-image" src=" {{ $image_url }}" border="0" alt="{{$title}}" style="max-width:113px;" id="book_image"></a>
                </div>
                <div class="mdl-cell mdl-cell--10-col mdl-cell--3-col-phone">
                    <a href="{{ $amazon_url }}"><span class="mdl-card__title-text" id="title">{{ $title }}</span></a>
                    <div class="mdl-card__supporting-text padding-top">
                        <span id="author">{{ $author }}</span>
                    </div>
                </div>
            </div>

    <hr class="doc-horizontal">
    <!-- edit -->
        <div class="mdl-grid mdl-cell mdl-cell--12-col">
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
    <section id="memo_section">
    @foreach($notes as $note)
    <div class="mdl-grid mdl-cell mdl-cell--12-col note" id="note{{$note->id}}">
        <div class="mdl-cell mdl-cell--4-col mdl-cell--2-col-phone doc-detail-page">
        @if($note->page != 0)
            P.{{$note->page}}
        @endif
        </div>
        <div class="mdl-cell mdl-cell--8-col mdl-layout--large-screen-only doc-detail-icon" style="text-align:right">
            <button class="mdl-button mdl-js-button mdl-button--icon doc-edit" id="edit{{$note->id}}" name="edit{{$note->id}}"><i class="material-icons">mode_edit</i></button>
            <button class="mdl-button mdl-js-button mdl-button--icon doc-delete" id="delete{{$note->id}}" name="delete{{$note->id}}"><i class="material-icons">delete_forever</i></button>
        </div>
        <div class="mdl-cell mdl-cell--2-col-phone mdl-layout--small-screen-only doc-memo-memu">
            <button class="mdl-button mdl-js-button mdl-button--icon doc-mobile-edit doc-more" id="more{{$note->id}}"><i class="material-icons">more_vert</i></button>           
        </div>
        <!-- row -->
        <div class="mdl-cell mdl-cell--12-col">
            @if(strlen($note->quote) != 0)
            <blockquote>
                <span>{!! nl2br(e($note->quote)) !!}</span>
            </blockquote>
            @endif
        </div>
        <div class="mdl-cell mdl-cell--12-col doc-detail-note">
            {!! nl2br(e($note->note)) !!}
        </div>
        <div class="mdl-cell mdl-cell--12-col">
            <div class="doc-time">{{ date_format($note->created_at,'Y/m/d H:i')}}</div>
        </div>
        <hr class="doc-detail-hr">
    </div>
    @endforeach
    </section>
    <!-- post用 -->
    <div id="add_div" style="display:none">
    <div class="mdl-grid mdl-cell mdl-cell--12-col note" id="add_note">
        <div class="mdl-cell mdl-cell--4-col mdl-cell--2-col-phone doc-detail-page" id="add_page">
        <!-- page -->
        </div>
        <div class="mdl-cell mdl-cell--8-col doc-detail-icon mdl-layout--large-screen-only" >
            <button class="mdl-button mdl-js-button mdl-button--icon doc-edit" id="add_edit" name="add_edit"><i class="material-icons">mode_edit</i></button>
            <button class="mdl-button mdl-js-button mdl-button--icon doc-delete" id="add_delete" name="add_delete"><i class="material-icons">delete_forever</i></button>
        </div>
        <div class="mdl-cell mdl-cell--2-col-phone mdl-layout--small-screen-only doc-memo-memu">
            <button class="mdl-button mdl-js-button mdl-button--icon doc-mobile-edit doc-more" id="add_more"><i class="material-icons">more_vert</i></button>           
        </div>
        <div class="mdl-cell mdl-cell--12-col" id="add_quote_div">
            <div class="doc-quote-div">
            <i class="material-icons doc-quote-icon">format_quote</i>
            <div class="doc-quote-text" id="add_quote">
                <!-- quote -->
            </div>
            </div>
        </div>
        <div class="mdl-cell mdl-cell--12-col doc-detail-note" id="add_note">
            <!-- note -->
        </div>
        <div class="mdl-cell mdl-cell--12-col">
            <div class="doc-time" id="add_time"><!-- date 'Y/m/d H:i'--></div>
        </div>
        <hr class="doc-detail-hr">
    </div>
    </div>

<!-- dialog -->
<dialog class="mdl-dialog doc-more-dialog" id="moreDialog">
    <div class="mdl-grid">
    <div class="mdl-cell mdl-cell--3-col-phone"></div>
    <div class="doc-dialog-close mdl-cell mdl-cell--1-col-phone">
        <button class="mdl-button mdl-js-button mdl-button--icon doc-close-button" id="dialog-close">
            <i class="material-icons">close</i>
        </button>
    </div>
    </div>
    <div class="mdl-grid">
    <button type="button" class="mdl-cell mdl-cell--2-col mdl-cell--4-col-phone mdl-cell--6-col-tablet mdl-button mdl-js-button doc-edit"
                id="dialog-edit" name="edit" style="display:block">編集</button>
    <button type="button" class="mdl-cell mdl-cell--2-col mdl-cell--4-col-phone mdl-cell--6-col-tablet mdl-button mdl-js-button doc-delete"
               id="dialog-delete" name="delete" style="display:block">削除</button>
    </div>
</dialog>
</main>
<script type="text/javascript" src="/js/autosize.min.js"></script>
<script type="text/javascript" src="/js/memo.js"></script>
@stop