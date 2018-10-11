{% extends "twocolumn.volt" %}

{% block menu %}
    <ul id="content_menu">
        <li class="content_menu_nagl profiles_nagl"><i class="fa fa-folder-open"></i> {{ t['messages-nagl_mess'] }}</li>
        <li class="content_menu_normal"><i class="fa fa-angle-right"></i> <a
                    href="/game/messages/index">{{ t['messages-menu_new'] }}</a></li>
        <li class="content_menu_normal"><i class="fa fa-angle-right"></i> <a
                    href="/game/messages/sended">{{ t['messages-menu_send'] }}</a></li>
        <li class="content_menu_normal"><i class="fa fa-angle-right"></i> <a
                    href="/game/messages/saved">{{ t['messages-menu_saved'] }}</a></li>
        <li class="content_menu_nagl profiles_nagl"><i class="fa fa-folder-open"></i> {{ t['messages-nagl_opt'] }}</li>
        <li class="content_menu_normal"><i class="fa fa-angle-right"></i> <a
                    href="/game/messages/write">{{ t['messages-menu_write'] }}</a></li>
    </ul>
{% endblock %}

{% block content %}
    <div class="module_content">
        <div class="progress">
            <?php
                $proc = ceil(($page -> total_items / $this -> config -> modules -> messages -> inboxlimit) * 100);
            ?>
            <div style="width: {{ proc }}%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="{{ proc }}" role="progressbar" class="progress-bar progress-bar-primary progress-bar-striped">
                <span>{{ t['messages-inbox_limit'] }} {{ proc }}% ({{ page.total_items }} / {{ config.modules.messages.inboxlimit }})</span>
            </div>
        </div>
    {% if page.items|length > 0 %}
        <div>
            {% for msg in page.items %}
                <div style="float: left; width: 100%; margin: 5px 0px; border-bottom: 1px dotted rgb(51, 102, 0); padding-bottom: 5px; position: relative;">
                    <div style="float: left; text-align: center; overflow: hidden; width: 50px; height: 50px; {% if msg.readed == 0 %} border-left: 1px solid #d2691e; {% endif %}">
                        <img src="<?php
                        if ($msg -> Sender -> avatar) echo $msg -> Sender -> getAvatar();
                        else echo '/assets/images/defaultav.jpg';
                        ?>" style="width: 40px;" />
                    </div>
                    <div style="font-family: Pixel; font-size: 11px; float: left; padding: 5px 0px; width: 400px; overflow: hidden; text-align: left;">
                        {{ msg.topic }}
                    </div>
                    <div style="float: left;">
                        <?php
                        if ($msg -> Sender -> name) echo $t['author'].': <a href="/game/profile/show/'.$msg -> Sender -> id.'">'.$msg -> Sender -> name.'</a>';
                        else echo $t['author'].': '.$t['someone'];
                        ?> | <?php echo date('d-m-Y H:i:s', $msg -> date); ?>
                    </div>
                    <div style="position: absolute; right: 0px; top: 15px;">
                        <a href="/game/messages/read/{{ msg.mid }}" class="btn btn-default">{{ t['read'] }}</a>
                    </div>
                </div>
            {% endfor %}
        </div>

        <nav style="text-align: center;">
            <ul class="pagination">
                <li>
                    <a href="/game/messages/index/<?= $page->before; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php
                for ($i = 1; $i <= $page->total_pages; $i++)
                {
                    echo '<li><a href="/game/messages/index/'.$i.'">'.$i.'</a></li>';
                }
                ?>
                <li>
                    <a href="/game/messages/index/<?= $page->next; ?>" aria-label="Next">
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