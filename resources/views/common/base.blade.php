<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="A portfolio template that uses Material Design Lite.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>bkim -読書家のためのシンプルなメモアプリ</title>
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
<div class="mdl-layout mdl-js-layout mdl-layout--no-desktop-drawer-button">
  <header class="mdl-layout__header mdl-layout__header--scroll">
    <div class="mdl-layout__header-row">
      <!-- Title -->
      <span class="mdl-layout-title doc-title"><i class="fa fa-book"></i>bkim</span>
      <!-- Add spacer, to align navigation to the right -->
      <div class="mdl-layout-spacer"></div>
      <!-- search -->
      本を検索
      <div style="margin-right: 15px;">
      <form action="#">
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable">
          <label class="mdl-button mdl-js-button mdl-button--icon" for="sample6">
            <i class="material-icons">search</i>
          </label>
          <div class="mdl-textfield__expandable-holder">
            <input class="mdl-textfield__input" type="text" id="sample6">
            <label class="mdl-textfield__label" for="sample-expandable">Expandable Input</label>
          </div>
        </div>
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
        <a herf="/home" class="mdl-layout__tab is-active">メモ</a>
        <a href="/wishlist/0" class="mdl-layout__tab">ほしいものリスト</a>
        <a href="/archive" class="mdl-layout__tab">読んだ本</a>
        <a href="/wordsearch" class="mdl-layout__tab">検索</a>
  </header>
  <div class="mdl-layout__drawer">
    <nav class="mdl-navigation">
        <div class="doc-drawer-header">
            <div class="doc-drawer-image">
                <img src="{{$user->avatar}}">
            </div>
            <div class="doc-drawer-name">
                {{$user->name}}
            </div>
        </div>
      <a class="mdl-navigation__link" href="">メモ</a>
      <a class="mdl-navigation__link" href="">ほしいものリスト</a>
      <a class="mdl-navigation__link" href="">読んだ本</a>
      <a class="mdl-navigation__link" href="">検索</a>
      <a class="mdl-navigation__link" href="">Evernoteと連携</a>
      <a class="mdl-navigation__link" href="">ログアウト</a>
    </nav>
  </div>
  <main class="mdl-layout__content">
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
      お問合せ
      </div>
  </footer>
</div>
</body>