{% extends "threecolumns.volt" %}

{% block menu %}
    {% if charid is not null %}
    <h1>{{ t['chat-sessions_panel'] }}</h1>
    <ul>
        <li class="content_menu_normal"><i class="fa fa-angle-right"></i> <a href="/game/profile/show/{{ charid }}">{{ t['profile-char_bio'] }}</a></li>
    </ul>
    {% endif %}
    <h1>{{ t['profile-history_nagl'] }}</h1>
    <ul id="content_menu">
        <?php
         foreach (Main\Models\CharactersHistory::find('character_id = '.$charid) as $onecharelem) {
         ?>
        <li class="content_menu_normal"><i class="fa fa-angle-right"></i> <a
                    href="/game/profile/one/{{ onecharelem.id }}">{{ onecharelem.title }}</a></li>
        <?php } ?>
        {% if charid == auth['activeChar'] %}
            <li class="content_menu_normal"><i class="fa fa-plus-circle"></i> <a
                        href="/game/profile/edit/0">{{ t['profile-add_new'] }}</a></li>
        {% endif %}
    </ul>
{% endblock %}

{% block content %}
    <div class="module_content profile_module">

    </div>
{% endblock %}