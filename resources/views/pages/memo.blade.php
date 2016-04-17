@extends('common.header')
@section('content')
<main class="mdl-layout__content">
            <div class="mdl-grid mdl-cell mdl-cell--12-col" >
                <div class="mdl-cell--2-col mdl-cell--1-col-phone">
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
            <div class="mdl-cell mdl-cell--12-col">P. <input type="number" name="page" id="page" maxlength="5"></div>
    	   <textarea class="mdl-cell mdl-cell--12-col mdl-cell--4-col-phone doc-textarea" type="text" id="quote" name="quote" rows="5" placeholder="引用を記録…"></textarea>
           <textarea class="mdl-cell mdl-cell--12-col mdl-cell--4-col-phone doc-textarea" type="text" id="note" name="note" rows="5" placeholder="メモを記録…"></textarea>
            <div class="mdl-cell mdl-cell--10-col mdl-layout--large-screen-only"></div>
            <button  id="save" type="button" class="mdl-cell mdl-cell--2-col mdl-cell--4-col-phone mdl-cell--3-col-tablet doc-search-button mdl-button mdl-js-button mdl-button--raised mdl-button--colored"
                name="save">
                        Save
            </button>
        </div>
        <input type="hidden" id="isbn" name="isbn" value="{{ $isbn }}">
    <hr class="doc-horizontal">
    <section id="memo_section">
    @foreach($notes as $note)
    <div class="mdl-grid mdl-cell mdl-cell--12-col">
        @if($note->page != 0)
        <div class="mdl-cell mdl-cell--12-col doc-detail-page">
            P.{{$note->page}}
        </div>
        @endif
        <div class="mdl-cell mdl-cell--12-col">
            @if(strlen($note->quote) != 0)
            <div class="doc-quote-div">
            <i class="material-icons doc-quote-icon">format_quote</i>
            <div class="doc-quote-text">
                {!! nl2br(e($note->quote)) !!}
            </div>
            </div>
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
    <div class="mdl-grid mdl-cell mdl-cell--12-col">
        <div class="mdl-cell mdl-cell--12-col doc-detail-page" id="add_page">
        <!-- page -->
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


</main>
<meta name="csrf-token" content="{{ csrf_token() }}">
<script type="text/javascript" src="/js/autosize.min.js"></script>
<script type="text/javascript" src="/js/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="/js/memo.js"></script>
@stop