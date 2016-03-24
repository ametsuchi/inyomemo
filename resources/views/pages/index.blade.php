<!DOCTYPE HTML>
<html>
<head>
	<tite>Amaon itemlookupのお試し</tite>
</head>
<body>
	<form action="/search" method="post">
		<input type="text" name="keyword">
		<input type="submit">
	</form>

	<table>
	@foreach ($notes as $note)
		<tr>
			<th><a href="/item/{{ $note->isbn }}"><img src="{{ $note->image }}"></a></th>
			<th>
				<div style="border:solid 1px">
				<pre>{{ $note->quote }}</pre>
				</div>
				<pre>{{ $note->note }}</pre>
				<a href="/item/{{ $note->isbn }}">{{ $note->title }}</a>
			</th>
		</tr>
	@endforeach
</body>
</html>