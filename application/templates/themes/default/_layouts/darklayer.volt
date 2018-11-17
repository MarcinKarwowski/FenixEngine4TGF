<div class="darklayer">
    <div id="indarkLayer">
        {% block content %}

        {% endblock %}
    </div>
    <script type="text/javascript">
        $(function()
        {
            $('body').css('height', 'auto');
            //$('.darklayer').css('height', $('body').css('height'));
        });
    </script>
</div>
