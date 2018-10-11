<!DOCTYPE html>
<html class="csstransforms no-csstransforms3d csstransitions js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage no-websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients no-cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths"
      lang="pl" xmlns="http://www.w3.org/1999/html">

<head>
    <base href="/"/>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Fenix Engine install page">
    <meta name="author" content="">

    <title>Fenix Engine Install Page</title>

    <!-- Bootstrap Core CSS -->
    {{ stylesheet_link("scripts/bootstrap/css/bootstrap.min.css") }}
    {{ stylesheet_link("css/font-awesome.min.css") }}

    <!-- Custom CSS -->
    {{ stylesheet_link("css/style.css") }}

    <!-- Template js -->
    {{ javascript_include("scripts/jquery-2.1.1.min.js") }}
    {{ javascript_include("scripts/bootstrap/js/bootstrap.min.js") }}
    {{ javascript_include("scripts/jqBootstrapValidation.js") }}
    {{ javascript_include("scripts/modernizr.custom.js") }}
    {{ javascript_include("scripts/script.js") }}

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>

<!--====ALL CONTENT====-->
<div class="container metro">
    <div class="row">
        <div class="tab-content">

        </div>
    </div>
</div>

<!--====/ALL CONTENT====-->
<!--====FOOTER====-->
<footer>
    <div style="color: #9acfea; text-align: center; margin-bottom: 20px;">
        <p>Copyright © <script type="text/javascript">document.write(new Date().getFullYear())</script> <a href="https://hexengine.pl/">Hexen Engine Team</a> All rights reserved.</p>
        <p>Czas ładowania strony: {{ scriptTime }} | Zajęta pamieć: {{ scriptMemory }} MB</p>
    </div>
</footer>
<!--====FOOTER====-->
</body>
</html>
