@extends('common.header')
@section('content')
<main class="mdl-layout__content">
            <div class="mdl-grid mdl-cell mdl-cell--12-col" >
                <div class="mdl-cell--2-col mdl-cell--1-col-phone">
                    <a class="" href="{{ $amazon_url }}"><img class="doc-detail-image book-image" src=" {{ $image_url }}" border="0" alt="" style="max-width:113px;"></a>
                </div>
                <div class="mdl-cell mdl-cell--10-col mdl-cell--3-col-phone">
                    <a href="{{ $amazon_url }}"><span class="mdl-card__title-text">{{ $title }}</span></a>
                    <div class="mdl-card__supporting-text padding-top">
                        <span>{{ $author }}</span>
                    </div>
                </div>
            </div>

    <hr class="doc-horizontal">
    <!-- edit -->
        <form action="/memo/edit" method="post">
        <div class="mdl-grid mdl-cell mdl-cell--12-col">
            <div class="mdl-cell mdl-cell--12-col"><i class="material-icons">mode_edit</i>Memo</div>
    	   <textarea class="mdl-cell mdl-cell--12-col mdl-cell--4-col-phone doc-textarea" type="text" id="quote" name="quote" rows="5" placeholder="引用を記録…"></textarea>
           <textarea class="mdl-cell mdl-cell--12-col mdl-cell--4-col-phone doc-textarea" type="text" name="note" rows="5" placeholder="メモを記録…"></textarea>
            <div class="mdl-cell mdl-cell--10-col mdl-layout--large-screen-only"></div>
            <button  type="submit" class="mdl-cell mdl-cell--2-col mdl-cell--4-col-phone mdl-cell--3-col-tablet doc-search-button mdl-button mdl-js-button mdl-button--raised mdl-button--colored"
                name="save">
                        Save
            </button>
        </div>
        {{ csrf_field() }}
        <input type="hidden" name="title" value="{{ $title }}">
        <input type="hidden" name="author" value="{{ $author }}">
        <input type="hidden" name="isbn" value="{{ $isbn }}">
        <input type="hidden" name="image_url" value="{{ $image_url }}">
        <input type="hidden" name="amazon_url" value="{{ $amazon_url }}">
        </form>
    <hr class="doc-horizontal">
    @foreach($notes as $note)
    <div class="mdl-grid mdl-cell mdl-cell--12-col">
        <div class="mdl-cell mdl-cell--12-col">
            <div class="doc-quote-div">
            <i class="material-icons doc-quote-icon">format_quote</i>
            <div class="doc-quote-text">
                {!! nl2br(e($note->quote)) !!}
            </div>
            </div>
        </div>
        <div class="mdl-cell mdl-cell--12-col doc-detail-text">
            {!! nl2br(e($note->note)) !!}
        </div>
        <div class="mdl-cell mdl-cell--12-col">
            <div class="doc-time">{{ date_format($note->created_at,'Y/m/d H:i')}}</div>
        </div>
        <hr class="doc-detail-hr">
    </div>
    @endforeach

</main>
<script type="text/javascript" src="/js/autosize.min.js"></script>
<script type="text/javascript">
    autosize(document.querySelectorAll('textarea'));
</script>
@stop