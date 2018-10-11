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
    {{ stylesheet_link("templates/game/default/style.css") }}

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
    {{ javascript_include("templates/game/default/script.js") }}

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="wrapper" id="wrapper">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div id="header-logo">
                {{ config.game.title }}
            </div>
            <div id="header-menu">
                <div><a href="/game/profile/show/{{ character.id }}">Postać</a></div>
                <div><a href="/game/chat/change/1">{{ t['com-inn'] }}</a></div>
                <div><a href="/game/chat/rpg/1">{{ t['com-rpgsessions'] }}</a></div>
                <div><a href="/game/news">{{ t['com-news'] }}</a></div>
            </div>
        </div>

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
        <div style="color: #9acfea; text-align: center; margin-bottom: 20px;">
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
