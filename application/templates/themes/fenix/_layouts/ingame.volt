<div class="row">
    <div class="col-lg-2 sidebar-content">
        {% block menu %} {% endblock %}

        {% block sidebar %}
            <h1>Online <span id="characters_online_count"></span></h1>
            <div id="characters_online"></div>
        {% endblock %}
    </div>
    <div class="col-lg-10 main-container">
        <div class="main-menu">
            <a href="/game/profile/show/{{ character.id }}">Postać</a>
            <a href="/game/chat/change/1">{{ t['com-inn'] }}</a>
            <a href="/game/chat/rpg/1">{{ t['com-rpgsessions'] }}</a>
            <a href="/game/news">{{ t['com-news'] }}</a>
        </div>
        <!-- Main content -->
        <div class="content main-content">
            {{ flash.output() }}
            {% block content %}

            {% endblock %}
        </div>
        <!-- /.content -->
    </div>
</div>
