<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Elasticsearch') }}</title>
	<link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous"/>
    <style>
    	body {
		    line-height: 1;
		    position: relative;
		    /*font-family: Arial, sans-serif;*/
		    font-size: 12px;
		    overflow: visible !important;
		    /*font-family: 'Noto Serif',serif;*/
		}
    	.title{
    		margin-top: 30px !important;
    		margin-bottom: 20px !important;
    		text-align: center;
    		font-weight: 700;
    		line-height: 1.5;
		    font-family: 'Noto Serif',serif;
    	}
    	.title h1{
    		font-size: 45px;
    		letter-spacing: -1px;
    	}
    	.short_content{
		    text-align: center;
		    position: relative;
		    font-size: 1.1rem;
		    line-height: 1.6;
		    font-weight: 700;
		    font-family: 'Noto Serif',serif;
    	}
    	.content{
    		font-size: 1.1rem;
    		line-height: 1.6;
    		margin-top: 20px;
    	}
    	.author{
    		margin-top: 10px;
    		margin-bottom: 10px;
		    font-family: 'Noto Serif',serif;
    	}
    	.author span{
    		margin-right: 10px;
    	}
    	.container{
    		padding-bottom: 30px;
    		padding-top: 10px;
    	}
    </style>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-8" style="margin: auto">
				<div class="title">
					<h1>{{ $post->title }}</h1>
				</div>
				<div class="author">
					<span style="font-weight: 600;">{{ $post->user_name }}</span><span>{{ $post->time }}</span>
				</div>
				<div class="short_content">
					{{ $post->short_content }}
				</div>
				<div class="content">
					{!! $post->content !!}
				</div>
			</div>
		</div>
	</div>
</body>
</html>