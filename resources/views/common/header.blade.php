<!DOCTYPE HTML>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="A portfolio template that uses Material Design Lite.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title>引用メモ（仮）</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.blue_grey-light_blue.min.css" />
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/style.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <script type="text/javascript" src="/js/all.js"></script>
</head>
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
        <header class="mdl-layout__header">
            <div class="mdl-layout__header-row doc-logo-row mdl-layout--large-screen-only">
                <span class="mdl-layout__title">
                    <span class="mdl-layout__title"><i class="fa fa-book" style="margin-right:3px;"></i>bkim</span>
                </span>
            </div>
            <div class="mdl-layout__header-row doc-navigation-row mdl-layout__header--waterfall">
                <nav class="mdl-navigation mdl-typography--body-1-force-preferred-font">
                    <a class="mdl-navigation__link is-active" href="/home">メモ</a>
                    <a class="mdl-navigation__link" href="/wishlist/show">ほしいものリスト</a>
                    <a class="mdl-navigation__link" href="/archive">読んだ本一覧</a>
                    <a class="mdl-navigation__link" href="/search">検索</a>
                </nav>
            </div>
        </header>
        @yield('content')
</body>
</html>