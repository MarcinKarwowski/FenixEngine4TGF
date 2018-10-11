<div class="darklayer">
    <div id="indarkLayer">
        {% block content %}

        {% endblock %}
    </div>
    <script type="text/javascript">
        $(function()
        {
            $('.darklayer').css('height', $('body').css('height'));
        });
    </script>
</div>
