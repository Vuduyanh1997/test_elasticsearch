@extends('layouts.master')
@section('link')
@endsection
@section('content')
<div class="content-header">
<div class="container-fluid">
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1 class="m-0 text-dark">Thêm mới bài viết</h1>
    </div>
  </div>
</div>
</div>
<div class="container-fluid">
	<div class="col-md-10" style="margin: auto">
		<form method="POST" id="form_create_post">
			<div class="form-group">
				<label for="title">Tiêu đề</label>
				<input type="text" name="title" id="title" class="form-control" placeholder="Tiêu đề">
			</div>
			<div class="form-group">
				<label for="content">Mô tả ngắn</label>
				<textarea name="short_content" id="short_content" rows="3" class="form-control" placeholder="Mô tả ngắn"></textarea>
			</div>
			<div class="form-group">
				<label for="content">Nội dung</label>
				<textarea name="content" id="content" rows="5" class="form-control"></textarea>
			</div>
			<div class="form-group">
				<center>
					<button type="submit" class="btn btn-md btn-primary">Tạo</button>
				</center>
			</div>
		</form>
		
	</div>
</div>
@endsection
@section('footer')
<script>
	tinymce.init({
        selector: '#content',
        height: 350,
        // theme: 'modern',
        menubar: false,
        autosave_ask_before_unload: false,
        mode : 'specific_textareas',
        editor_selector : 'mceEditor' ,
        plugins: [
        "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
        "table contextmenu directionality emoticons template textcolor paste fullpage textcolor colorpicker textpattern codesample"
        ],
        toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | fontselect fontsizeselect | cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor | table | hr removeformat | subscript superscript | charmap | emoticons | print fullscreen | codesample",
        image_advtab: true,
        content_css: [
        '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
        '//www.tinymce.com/css/codepen.min.css'
        ],
        relative_urls: false,
        remove_script_host : false,
        file_browser_callback : function(field_name, url, type, win) {
            var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
            var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

            var cmsURL = route_prefix + '?field_name=' + field_name;
            if (type == 'image') {
                cmsURL = cmsURL + "&type=Images";
            } else {
                cmsURL = cmsURL + "&type=Files";
            }

            tinyMCE.activeEditor.windowManager.open({
                file : cmsURL,
                title : 'Image manager',
                width : x * 0.9,
                height : y * 0.9,
                resizable : "yes",
                close_previous : "no"
            });
        }
    });

	$(document).ready(function(){
		$("#form_create_post").validate({
			onfocusout: false,
			onkeyup: false,
			onclick: false,
			errorElement: 'span',
	        errorClass: 'error',
			rules: {
				"title": {
					required: true,
					maxlength: 255,
					minlength: 5
				},
				"short_content": {
					required: true,
					minlength: 10
				},
				"content": {
			          required: true,
			          noSpace: true,
			    }
			},
			messages: {
				"title": {
					required: "Vui lòng thêm tiêu đề cho bài viết!",
					maxlength: "Vui lòng nhập tối đa 255 ký tự!",
					minlength: "Vui lòng nhập tối thiểu 5 ký tự!"
				},
				"short_content": {
					required: "Vui lòng thêm nội dung cho bài viết!",
					minlength: "Vui lòng nhập tối thiểu 10 ký tự!"
				},
				"content": {
			        required: "Vui lòng thêm nội dung cho bài viết!"
			    }
			}
		});
		jQuery.validator.addMethod("noSpace", function(value, element) { 
		  return value == '' || value.trim().length != 0;  
		}, "Vui lòng không nhập khoảng trắng");

		$(document).on('submit', '#form_create_post', function(e){
			e.preventDefault();

			$('#title').val($('#title').val().trim());
			$('#content').val($('#content').val().trim());

			$('#form_create_post').valid();

			var title = $('#title').val();
			var short_content = $('#short_content').val();
			var content = tinymce.get('content').getContent();
  			var main_content = $('#content_ifr').contents().find('body').text();


			$.ajax({
                url: '/posts/store',
                type: 'POST',
                data: {
                    title: title,
                    short_content: short_content,
                    content: content,
                    main_content: main_content
                },
                success: function(res) {
                	if(!res.error){
                		toastr.success(res.message);
                		setTimeout(function(){
                			document.location.href= '/posts';
                		}, 300);
                	} else {
                		toastr.error(res.message);
                	}
			    },
            });
		})
	})
</script>
@endsection