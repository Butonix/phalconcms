{% extends "../../index.volt" %}

{% block css_header %}
	<link rel="stylesheet" href="{{ _baseUri }}/plugins/dropzone/min/dropzone.min.css">
{% endblock %}

{% block content %}
	<div class="content">
		<div class="dropzone">
			{% for media in medias %}
			<div class="dz-preview dz-image-preview">
				<div class="dz-image"><img alt="{{ media.title }}" src="{{ media.src }}"></a></div>
				<div class="dz-details" style="padding:3em 0;">
					<div class="actions"><i class="fa fa-search-plus" data-src="{{ media.src }}"></i><i class="fa fa-trash" data-id="{{ media.media_id }}"></i></div>
				</div>
			</div>
			{% endfor %}
		</div>
		<div id="showImg" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<a class="close" data-dismiss="modal">&times;</a>
					</div>
					<div class="modal-body">
						<img src="" class="img-responsive">
					</div>
				</div>
			</div>
		</div>
	</div>
{% endblock %}

{% block js_footer %}
	<script src="{{ _baseUri }}/plugins/fakecrop/jquery.fakecrop.js"></script>
	<script src="{{ _baseUri }}/plugins/bootbox/bootbox.min.js"></script>
	
	<script>
	$(document).ready(function () {
		$('.dz-image img').fakecrop({wrapperWidth:120, wrapperHeight:120});
		
		$('.dz-details .actions i.fa-search-plus').on('click', function() {
			var src = $(this).data('src');
			$('#showImg img').attr('src', src);
			$('#showImg').modal('show');
		});
		
		$('.dz-details .actions i.fa-trash').on('click', function() {
			var self = $(this);
			var media_id = self.data('id');
			bootbox.confirm({ 
				size: 'small',
				message: "{{ __('Are you sure you want to delete it?') }}", 
				callback: function(result) {
					if(result) {
						$.ajax({
							url: "{{ _baseUri }}/admin/media/manager/delete",
							type: "POST",
							data: {media_id: media_id},
							success: function(data) {
								if(data.status) {
									self.parents('.dz-image-preview').remove();
								} else {
									bootbox.alert({ 
										size: 'small',
										message: "{{ __('Delete failed') }}"
									});
								}
							}
						});
					}
				}
			});
		});
	});
	</script>
{% endblock %}