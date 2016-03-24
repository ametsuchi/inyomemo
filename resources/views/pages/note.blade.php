<!DOCTYPE HTML>
<html>
<head>
	<tite>Amaon itemlookupのお試し</tite>
</head>
<body>
<h3>{{$title}}</h3>
<img src="{{$mimage}}">
<br>
<form action="/notesubmit" method="post"> 
ページ
<input type="text" name="page" maxlength="4">
<br>
引用<br>
<textarea name="quote" rows="10" cols="100">
	
</textarea>
<br>メモ<br>
<textarea name="note" rows="10" cols="100">
	
</textarea>
<input type="submit">
<input type="hidden" name="title" value="{{$title}}">
<input type="hidden" name="author" value="{{$author}}">
<input type="hidden" name="isbn" value="{{$isbn}}">
</form>
<br>
@foreach ($notes as $note)
	<hr> 
	P.{{ $note->page }}<br>
	<pre>{{ $note->quote }}</pre><br>
	<pre>{{ $note->note }}</pre><br>
	{{ $note->created_at }} <br>
	
@endforeach
</body>
</html>