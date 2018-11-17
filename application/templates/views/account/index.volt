{% extends "_layouts/ingame.volt" %}

{% block menu %}
    <h1>{{ t['account-yre_acc'] }}</h1>
    <ul id="content_menu">
        <li class="content_menu_normal"><i class="fa fa-angle-right"></i> <a
                    href="/game/account/avatar">{{ t['avatar'] }}</a></li>
    </ul>
{% endblock %}

{% block content %}
    <div>{{ t['account-first_desc'] }}</div>
    <div class="module_content">
        {{ t['account-with_us'] }} {{ registerdate }}.<br />
        {% if config.game.params.charactersAmount > 0 %}
        {{ t['account-have_chars'] }} {{ charcount }}. {{ t['account-manage_lobby'] }}<br />
        {% endif %}
    </div>
{% endblock %}
