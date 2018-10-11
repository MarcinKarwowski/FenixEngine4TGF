{% extends "messages/index.volt" %}

{% block content %}
    <div class="module_content message_content">
        <div style="margin-bottom: 10px;">{{ t['messages-sended_desc'] }}</div>
    {% if page.items|length > 0 %}
        {% for msg in page.items %}
            <div style="float: left; width: 100%; margin: 5px 0px; border-bottom: 1px dotted; padding-bottom: 5px; position: relative;">
                <div style="float: left; text-align: center; overflow: hidden; width: 50px; height: 50px;">
                    <img src="<?php
                        if ($msg -> Sender -> avatar) echo $msg -> Sender -> getAvatar();
                        else echo '/assets/images/defaultav.jpg';
                        ?>" style="width: 40px;" />
                </div>
                <div style="font-family: Pixel; font-size: 11px; float: left; padding: 5px 0px; width: 400px; overflow: hidden; text-align: left;">
                    {{ msg.Messages.topic }}
                </div>
                <div style="float: left;">
                    <?php
                        if (isset($msg -> Sender -> name)) echo $t['messages-receiver'].': <a href="/game/profile/show/'.$msg -> Sender -> id.'">'.$msg -> Sender -> name.'</a>';
                    else echo $t['messages-receiver'].': '.$t['someone'];
                    ?> | <?php echo date('d-m-Y H:i:s', $msg -> Messages -> date); ?>
                </div>
                <div style="position: absolute; right: 0px; top: 15px;">
                    <a href="/game/messages/read/{{ msg.Messages.id }}" class="btn btn-default">{{ t['read'] }}</a>
                </div>
            </div>
        {% endfor %}

        <nav style="text-align: center;">
            <ul class="pagination">
                <li>
                    <a href="/game/messages/sended/<?= $page->before; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php
                for ($i = 1; $i <= $page->total_pages; $i++)
                {
                echo '<li><a href="/game/messages/sended/'.$i.'">'.$i.'</a></li>';
                }
                ?>
                <li>
                    <a href="/game/messages/sended/<?= $page->next; ?>" aria-label="Next">
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