<!-- Main content -->
<section class="content" id="maincontent">
    {{ flash.output() }}
    {% block content %}

    {% endblock %}
</section>
<!-- /.content -->

<section class="content-sidebar">
    {% block menu %}

    {% endblock %}
</section>
