{% extends "twocolumn.volt" %}

{% block menu %}
    <ul id="content_menu">
        <li class="content_menu_nagl profiles_nagl"><i class="fa fa-street-view"></i> {{ t['profile-char_nagl'] }}</li>
        <li class="content_menu_normal"><i class="fa fa-angle-right"></i> <a
                    href="/game/profile/show/{{ charid }}">{{ t['profile-char_bio'] }}</a></li>
    </ul>
{% endblock %}

{% block content %}
    <div class="module_content profile_module">

    </div>
{% endblock %}