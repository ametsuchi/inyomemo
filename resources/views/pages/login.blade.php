<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="読書管理,読書メモ,Evernote">
    <meta name="description" content="読書家のためのシンプルなメモアプリです。Evernoteにバックアップすることもできます。">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.blue_grey-light_blue.min.css" />
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/login.css">
	<title>honmemo! -読書家のためのシンプルなメモアプリ</title>
</head>
<body>
<main>
<div class="social">
<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://books.honmemo.com" data-text="honmemo! -読書家のためのシンプルなメモアプリ">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
<a data-pocket-label="pocket" data-pocket-count="none" class="pocket-btn" data-lang="en"></a>
<script type="text/javascript">!function(d,i){if(!d.getElementById(i)){var j=d.createElement("script");j.id=i;j.src="https://widgets.getpocket.com/v1/j/btn.js?v=1";var w=d.getElementById(i);d.body.appendChild(j);}}(document,"pocket-btn-js");</script>
</div>
<!-- logo -->
<div class="logo">
	<i class="fa fa-book"></i><span>honmemo!</span>
</div>
<div class="subtitle">
	<span>読書家のためのシンプルなメモアプリ</span>
</div>

<div class="button button-top">
<button id="twitter" class="mdl-button mdl-js-button mdl-button--raised twitter"><i class="fa fa-twitter" aria-hidden="true"></i>Twiterアカウントでログイン</button>
</div>
<div class="button">
<button id="github" class="mdl-button mdl-js-button mdl-button--raised github"><i class="fa fa-github" aria-hidden="true"></i>GitHubアカウントでログイン</button>
</div>
<div class="center">
<input type="checkbox" name="remember" id="remember" checked="checked">
<label for="remember">次回からログインを省略</label><br/>
</div>


<!-- 説明とか -->
<div class="exsam">
	<div class="box">
		<i class="fa fa-pencil" aria-hidden="true"></i><br/>
		<span class="title">シンプルなメモアプリ</span>
		<p>読書中にメモを取りたい！でも書名をメモして表紙の写真を撮ってなんてめんどくさい…。そんな方のためのメモアプリです。読んだ本のページ・引用・感想などをすぐ書き込めます。書いたメモの全文検索も可能です。</p>
	</div>

	<div class="box">
		<i class="fa fa-cloud-upload" aria-hidden="true"></i><br/>
		<span class="title">Evernoteにバックアップ</span>
		<p>Evernoteと連携することで、書いたメモをリアルタイムでバックアップすることができます。</p>
	</div>

	<div class="box">
		<i class="fa fa-lock" aria-hidden="true"></i><br/>
		<span class="title">自分専用のメモスペース</span>
		<p>メモを書きたいけどほかの人に見られるのはいやだな…という方、honmemo!には公開機能はついておりません。プライベートなメモとしてお使いいただけます。</p>
	</div>
</div>
</main>
<footer class="mdl-mini-footer" id="footer">
	<div><a href="/inquiry">お問い合せ</a></div>
</footer>
<script type="text/javascript" src="/js/login.js"></script>
</body>
</html>