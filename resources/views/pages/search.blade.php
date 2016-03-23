<!DOCTYPE HTML>
<html>
<head>
	<tite>Amaon itemsearchのお試し</tite>
</head>
<body>
@foreach ($results as $item)
	<h5>{{ $item["title"]}}</h5>
	<a href="/item/{{ $item['isbn'] }}"> <img src="{{ $item['image']}}"></a>
@endforeach

<!-- ページャ -->
@foreach ($pages as $page)
	<a href="/search/{{$keyword}}/{{$page}}">{{$page}}</a>
@endforeach
</body>
</html>