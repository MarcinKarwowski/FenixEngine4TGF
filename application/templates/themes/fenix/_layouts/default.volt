<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>{% if pageHeader is defined %}{{ pageHeader }} :: {% endif %}{{ config.game.title }}</title>

    <script type="text/javascript">
        var translate = {
            'notify-expiry_one': '{{ t['notify-expiry_one'] }}',
            'action_expiry': '{{ t['game-location_end_action'] }}'
        };
        var fenixengine = {
            controller: '{{ controller }}'
            {% for param in params %}
            , param{{ loop.index }}: '{{ param }}'
            {% endfor %}
        };
    </script>

    {{ stylesheet_link("scripts/bootstrap/css/bootstrap.min.css") }}
    {{ stylesheet_link("css/font-awesome.min.css") }}

    {{ stylesheet_link("scripts/jquery.alerts/jquery.alerts.css") }}
    {{ stylesheet_link("scripts/jquery.ui/jquery.ui.min.css") }}
    {{ stylesheet_link("scripts/jquery.ui/jquery-ui.theme.min.css") }}
    {{ stylesheet_link("scripts/jquery.scrollbar/jquery.scrollbar.css") }}
    {{ stylesheet_link("scripts/editable/css/bootstrap-editable.css") }}
    {{ stylesheet_link("scripts/sceditor/themes/modern.min.css") }}
    {{ stylesheet_link("templates/game/fenix/style.css") }}

    {{ javascript_include("scripts/jquery-2.1.1.min.js") }}
    {{ javascript_include("scripts/jquery.ui/jquery-ui.min.js") }}
    {{ javascript_include("scripts/jquery.transit.min.js") }}
    {{ javascript_include("scripts/jquery.timer.js") }}
    {{ javascript_include("scripts/bootstrap/js/bootstrap.min.js") }}
    {{ javascript_include("scripts/jquery.scrollbar/jquery.scrollbar.js") }}
    {{ javascript_include("scripts/datatables/media/js/jquery.dataTables.min.js") }}
    {{ javascript_include("templates/admin/plugins/datatables/dataTables.bootstrap.min.js") }}
    {{ javascript_include("scripts/jquery-migrate-1.2.1.js") }}
    {{ javascript_include("scripts/jquery.alerts/jquery.alerts.js") }}
    {{ javascript_include("scripts/jquery.ajaxq.js") }}
    {{ javascript_include("scripts/jsrender.min.js") }}
    {{ javascript_include("scripts/sceditor/jquery.sceditor.bbcode.min.js") }}
    {{ javascript_include("scripts/editable/js/bootstrap-editable.min.js") }}
    {% if config.game.params.mapOn == 1 %}
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
        {{ javascript_include("scripts/plugins/map.js") }}
    {% endif %}
    {{ javascript_include("templates/game/fenix/script.js") }}

    <style>
        {% if config.game.template_text_color is defined and config.game.template_text_color|length == 6 %}
        .chat-message {
            color: # {{ config.game.template_text_color }} !important;
        }
        {% endif %}
        {% if config.game.template_bg is defined and config.game.template_bg|length > 6 %}
        .header-character {
            background-image: url({{ config.game.template_bg }});
            background-size: cover;
            background-position: center center;
        }
        {% endif %}
    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="wrapper" id="wrapper">
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="/{{ config.game.params.defaultPage }}">
                    {{ config.game.title }}
                </a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="/game/account/index">
                        <img src="/assets/templates/game/fenix/account_options_icon.png" />
                    </a>
                </li>
                <li>
                    <a href="/session/logout">
                        <img src="/assets/templates/game/fenix/log_out_icon.png" />
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- Content Wrapper. Contains page content -->
    <div class="container">
        {% if auth['activeChar'] is defined %}
            <div class="row">
                <div class="col-lg-2 header-avatar">
                    <img src="{{ character.avatar }}" style="width: 140px;" />
                    <h4>{{ character.shortname }}</h4>
                    {% if eraDate != '' %}
                        <div class="era_date">{{ t['game-date'] }} {{ eraDate }}<br /><div id="clock"></div></div>
                    {% endif %}
                </div>
                <div class="col-lg-10 header-character">
                    <div class="character-menu">
                        <a href="/game/messages/index" data-toggle="tooltip" data-placement="bottom" title="Poczta">
                            <img src="/assets/templates/game/fenix/profile_menu_5.png" />
                            <div style="display: none;" class="interface_new_msg">0</div>
                        </a>
                        <div id="interface_notifications" href="/game/notifications/index/1" data-toggle="tooltip" data-placement="bottom" title="Powiadomienia">
                            <img src="/assets/templates/game/fenix/profile_menu_4.png" />
                            <div style="display: none;" class="interface_new_logs">0</div>
                            <div id="interface_show_new_logs">
                                <div id="new_notifications_arrow"></div>
                                <div class=" scrollbar-dynamic" id="new_notifications_content"></div>
                                <div id="new_notifications_all"><a href="/game/notifications/index/1">Zobacz wszystkie</a>
                                </div>
                            </div>
                        </div>
                        <a href="/charcreator/lobby" data-toggle="tooltip" data-placement="bottom" title="Lobby">
                            <img src="/assets/templates/game/fenix/profile_menu_1.png" />
                        </a>
                        <a href="/wikipedia" data-toggle="tooltip" data-placement="bottom" title="Wikipedia">
                            <img src="/assets/templates/game/fenix/profile_menu_3.png" />
                        </a>
                        {% if config.game.params.mapOn == 1 %}
                            <a href="#" id="openmap" data-toggle="tooltip" data-placement="bottom" title="Mapa świata">
                                <img src="/assets/templates/game/fenix/profile_menu_2.png" />
                            </a>
                        {% endif %}
                    </div>
                </div>
            </div>
        {% endif %}
        <!-- Main content -->
        {{ content() }}
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- custom window -->
    <div id="custom-window" style="display: none;">
        <div id="custom-window-content"></div>
        <div id="custom-window-close">{{ t['close'] }}</div>
    </div>

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- Default to the left -->
        <div style="text-align: center; margin-bottom: 20px;">
            <p>Copyright © <script type="text/javascript">document.write(new Date().getFullYear())</script> <a href="https://hexengine.pl/">Hexen Engine Team</a> All rights reserved.</p>
            <p>Engine ver.: {{ config.game.engineVer }}{% if auth['group'] == 'Admin' or auth['permissions']['adminlink'] is defined %} - <a href="/admin">Panel Administratora</a>{% endif %}</p>
            <p>Czas ładowania strony: {{ scriptTime }}</p>
        </div>
        {{ html_entity_decode(config.game.custom) }}
    </footer>
</div>
<!-- ./wrapper -->
</body>
</html>
