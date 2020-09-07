<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<title>Tìm kiếm</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous"/>
	<style type="text/css" media="screen">
		span.error{
			color: red;
		}
	</style>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<center><h1>Tìm kiếm</h1></center>
			</div>
		</div>
		<form action="" method="POST" id="form_search">
			<div class="row">
				<div class="col-md-12 form-group">
					<label for="search_name">Nhập tên</label>
					<input type="text" id="search_name" name="search_name" class="form-control">
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<center>
						<button type="submit" id="search" class="btn btn-md green">Tìm kiếm</button>
					</center>
				</div>
			</div>
		</form>
	</div>
</body>
</html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js" integrity="sha512-UdIMMlVx0HEynClOIFSyOrPggomfhBKJE28LKl8yR3ghkgugPnG6iLfRfHwushZl1MOPSY6TsuBDGPK2X4zYKg==" crossorigin="anonymous"></script>
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
                }
            }).done(function(ketqua) {
                
            });
		})
	})
</script>