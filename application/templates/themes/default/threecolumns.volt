<div class="content-menu">
    {% block menu %}

    {% endblock %}
</div>

<!-- Main content -->
<div class="content" id="maincontent">
    {{ flash.output() }}
    {% block content %}

    {% endblock %}
</div>
<!-- /.content -->

<div class="content-sidebar">
    {% block sidebar %}
        <h1>{{ character.shortname }}</h1>
        <img src="{{ character.avatar }}" style="width: 140px;" />
        <div class="character-menu">
            <a href="/game/account/index" data-toggle="tooltip" data-placement="bottom" title="Opcje konta"><i class="fa fa-cog"></i></a>
            <a href="/game/messages/index" data-toggle="tooltip" data-placement="bottom" title="Poczta">
                <i class="fa fa-envelope"></i>
                <div style="display: none;" class="interface_new_msg">0</div>
            </a>
            <div id="interface_notifications" href="/game/notifications/index/1" data-toggle="tooltip" data-placement="bottom" title="Powiadomienia">
                <i class="fa fa-exclamation-triangle"></i>
                <div style="display: none;" class="interface_new_logs">0</div>
                <div id="interface_show_new_logs">
                    <div id="new_notifications_arrow"></div>
                    <div class=" scrollbar-dynamic" id="new_notifications_content"></div>
                    <div id="new_notifications_all"><a href="/game/notifications/index/1">Zobacz wszystkie</a>
                    </div>
                </div>
            </div>
            <a href="/charcreator/lobby" data-toggle="tooltip" data-placement="bottom" title="Lobby"><i class="fa fa-hourglass-start"></i></a>
            <a href="/wikipedia" data-toggle="tooltip" data-placement="bottom" title="Wikipedia"><i class="fa fa-university"></i></a>
            {% if config.game.params.mapOn == 1 %}
                <a href="#" id="openmap" data-toggle="tooltip" data-placement="bottom" title="Mapa świata"><i class="fa fa-map" aria-hidden="true"></i></a>
            {% endif %}
            <a href="/session/logout" data-toggle="tooltip" data-placement="bottom" title="Wylogowanie"><i class="fa fa-power-off"></i></a>

        </div>
        {% if eraDate != '' %}
            <div class="era_date">{{ t['game-date'] }} {{ eraDate }}<br /><div id="clock"></div></div>
        {% endif %}
        <h1>Online <span id="characters_online_count"></span></h1>
        <div id="characters_online"> </div>
    {% endblock %}
</div>