{% extends "threecolumns.volt" %}

{% block menu %}
    <h1>{{ t['chat-room_users_nagl'] }}</h1>
    <ul id="content_menu">
        <div id="chat_chaters">
            {% for chater in chaters %}
                <li class="chat_chater">
                    <a  href="/game/profile/show/{{ chater.id }}">{{ chater.name }}</a>
                </li>
            {% endfor %}
        </div>
    </ul>
{% endblock %}

{% block content %}
    <div class="module_content chat_module">

        <h3 class="content_title">
        {{ title }} <?php if ($auth['group'] == 'Admin') { ?><a style="font-size: 12px;" href="/game/chat/create/{{ room_id }}">[Edytuj]</a><?php } ?>
        </h3>
        <div style="float: left; width: auto; margin: 0px 10px 20px;" id="chat_desc">
            {{ desc }}
        </div>

        <div style="text-align: center;">
            <form action="/game/chat/write/{{ room_id }}" method="POST" id="chat_send_form" class="form-horizontal" onsubmit="ajaxLoad('/game/chat/write/{{ room_id }}', {formName: 'chat_send_form'}, true, function() { $('#chat_write_area').val(''); fenixrefres(); }); return false;">
                <div class="form-group">
                    <div class="col-sm-12">
                        <textarea onkeydown="checkenter(event, '#chat_send_form')" class="form-control" rows="3" name="messageText" id="chat_write_area"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        {%  if acl.checkPermit('chat_npc') === true %}
                            <input type="text" id="messageNPC" name="messageNPC" placeholder="{{ t['chat-room_npc_name'] }}">
                        {% endif %}

                        <input type="submit" value="{{ t['send'] }}" style="width: 70px;">
                        <input type="checkbox" id="enterCheckbox" data-toggle="tooltip" data-placement="bottom" title="Zaznacz jeśli chcesz wysyłać wiadomości naciskając Enter" />
                    </div>
                </div>
            </form>
        </div>

        <div class="chat_display_msg" id="{% if messages.current == 1 %}chat_display_msg{% endif %}">
            {% if acl.checkPermit('chat_delpost') === true %}
                {% set candel = 1 %}
            {% else %}
                {% set candel = 0 %}
            {% endif %}
            {% for index, msg in messages.items %}
                <div msg="{{ msg.id }}" class="chat_msg row_{{ msg.id }}">
                    <img src="{{ msg.writer.getAvatar() }}" style="width: 50px; float: left; margin: 10px;" />
                    <div style="margin-top: 10px;">
                        <?php echo date('d-m-Y H:i:s', $msg -> date); ?> - <a href="/game/profile/show/{{ msg.writer.id }}">{{ msg.writer.name }}</a>
                        {% if candel == 1 %}
                            <i class="fa fa-times candel"></i>
                        {% endif %}
                    </div>
                    <div class="chat-message">{{ msg.msg }}</div>
                </div>
            {% endfor %}
        </div>
        <nav style="text-align: center;">
            <ul class="pagination">
                <li>
                    <a href="/game/chat/index/<?= $messages->before; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li><a {% if messages.current == 1 %}style="text-decoration: underline;"{% endif %} href="/game/chat/index/{{ room_id }}/1">1</a></li>
                <?php

                        for ($i = ($messages->current - 4); $i <= ($messages->current + 4); $i++)
                {
                if ($i > 0 && $i != 1 && $i < $messages -> total_pages)
                {
                echo '<li><a '.($messages->current == $i ? 'style="text-decoration: underline;"' : '').' href="/game/chat/index/'.$room_id.'/'.$i.'">'.$i.'</a></li>';
                }
                }
                ?>
                {% if messages.total_pages > 1 %}<li><a {% if messages.current == messages.total_pages %}style="text-decoration: underline;"{% endif %} href="/game/chat/index/{{ room_id }}/<?= $messages->total_pages ?>"><?= $messages->total_pages ?></a></li>{% endif %}
                <li>
                    <a href="/game/chat/index/{{ room_id }}/<?= $messages->next; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <style type="text/css">
        #content_menu li.chat_chater {
            text-align: left;
            text-indent: 0px;
        }
        #content_menu li.chat_chater a {
            font-family: "LithosR";
            font-size: 14px;
        }
        .chat_display_msg .chat_msg {
            float: left;
            width: 100%;
        }
        .chat_display_msg div.chat_msg:nth-child(even) {
            background: #0F0F0F;
            color: #5C4033;
        }
        .chat_msg .candel {
            cursor: pointer;
        }
        .chat_msg .candel:hover {
            color: #e7cf9f;
        }
        .chat-message {
            color: #CDAA7D;
            margin-bottom: 10px;
        }
    </style>
    <script type="text/javascript">
        $(function() {
            $('#chat_display_msg').on('click', 'i.candel', function() {
                var post = $(this).parents('.chat_msg');
                var postid = post.attr('msg');
                ajaxLoad('/game/chat/delete/'+postid, {}, false, function() {
                    //
                });
                post.remove();
            });
        });
    </script>
{% endblock %}
