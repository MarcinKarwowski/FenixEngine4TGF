<!DOCTYPE html>
<html lang="pl">
<head>
    <base href="/"/>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ config.game.description }}">
    <meta name="keywords" content="{{ config.game.keywords }}"/>
    <meta name="author" content="">

    <title>{{ config.game.title }}</title>

    <!-- Mobile Specific Metas
  ================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS
  ================================================== -->
    <!-- Bootstrap Core CSS -->
    {{ stylesheet_link("scripts/bootstrap/css/bootstrap.min.css") }}
    {{ stylesheet_link("css/font-awesome.min.css") }}
    {{ stylesheet_link("css/counter/animate.css") }}
    <!-- Bxslider CSS -->
    {{ stylesheet_link("css/counter/bxslider.css") }}
    <!-- Template styles-->
    {{ stylesheet_link("css/counter/style.css") }}
    <!-- Responsive styles-->
    {{ stylesheet_link("css/counter/responsive.css") }}

    <!-- Template js -->
    {{ javascript_include("scripts/jquery-2.1.1.min.js") }}
    {{ javascript_include("scripts/bootstrap/js/bootstrap.min.js") }}
    {{ javascript_include("scripts/jquery.appear.js") }}
    {{ javascript_include("scripts/modernizr.custom.js") }}
    {{ javascript_include("scripts/wow.min.js") }}
    {{ javascript_include("scripts/jquery.easing.1.3.js") }}
    {{ javascript_include("scripts/countdown.js") }}
    {{ javascript_include("scripts/jquery.bxslider.min.js") }}
    {{ javascript_include("scripts/jquery.backstretch.js") }}

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->


</head>
<body>
<div class="wrapper">
    <header id="header" class="parallax1">
        <div class="container">
            <div class="row ">
                <div class="wrapper-inner text-center">
                    <div class="heading-content">
                        <h2 class="title wow bounceInLeft">{{ config.game.title }}</h2>

                        <p class="wow bounceInRight" data-wow-delay=".3s">{{ config.game.description }}</p>
                    </div>
                    <!-- start timer, reference to js/countdown.js -->
                    <div id="timer" class=" wow flipInY" style="margin-bottom: 60px;"></div>
                    <!-- end timer -->
                    <ul class="list-inline socail-link">
                        <li><a href="https://www.facebook.com/fenixengine/" target="_blank"><i
                                        class="fa fa-facebook wow fadeInRight" data-wow-delay=".2s"></i></a></li>
                    </ul>
                    {% if auth === false %}

                        <div class="contact-inner" style="margin-top: 50px;">

                            {{ form('class': 'form-horizontal contact-form', 'id': 'login-form', 'action': 'session/login') }}
                            <!-- Prepended text-->
                            <div class="form-group wow bounceInLeft">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-envelope"></span> {{ t['form-email'] }}</span>
                                        {{ forms.get('login').render('email', ['class': 'form-control', 'style': 'margin: 0;', 'placeholder': t['form-write_email']]) }}
                                    </div>

                                </div>
                            </div>

                            <!-- Prepended text-->
                            <div class="form-group wow bounceInLeft">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="fa fa-minus-circle"></span> {{ t['form-password'] }}</span>
                                        {{ forms.get('login').render('password', ['class': 'form-control', 'style': 'margin: 0;', 'placeholder': t['form-write_password']]) }}
                                    </div>

                                </div>
                            </div>

                            <!-- Button -->
                            <div class="form-group wow bounceInRight">
                                <label class="col-md-4 control-label" for="singlebutton"></label>

                                <div class="col-md-4">
                                    {{ forms.get('login').render(t['form-login'], ['class': 'btn-container col-md-12 text-center submit']) }}
                                </div>
                            </div>
                            {{ forms.get('login').render('csrf', ['value': csrfToken]) }}

                            </form>
                        </div>
                    {% endif %}
                    <div class="copyright text-center white">
                        <div style="color: #9acfea; text-align: center; margin-bottom: 20px;">
                            <p>Copyright © 2015 <a href="https://hexengine.pl/">Hexen Engine Team</a> All rights reserved.</p>
                            <p>Engine ver.: {{ config.game.engineVer }}{% if auth['group'] == 'Admin' %} - <a href="/admin">Panel Administratora</a>{% endif %}</p>
                            <p>Czas ładowania strony: {{ scriptTime }}</p>
                        </div>

                    </div>
                </div>
                <!-- wrapper-inner end -->
            </div>
            <!-- row end -->
        </div>
        <!-- container-fluid end -->
    </header>
</div>

<script>
    jQuery(function ($) {
        "use strict";

        var launchDay = new Date('{{ date('Y-m-d', config.game.startTime) }}');
        $('#timer').countdown({
            until: launchDay
        });
        $('.bxslider').bxSlider({
            auto: true,
            pager: false,
            mode: 'fade',
            speed: 1500,
            pause: 5000
        });
        new WOW().init();
        $.backstretch([
            "assets/images/tapeta1.jpg"
        ], {
            fade: 750,
            duration: 4000
        });
    });
    (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-3428493-28', 'auto');
    ga('send', 'pageview');

</script>

</body>
</html>
