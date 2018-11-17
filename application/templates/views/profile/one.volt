{% extends "profile/index.volt" %}

{% block content %}
    <div class="module_content profile_module">
        <h1>{{ charprofile.title }} {% if charid == auth['activeChar'] %}<a href="/game/profile/edit/{{ charprofile.id }}"><i class="fa fa-pencil-square-o"></i></a> <a href="/game/profile/del/{{ charprofile.id }}"><i class="fa fa-times-circle"></i></a>{% endif %}</h1>

        <div>
            {{ charprofile.text }}
        </div>
    </div>
{% endblock %}