<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

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
    	span.error{
            color: red;
        }
		#search_name{
			width: 90%;
			float: left;
			line-height: 30px;
		}
		#search{
			background: #e4e3e3;
			width: 10%;
			float: right;
		}
		#view_result{
			margin-top: 30px;
		}
		.element{
			
		}
	</style>
</head>
<body>
	<div id="app">
        <main class="py-4">
            <div class="container">
			    <div class="row justify-content-center">
			        <div class="col-md-8">
			        	<div class="col-md-12">
							<center><h1>Tìm kiếm</h1></center>
						</div>
						<form method="POST" id="form_search">
							<div class="row">
								<div class="col-md-12" class="input_search">
									<button type="submit" id="search" class="btn btn-md green"><i class="fas fa-search"></i></button>
									<input type="text" id="search_name" name="search_name">
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<center>
										
									</center>
								</div>
							</div>
						</form>
						<div class="col-md-12">
							<div id="view_result">
								
							</div>
						</div>
			        </div>
			    </div>
			</div>
        </main>
    </div>
</body>
</html>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>

<script>
	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});
	$("#form_search").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		errorElement: 'span',
        errorClass: 'error',
		rules: {
			"search_name": {
				required: true
			}
		},
		messages: {
			"search_name": {
				required: "Vui lòng thêm thông tin tìm kiếm"
			},
		}
	});
	$(document).ready(function(){
		$(document).on('submit', '#form_search', function(e){
			e.preventDefault();
			var search_name = $('#form_search #search_name').val().trim();
			$('#form_search #search_name').val(search_name);
			$('#form_search').valid();
			$.ajax({
                url: '/search',
                type: 'POST',
                data: {
                    search_name: search_name,
                },
                success: function(data) {
                	var data = data.arr_banks;
                	if(data.length > 0) {
                		var txt = '';
                		for (var i = 0; i < data.length; i++) {
                			var element = data[i];
                			console.log(element['_source']['account_number']);
                			txt += `<div class="element">
                				`+element['_source']['account_number']+`
                			</div>`;
                		}
                		$('#view_result').html(txt);
                	} else {
                		$('#view_result').html('');
                	}
			    },
            });
		})
	})
</script>