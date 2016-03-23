<!DOCTYPE HTML>
<html>
<head>
	<tite>Amaon itemsearchのお試し</tite>
</head>
<body>
@foreach ($results as $item)
	<h5>{{ $item["title"]}}</h5>
	<img src="{{ $item['image']}}">
@endforeach
</body>
</html>