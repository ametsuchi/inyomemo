<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="A portfolio template that uses Material Design Lite.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>お問合せ - honmemo!</title>
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
<!-- desktop header -->
<div class="mdl-layout mdl-js-layout mdl-layout--no-desktop-drawer-button">
  <header class="mdl-layout__header mdl-layout--fixed-header">
    <div class="mdl-layout__header-row">
      <!-- Title -->
      <span class="mdl-layout-title doc-title"><i class="fa fa-book"></i>honmemo!</span>
      <!-- Add spacer, to align navigation to the right -->
      <div class="mdl-layout-spacer"></div>
    </div>
    <!-- tab -->
  </header>
  <main class="mdl-layout__content">
    <div class="page-content" style="margin-top:20px;">
    <div class="max-width center">
      <form action="/inquiry/post" method="post">
      <div class="left" style="margin-left:10px;">
      <h6>お問合せ</h6>
        <select name="type">
          <option value="1">障害報告</option>
          <option value="2">お問合せ</option>
        </select><br/><br/>
    <textarea cols="38" rows="10" name="comment"></textarea><br/>
    <input type="submit" class="mdl-button mdl-button--colored mdl-button--raised" value="送信" style="margin-top:10px;"><br/><br/>
    または<a href="http://honmemo.hatenablog.com/">開発ブログ</a>まで
 {{ csrf_field() }}
    <br/><br/>
    {{$msg}}
      </div>
    </form>
    </div>

    </div>
  </main>
</div>
</body>