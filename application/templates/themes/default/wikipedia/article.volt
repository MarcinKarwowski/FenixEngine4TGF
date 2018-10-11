{% extends "wikipedia/index.volt" %}

{% block content %}
    <h1>{{ title }}</h1>
    <div id="wiki_text">{{ text }}</div>
{% endblock %}