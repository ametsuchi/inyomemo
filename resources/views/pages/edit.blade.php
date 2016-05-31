@extends('common.base')
@section('content')
<div class="max-width center">
	        <div class="mdl-grid mdl-cell mdl-cell--12-col">
            <div class="mdl-cell mdl-cell--12-col left"><i class="material-icons">mode_edit</i>Edit</div>
            <div class="mdl-cell mdl-cell--12-col left">P. <input type="number" name="page" id="page" maxlength="5" class="doc-page-field" value="{{$page}}"></div>
    	    <textarea class="mdl-cell mdl-cell--12-col mdl-cell--4-col-phone doc-textarea" type="text" id="quote" name="quote" rows="5" placeholder="引用を記録…">{{$quote}}</textarea>
            <textarea class="mdl-cell mdl-cell--12-col mdl-cell--4-col-phone doc-textarea" type="text" id="note" name="note" rows="5" placeholder="メモを記録…">{{$note}}</textarea>
            <div class="mdl-cell mdl-cell--10-col mdl-layout--large-screen-only"></div>
            <button  id="edit" type="button" class="mdl-cell mdl-cell--2-col mdl-cell--4-col-phone mdl-cell--3-col-tablet doc-search-button mdl-button mdl-js-button mdl-button--raised mdl-button--colored"
                name="edit">
                        Save
            </button>
            <div id="edit_saving" style="display:none">
            <div id="saving_image" class="mdl-spinner mdl-js-spinner"></div>Saving...
            </div>
        </div>
        <input type="hidden" id="id" name="id" value="{{ $id }}">
        <input type="hidden" id="isbn" name="isbn" value="{{ $isbn }}">
        <input type="hidden" id="title" name="title" value="{{ $title }}">
        <input type="hidden" id="author" name="author" value="{{ $author }}">
    
    <hr class="doc-horizontal">

	<script type="text/javascript" src="/js/autosize.min.js"></script>
	<script type="text/javascript" src="/js/edit.js"></script>
    <script type="text/javascript">
    $(function(){
        document.title = "{{$title}} - bkim";
    });
    </script>
</div>
@stop