{% include "../../header.volt" %}
{% block content %}
    <h1>Welcome to Phalcon CMS - Overwrite by Default template!</h1>
    <h2>{{ 'published' | t }}</h2>
    {{ get_sidebar("sidebar_left") }}
{% endblock %}
{% include "../../footer.volt" %}