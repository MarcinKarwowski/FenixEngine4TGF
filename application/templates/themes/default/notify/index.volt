{% extends "threecolumns.volt" %}

{% block menu %}
    <h1>{{ t['notify-notify_nagl'] }}</h1>
    <ul id="content_menu">
        <li class="content_menu_normal"><i class="fa fa-angle-right"></i> <a
                    href="/game/notifications/index/1">{{ t['all'] }}</a></li>
        <li class="content_menu_normal"><i class="fa fa-angle-right"></i> <a
                    href="/game/notifications/index/CHAR/1">{{ t['character'] }}</a></li>
        <li class="content_menu_normal"><i class="fa fa-angle-right"></i> <a
                    href="/game/notifications/index/NEWS/1">{{ t['news'] }}</a></li>
    </ul>
{% endblock %}

{% block content %}
    <div class="module_content notifications_module">
        {% if page.items|length > 0 %}
            <div>
                {% for msg in page.items %}
                    <div class="notify"><?php if (isset($nicons[$msg -> Notify -> type])) echo '<i class="fa '.$nicons[$msg -> Notify -> type].'"></i>'; else echo '<i class="fa fa-bolt"></i>'; ?>
                        <div class="notify_title">{{ msg.Notify.title }}</div>
                        <div class="notify_data">{{ msg.Notify.date }}</div>
                        <div class="notify_content">{% if msg.expiry is null %}{{ msg.Notify.text }}{% else %}{{ t['notify-expiry_one'] }}{% endif %} </div>
                    </div>
                {% endfor %}
            </div>

            <nav style="text-align: center;">
                <ul class="pagination">
                    <li>
                        <a href="/game/notifications/index/<?= $page->before; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php
                for ($i = 1; $i <= $page->total_pages; $i++)
                    {
                    echo '<li><a href="/game/notifications/index/'.$i.'">'.$i.'</a></li>';
                    }
                    ?>
                    <li>
                        <a href="/game/notifications/index/<?= $page->next; ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        {% else %}
            <div style="text-align: center;width: 100%;">{{ t['messages-inbox_no_msg'] }}</div>
        {% endif %}
    </div>
{% endblock %}