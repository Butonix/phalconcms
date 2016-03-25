{% extends "../../index.volt" %}

{% block content %}
    <div class="content">
		<form action="{{ _baseUri }}{{ router.getRewriteUri() }}" method="post" id="adminForm" novalidate="novalidate">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="password" class="control-label">
									Current Password <span class="symbol required"></span>
								</label>
								<input type="password" id="password" name="password" value="" class="form-control">
							</div>
						</div>
						<div class="col-md-6">
							<div>
								<span class="symbol required"></span>Required Fields
								<hr>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
    </div>
{% endblock %}