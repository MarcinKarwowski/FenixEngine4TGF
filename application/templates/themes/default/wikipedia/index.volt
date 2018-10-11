{% extends "threecolumns.volt" %}

{% block content %}
    <h1>Wikipedia</h1>
    <p style="text-align: center;">Wybierz artykuł z menu.</p>
{% endblock %}

{% block menu %}
    <h1>Menu</h1>
    <ul id="modulemenu">
    <?php
    $allarts = Main\Models\Wikipedia::find(['order'=>'orderid'])-> toArray();
    $treeClass = new App\Facets\TreeView($allarts);
    foreach ($treeClass -> retArr as $data) {
    ?>
        <li class="{% if data['parent_id'] == 0 %}wiki_root{% else %}wikichild parent_{{ data['parent_id'] }}{% endif %}">{% if data['deep'] == '' %}<i class="fa fa-angle-right"></i>{% endif %}{{ data['deep'] }} <a  artid="{{ data['id'] }}" parentid="{{ data['parent_id'] }}" href="/wikipedia/artykul/{{ data['id'] }}">{{ data['title'] }}</a></li>
    <?php } ?>
    </ul>
    <script type="text/javascript">
        $(function () {
            {% if artid is not null %}
                {% if parent_id != 0 %}
                    $('.parent_{{ parent_id }}').show();
                {% else %}
                    $('.parent_{{ artid }}').show();
                {% endif %}
            {% endif %}
        });
    </script>
{% endblock %}