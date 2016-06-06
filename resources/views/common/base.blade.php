<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="A portfolio template that uses Material Design Lite.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

@yield('title')

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.indigo-orange.min.css" />
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="/css/common.css">
    <script type="text/javascript" src="/js/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="/js/jquery.leanModal.min.js"></script>
    <script type="text/javascript" src="/js/all.js"></script>
    <script type="text/javascript" src="/js/header.js"></script>
</head>
<body>
<!-- moblie -->
<div class="mdl-layout mdl-js-layout  mdl-layout--fixed-header mdl-layout--small-screen-only mobile-header">
  <header class="mdl-layout__header">
    <div class="mdl-layout-icon"></div>
    <div class="mdl-layout__header-row">
      <span class="mdl-layout-title doc-title mobile-logo"><i class="fa fa-book"></i>honmemo!</span>
      <div class="mdl-layout-spacer"></div>
      <form action="/search" method="post">
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable">
          <label class="mdl-button mdl-js-button mdl-button--icon" for="exsearchMobile">
            <i class="material-icons">search</i>
          </label>
          <div class="mdl-textfield__expandable-holder">
            <input class="mdl-textfield__input" type="text" id="exsearchMobile" name="keyword"
             placeholder="本を検索">
          </div>
        </div> {{ csrf_field() }}
      </form>
    </div>
  </header>
</div>

<!-- desktop header -->
<div class="mdl-layout mdl-js-layout mdl-layout--no-desktop-drawer-button">
  <header class="mdl-layout__header mdl-layout__header--scroll">
    <div class="mdl-layout__header-row">
      <!-- Title -->
      <span class="mdl-layout-title doc-title"><i class="fa fa-book"></i>honmemo!</span>
      <!-- Add spacer, to align navigation to the right -->
      <div class="mdl-layout-spacer"></div>
      <!-- search -->
      本を検索
      <div style="margin-right: 15px;">
      <form action="/search" method="post">
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable">
          <label class="mdl-button mdl-js-button mdl-button--icon" for="exsearch">
            <i class="material-icons">search</i>
          </label>
          <div class="mdl-textfield__expandable-holder">
            <input class="mdl-textfield__input" type="text" id="exsearch" name="keyword">
          </div>
        </div> {{ csrf_field() }}
      </form>
      </div>
      <!-- user -->
      <div>
            <img class="doc-avator" src="{{$user->avatar}}">
            <span>{{$user->name}}</span>
            <button class="mdl-button mdl-js-button mdl-button--icon" id="submenu">
                <i class="material-icons">more_vert</i>
            </button>
            <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect"
                    for="submenu">
                    <li class="mdl-menu__item evernoteLink" id="evernoteShow"><i class="fa fa-cloud-upload"></i><a rel="leanModal" href="#evernoteDialog">Evernote連携</a></li>
                    <li class="mdl-menu__item" id="logout"><i class="fa fa-sign-out"></i>ログアウト</li>
            </ul>
      </div>
    </div>
    <!-- tab -->
    <div class="mdl-layout__tab-bar mdl-js-ripple-effect">
        <a href="/home" id="nav_memo" class="mdl-layout__tab">メモ</a>
        <a href="/wishlist/0" id="nav_wishlist" class="mdl-layout__tab">ほしいものリスト</a>
        <a href="/archive" id="nav_archive" class="mdl-layout__tab">読んだ本</a>
        <a href="/wordsearch" id="nav_search" class="mdl-layout__tab">検索</a>
  </header>
  <div class="mdl-layout__drawer mdl-color--blue-grey-900">
    <nav class="mdl-navigation">
        <div class="doc-drawer-header">
            <div class="doc-drawer-image">
                <img src="{{$user->avatar}}">
            </div>
            <div class="doc-drawer-name">
                {{$user->name}}
            </div>
        </div>
      <a class="mdl-navigation__link" href="/home">メモ</a>
      <a class="mdl-navigation__link" href="/wishlist/0">ほしいものリスト</a>
      <a class="mdl-navigation__link" href="/archive">読んだ本</a>
      <a class="mdl-navigation__link" href="/wordsearch">検索</a>
      <a class="mdl-navigation__link" a rel="leanModal" href="#evernoteDialog">Evernoteと連携</a>
      <a class="mdl-navigation__link" href="/logout">ログアウト</a>
    </nav>
  </div>
  <main class="mdl-layout__content mdl-color--grey-100">
    <div class="page-content">
    @yield('content')
    </div>
  </main>
    <!-- dialog -->
    <div class="dialog" id="evernoteDialog">
        <h5 class="mdl-title">Evernoteと連携を許可する</h5>
        <p>
        投稿された内容をEvernoteにバックアップします。<br />
        </p>
        <div id="loading">
            <div id="loading_image" class="mdl-spinner mdl-js-spinner"></div>
        </div>
        <div class="right">
            <button type="button" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored evernoteLinkButton"　id="evernoteLinkButton" onclick="ever();">連携</button>
        </div>
    </div>

    <!-- memo dialog -->
    <div class="dialog" id="memoDialog">
        <h5 class="mdl-title">メモを編集</h5>
        <div class="doc-dialog-button">
            <button type="button" class="mdl-button mdl-js-button mdl-button--raised mdl-color--grey-500 mdl-color-text--white doc-edit" name="edit">編集</button>
        </div>
        <div class="doc-dialog-button">
            <button type="button" class="mdl-button mdl-js-button mdl-button--raised mdl-color--grey-500 mdl-color-text--white doc-delete" name="delete">削除</button>
        </div>
    </div>

 @yield('more')

  <footer class="mdl-mini-footer">
      <div class="mdl-mini-footer__left-section">
      <a href="">お問合せ</a>
      </div>
  </footer>
</div>
</body>