<!DOCTYPE HTML>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="A portfolio template that uses Material Design Lite.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title>MDL-Static Website</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
<link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.blue_grey-light_blue.min.css" />    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <script type="text/javascript" src="js/all.js"></script>
</head>
</head>
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
        <header class="mdl-layout__header">
            <div class="mdl-layout__header-row doc-logo-row mdl-layout--large-screen-only">
                <span class="mdl-layout__title">
                    <span class="mdl-layout__title">Simple portfolio website</span>
                </span>
            </div>
            <div class="mdl-layout__header-row doc-navigation-row mdl-layout__header--waterfall">
                <nav class="mdl-navigation mdl-typography--body-1-force-preferred-font">
                    <a class="mdl-navigation__link is-active" href="/home">Memo</a>
                    <a class="mdl-navigation__link" href="/archive">Archive</a>
                    <a class="mdl-navigation__link" href="/search">Search</a>
                </nav>
            </div>
        </header>
        <main class="mdl-layout__content doc-main">
            <!-- search -->
            <div class="mdl-grid portfolio-max-width">
                <div class="mdl-cell mdl-cell--12-col  mdl-card doc-search-card ">
                <form action="#">
                        <div class="mdl-cell mdl-cell--12-col">
                            <div class="doc-search-field">
                            <input class="doc-search-text" type="text" id="card_search" name="search" placeholder="本を検索">
                            <button class="doc-search-button mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
                                <i class="material-icons">search</i>
                            </button>
                        </div>
                </form>
                </div>
            </div>

            @foreach($notes as $note)
            <section class="section--center">
            <div class="mdl-grid portfolio-max-width">
                <div class="mdl-grid mdl-cell mdl-cell--12-col  mdl-card mdl-shadow--4dp">
                    <div class="mdl-card__media mdl-cell--2-col mdl-cell--1-col-phone">
                        <img class="book-image" src=" {{ $note->image_url }}" border="0" alt="" style="max-width:113px;">
                    </div>
                    <div class="mdl-cell mdl-cell--10-col mdl-cell--3-col-phone">
                        <h2 class="mdl-card__title-text">{{ $note->title }}</h2>
                        <div class="mdl-card__supporting-text padding-top">
                            <span>{{ $note->author }}</span>
                        </div>
                            @foreach($note->comments as $comment)
                            <div class="doc-about-card section__text mdl-card__supporting-text no-left-padding">
                                <p>{{ $comment["comment"]}} </p>
                                <span>{{ $comment["created_at"] }}</span>
                            </div>
                            @endforeach
                    </div>
                </div>
            </div>
            </section>
            @endforeach
        </main>
</body>
</html>