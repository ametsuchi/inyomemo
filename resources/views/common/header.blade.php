<!DOCTYPE HTML>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="A portfolio template that uses Material Design Lite.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>bkim</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.blue_grey-light_blue.min.css" />
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/style.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <script type="text/javascript" src="/js/all.js"></script>
    <script type="text/javascript" src="/js/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="/js/header.js"></script>
</head>
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
        <header class="mdl-layout__header">
            <div class="mdl-layout__header-row doc-logo-row ">
                <span class="mdl-layout__title doc-title"><i class="fa fa-book" style="margin-right:3px;"></i>bkim</span>
                <div class="doc-header-user">
                    <img class="doc-avator" src="{{$user->avatar}}">
                    <span>{{$user->name}}</span>
            <button id="demo-menu-lower-right"
                class="mdl-button mdl-js-button mdl-button--icon">
                <i class="material-icons">more_vert</i>
                </button>

                <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect"
                    for="demo-menu-lower-right">
                    <li class="mdl-menu__item" id="evernote"><i class="fa fa-cloud-upload"></i>Evernote連携</li>
                    <li class="mdl-menu__item" id="logout"><i class="fa fa-sign-out"></i>ログアウト</li>
                    </ul>
                </div>
            </div>
            <div class="mdl-layout__header-row doc-navigation-row mdl-layout__header--waterfall">
                <nav class="mdl-navigation mdl-typography--body-1-force-preferred-font">
                    <a class="mdl-navigation__link" id="nav_memo" href="/home">メモ</a>
                    <a class="mdl-navigation__link" id="nav_wishlist" href="/wishlist">ほしいものリスト</a>
                    <a class="mdl-navigation__link" id="nav_archive" href="/archive">読んだ本一覧</a>
                    <a class="mdl-navigation__link" id="nav_search" href="/archive/search">検索</a>
                </nav>
            </div>

  <!-- evernote連携ダイアログ -->
  <dialog class="mdl-dialog">
    <h5 class="mdl-dialog__title">Evernoteと連携</h5>
    <div class="mdl-dialog__content">
      <p>
        投稿された内容をEvernoteにバックアップします。<br />
      </p>
        <div id="loading">
        <div id="loading_image" class="mdl-spinner mdl-js-spinner is-active"></div>loading...
        </div>
        <a href="" id="evernote_oauth">連携を許可</a>
    </div>
    <div class="mdl-dialog__actions">
      <button type="button" class="mdl-button close">閉じる</button>
    </div>
  </dialog>

        </header>
        @yield('content')
</body>
</html>