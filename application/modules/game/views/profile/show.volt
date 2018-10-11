{% extends "profile/index.volt" %}

{% block content %}
    <div class="module_content profile_module">
        <div class="profile_bio">
            <div class="profile_avatar"><img src="{{ avatarPath }}" /></div>
            <div class="profile_info">
                <div class="profile_char_name">{{ name }} [{{ id }}]</div>
            {% if stats is iterable %}
                {% for stat in stats %}
                <div class="one_stat">
                    <div class="one_stat_label">{{ stat['label'] }}</div> <div class="one_stat_value">{{ stat['value'] }}</div>
                </div>
                {% endfor %}
            {% endif %}
            </div>
        </div>

    </div>
{% endblock %}