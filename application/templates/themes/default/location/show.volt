{% extends "threecolumns.volt" %}

{% block menu %}

{% endblock %}

{% block content %}
    <div class="module_content news_module">
        <div>
            <h1>{{ location.name }}</h1>

            <div>{{ location.text }}</div>

            <div>
                <?php
                    foreach (Game\Models\Locations::find(["parent_id = ?0", "bind" => [$location -> id]]) as $data) {
                ?>
                    <div class="news-title openwindow" url="/game/location/window/{{ data.id }}">{{ data.name }}</div>
                <?php } ?>
            </div>
        </div>
    </div>
{% endblock %}