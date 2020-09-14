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
			/*margin-top: 35px;*/
		}
		.element{
			padding-bottom: 10px;
			padding: 10px 10px 10px 10px;
			/*background-color: #f6f6f6;*/
			margin-top: 5px;
			border-bottom: 1px solid #d1d1d1;
		}
		.element .title{
			font-size: 20px;
			line-height: 1.3;
			color: blue;
			cursor: pointer;
			font-weight: 600;
		}
		.element .title:hover{
			text-decoration: underline;
		}
		.element .content{
			font-size: 14px;
			color: black;
		}
		.element .user_name{
			font-size: 12px;
			color: gray;
		}
		#count{
			padding-top: 20px;
		}
		#header{
			border-bottom: 1px solid #ebebeb;
			padding-bottom: 20px;
		}
		.py-4{
			padding-top: 0px !important;
		}
		#main{
			/*background-color: #f4f4f4;*/
			padding-top: 20px;
			min-height: 615px;
		}

	    .avatar-xs {
	        width: 34px;
	        height: 34px;
	    }
	    #avatar {
	        border-radius: 10rem;
	        width: 38px;
	        height: 38px;
	    }
	    li{
	    	list-style-type: none;
	    }
	    .show{
	    	display: block;
	    }
	    .hide{
	    	display: none;
	    }
	    #drop_menu{
	    	position: absolute;
	    }
	</style>
</head>
<body>
	<div id="app">
        <main class="py-4">
            <div class="container" id="main">
			    <div class="row justify-content-center">
			        <div class="col-md-12">
						<div class="row" id="header">
							<div class="col-md-2">
								<a href="/"><img src="https://techvccloud.mediacdn.vn/zoom/600_315/2018/10/15/elasticsearch-1539570535721747539902-0-50-832-1531-crop-15395705411511820530305.png" alt="logo" style="width: 100px;"></a>
							</div>
							
							<div class="col-md-8" class="input_search" style="padding-top: 10px;">
								<form method="POST" id="form_search">
									<button type="submit" id="search" class="btn btn-md green"><i class="fas fa-search"></i></button>
									<input type="text" id="search_name" name="search_name">
								</form>
							</div>
							<div class="col-md-2" style="padding-top: 10px;">
								@if (Auth::check())
									<ul class="navbar-nav ml-auto" style="float: right !important;">
										<li class="nav-item">
									        <span class="dropdown-toggle no-caret" data-toggle="dropdown" id="drop">
									          <img src="https://st3.depositphotos.com/1767687/16607/v/450/depositphotos_166074422-stock-illustration-default-avatar-profile-icon-grey.jpg" alt="" id="avatar" class="avatar-xs">
									        </span>
									        <div class="dropdown-menu dropdown-menu-right" id="drop_menu">
									        	<a href="/home" class="dropdown-item"><i class="fas fa-home"></i>&nbsp;&nbsp;Trang cá nhân</a>
									            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="fas fa-power-off"></i>&nbsp;&nbsp;Đăng xuất</a>
									            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
									                @csrf
									            </form>
									        </div>
									    </li>
									</ul>
								@else
									<a class="btn btn-success btn-md" href="/login"  style="float: right !important;">Đăng nhập</a>
								@endif
							</div>
						</div>
						<div class="col-md-9">
							<div id="count">
								
							</div>
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
                	var count = data.count;
                	var count_show = data.count_show;
                	var took = data.took;
                	var data = data.arr_banks;
                	$('#count').html(`<span>Khoảng `+count +` kết quả (`+took+` giây)</span>`);
                	if(data != null) {
                		var txt = '';
                		for (var i = 0; i < data.length; i++) {
                			var element = data[i];
                			txt += `<div class="element">
                				<div class="title">
                					<a href="/post/`+element['_source']['slug']+`" target="_blank">`+element['_source']['title']+`</a>
                				</div>
                				<div class="content">
                					`+element['_source']['short_content']+`
                				</div>
                				<div class="user_name">
                					<span>Tác giả: `+element['_source']['user_name']+` - Thời gian: `+element['_source']['created_at']+`</span>
                				</div>
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
	$('#drop').on('click', function(){
		if ($('#drop_menu').hasClass('show')){
			$('#drop_menu').removeClass('show').addClass('hide');
		} else {
			$('#drop_menu').removeClass('hide').addClass('show');
		}
	})

</script>