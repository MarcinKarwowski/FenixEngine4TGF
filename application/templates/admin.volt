<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ pageHeader }} :: Panel administratora</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap Core CSS -->
    {{ stylesheet_link("scripts/bootstrap/css/bootstrap.min.css") }}
    {{ stylesheet_link("css/font-awesome.min.css") }}

    <!-- Ionicons -->
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    {{ stylesheet_link("templates/admin/adminLTE.css") }}
    {{ stylesheet_link("templates/admin/skins/skin-blue.min.css") }}
    {{ stylesheet_link("templates/admin/plugins/datatables/dataTables.bootstrap.css") }}

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

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
            {% for index, config in config.game.params %}
            , {{ index }}: '{{ config }}'
            {% endfor %}
        };
    </script>
</head>
<body class="skin-blue sidebar-mini layout-boxed">
<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="/play" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><img src="/assets/templates/admin/images/fe.png" style="float: left; width: 50px;" /></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg">{{ config.game.title }}</span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    {% if showUpdate %}
                    <li>
                        <a href="/admin/check-update">Nowa wersja silnika {{ new_version }}</a>
                    </li>
                    {% endif %}
                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                            <img src="/assets/templates/admin/images/defaultAvatar.png" class="user-image" alt="User Image"/>
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs">{{  auth['name'] }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                <img src="/assets/templates/admin/images/defaultAvatar.png" class="img-circle" alt="User Image" />
                                <p>
                                    {{  auth['name'] }}
                                    <small>{{ t['dashboard-register_at'] }}{{ user['registerdate'] }}</small>
                                </p>
                            </li>
                            <!-- Menu Body -->
                            <li class="user-body">
                                <div class="col-xs-12 text-center"></div>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-right">
                                    <a href="/session/logout" class="btn btn-default btn-flat">{{ t['log-out'] }}</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <!-- Control Sidebar Toggle Button -->
                    <li>
                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar" style="height: auto; min-height: 1000px;">

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu">
                {% for index, value in adminMenu %}
                    <li class="header">{{ t[index|e] }}</li>
                    {% if value is iterable %}
                        {% set statlabel = '' %}
                        {% for label, menu in value %}
                            {% if auth['permissions'][label] is defined or auth['group'] == 'Admin' %}
                                {% if menu['group'] is defined %}
                                    {% if statlabel == '' %}
                                        {% set statlabel = menu['group']|e %}
                                        <li class="treeview">
                                            <a href="#"><i class="fa fa-{{ menu['mico'] }}"></i> <span>{{ t[statlabel] }}</span> <i class="fa fa-angle-left pull-right"></i></a>
                                            <ul class="treeview-menu" style="display: none;">
                                                {% elseif statlabel != menu['group'] %}
                                                {% set statlabel = menu['group']|e %}
                                            </ul>
                                        </li>
                                        <li class="treeview">
                                            <a href="#"><i class="fa fa-{{ menu['ico'] }}"></i> <span>{{ t[statlabel] }}</span> <i class="fa fa-angle-left pull-right"></i></a>
                                            <ul class="treeview-menu" style="display: none;">
                                    {% endif %}
                                    <li {% if menu['link']|e == router.getRewriteUri() %}class="active"{% endif %}><a href="{{ menu['link']|e }}"><i class='fa fa-{{ menu['ico'] }}'></i> <span>{{ t[label|e] }}</span></a></li>
                                    {% if loop.last %}
                                        </ul>
                                    </li>
                                    {% endif %}
                                {% else %}
                                    {% if statlabel != '' %}
                                        </ul>
                                    </li>
                                {% endif %}
                                <li {% if menu['link']|e == router.getRewriteUri() %}class="active"{% endif %}><a href="{{ menu['link']|e }}"><i class='fa fa-{{ menu['ico'] }}'></i> <span>{{ t[label|e] }}</span></a></li>
                                {% set statlabel = '' %}
                                {% endif %}
                            {% endif %}
                        {% endfor %}
                    {% endif %}
                {% endfor %}
            </ul><!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                {{ pageHeader }}
                <small>{{ pageDesc }}</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            {{ flash.output() }}
            {% block pageContent %}

            {% endblock %}
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">

        </div>
        <!-- Default to the left -->
        {{ partial("../../../templates/partials/footer") }}

        {{ html_entity_decode(config.game.custom) }}
    </footer>
</div><!-- ./wrapper -->


<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ t['close'] }}</button>
                <button type="button" class="btn btn-primary" id="modal-save-button">{{ t['save'] }}</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- REQUIRED JS SCRIPTS -->

<!-- Template js -->
{{ javascript_include("scripts/jquery-2.1.1.min.js") }}
{{ javascript_include("scripts/jquery.ui/jquery-ui.min.js") }}
{{ javascript_include("scripts/bootstrap/js/bootstrap.min.js") }}
<!-- InputMask -->
{{ javascript_include("templates/admin/plugins/input-mask/jquery.inputmask.js") }}
{{ javascript_include("templates/admin/plugins/input-mask/jquery.inputmask.date.extensions.js") }}
{{ javascript_include("templates/admin/plugins/input-mask/jquery.inputmask.extensions.js") }}

{{ javascript_include("scripts/datatables/media/js/jquery.dataTables.min.js") }}
{{ javascript_include("templates/admin/plugins/datatables/dataTables.bootstrap.min.js") }}
{{ javascript_include("scripts/jquery-migrate-1.2.1.js") }}
{{ javascript_include("scripts/jquery.ajaxq.js") }}
{{ javascript_include("scripts/jsrender.min.js") }}
{{ javascript_include("scripts/tinymce/tinymce.min.js") }}
{% if config.game.params.mapOn == 1 %}
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
    {{ javascript_include("scripts/plugins/map.js") }}
{% endif %}

<!-- AdminLTE App -->
{{ javascript_include("templates/admin/js/app.min.js") }}
{{ javascript_include("templates/admin/js/javascript.js") }}

{% block addJS %}

{% endblock %}
</body>
</html>
