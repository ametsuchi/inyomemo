@extends('common.header')
@section('content')
<main class="mdl-layout__content doc-main">
    <div class="mdl-grid portfolio-max-width" style="background-color:white">
            <div class="mdl-grid mdl-cell mdl-cell--12-col" >
                <div class="mdl-card__media mdl-cell--2-col mdl-cell--1-col-phone">
                    <img class="book-image" src=" {{ $image_url }}" border="0" alt="" style="max-width:113px;">
                </div>
                <div class="mdl-cell mdl-cell--10-col mdl-cell--3-col-phone">
                    <h2 class="mdl-card__title-text">{{ $title }}</h2>
                    <div class="mdl-card__supporting-text padding-top">
                        <span>{{ $author }}</span>
                    </div>
                </div>
            </div>


    <!-- edit -->
    <h6>引用</h6>
    <form action="#">
    	<input type="">
    	<textarea class="mdl-textfield__input" type="text" rows="5" name="quote"></textarea>
    	<textarea class="mdl-textfield__input" type="text" rows="5" name="note"></textarea>
    </form>
    <!-- notes -->



    </div>


</main>
@stop