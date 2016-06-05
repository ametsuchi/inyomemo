<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="A portfolio template that uses Material Design Lite.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.blue_grey-light_blue.min.css" />
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/login.css">
	<title>honmemo! -読書家のためのシンプルなメモアプリ</title>
</head>
<body>
<div class="wapper">

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
<input type="checkbox" name="remember" id="remember">
<label for="remember">ログインしたままにする</label>
</div>

<!-- 説明とか -->
<div class="exsam">
	<div class="box">
		<i class="fa fa-pencil" aria-hidden="true"></i><br/>
		<span class="title">シンプルなメモアプリ</span>
		<p>読んだ本のページ・引用・感想などを書き込めます。書いたメモの全文検索も可能です。</p>
	</div>

	<div class="box">
		<i class="fa fa-cloud-upload" aria-hidden="true"></i><br/>
		<span class="title">Evernoteにバックアップ</span>
		<p>Evernoteと連携することで、書いたメモをリアルタイムでバックアップすることができます。</p>
	</div>

	<div class="box">
		<i class="fa fa-lock" aria-hidden="true"></i><br/>
		<span class="title">自分専用のメモスペース</span>
		<p>公開機能はついていません。プライベートなメモとしてお使いいただけます。</p>
	</div>
</div>
<!-- 何か入れる予定。。。 -->
<div>
<br /><br /><br />
</div>

<footer class="mdl-mini-footer" id="footer">
	<div><a href="/#">お問い合せ</a></div>
</footer>
<!--
<a href="/auth/github" id="github">GitHubでログイン</a>
<br><a href="/auth/twitter">Twitterでログイン</a>
 <input type="checkbox" name="remember"> ログインを継続する
-->
</div>
<script type="text/javascript" src="/js/login.js"></script>
</body>
</html>