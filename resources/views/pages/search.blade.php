<!DOCTYPE HTML>
<html>
<head>
	<tite>Amaon itemsearchのお試し</tite>
</head>
<body>
@foreach ($results as $item)
	<h5>{{ $item["title"] }}</h5>
	<h6>{{ $item["author"] }}</h6>
	<a href="/item/{{ $item['isbn'] }}"> <img src="{{ $item['image']}}"></a>
@endforeach

<!-- ページャ(仮) -->
@foreach ($pages as $page)
	<a href="/searchbooklists/{{$page}}?keyword={{$keyword}}">{{$page}}</a>
@endforeach
</body>
</html>