@extends('common.header')
@section('content')
<main class="mdl-layout__content">
    <!-- edit -->
    <form action="/memo/edit/{{$id}}/save" method="post">
        <div class="mdl-grid mdl-cell mdl-cell--12-col">
            <div class="mdl-cell mdl-cell--12-col"><i class="material-icons">mode_edit</i>Edit</div>
            <div class="mdl-cell mdl-cell--12-col">P. <input type="number" name="page" id="page" maxlength="5" class="doc-page-field" value="{{$page}}"></div>
    	   <textarea class="mdl-cell mdl-cell--12-col mdl-cell--4-col-phone doc-textarea" type="text" id="quote" name="quote" rows="5" placeholder="引用を記録…">{{$quote}}</textarea>
           <textarea class="mdl-cell mdl-cell--12-col mdl-cell--4-col-phone doc-textarea" type="text" id="note" name="note" rows="5" placeholder="メモを記録…">{{$note}}</textarea>
            <div class="mdl-cell mdl-cell--10-col mdl-layout--large-screen-only"></div>
            <button  id="edit" type="submit" class="mdl-cell mdl-cell--2-col mdl-cell--4-col-phone mdl-cell--3-col-tablet doc-search-button mdl-button mdl-js-button mdl-button--raised mdl-button--colored"
                name="edit">
                        Save
            </button>
        </div>
        {{ csrf_field() }}
        <input type="hidden" id="id" name="id" value="{{ $id }}">
    </form>
    <hr class="doc-horizontal">
</main>
<script type="text/javascript" src="/js/autosize.min.js"></script>
@stop