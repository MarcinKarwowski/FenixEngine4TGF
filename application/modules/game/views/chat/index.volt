{% extends "twocolumn.volt" %}

{% block menu %}
    <ul id="content_menu">
        <li class="content_menu_nagl chat_nagl"><i class="fa fa-users"></i> {{ t['chat-room_users_nagl'] }}</li>
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

        <div style="float: left; width: auto; margin: 0px 10px 20px;" id="chat_desc">
            {{ desc }}
            <?php if ($auth['group'] == 'Admin') { ?>
                <a href="/game/chat/create/{{ room_id }}">Edytuj</a>
            <?php } ?>
        </div>

        <div style="text-align: center;">
            <form action="/game/chat/write" method="POST" id="chat_send_form" class="form-horizontal" onsubmit="ajaxLoad('/game/chat/write', {formName: 'chat_send_form'}, true, function() { $('#chat_write_area').val(''); fenixrefres(); }); return false;">
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

        <div id="{% if messages.current == 1 %}chat_display_msg{% endif %}">
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
                    <div>{{ msg.msg }}</div>
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
                <?php
                for ($i = 1; $i <= $messages->total_pages; $i++)
                {
                echo '<li><a href="/game/chat/index/'.$i.'">'.$i.'</a></li>';
                }
                ?>
                <li>
                    <a href="/game/chat/index/<?= $messages->next; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <style type="text/css">
        #content_menu li.chat_chater {
            text-align: left;
            text-indent: 20px;
        }
        #content_menu li.chat_chater a {
            font-family: Arial;
            font-size: 16px;
            font-weight: bold;
        }
        #chat_display_msg .chat_msg {
            float: left;
            width: 100%;
        }
        #chat_display_msg div.chat_msg:nth-child(even) {
            background: #0F0F0F;
            color: #556B2F;
        }
        .chat_msg .candel {
            cursor: pointer;
        }
        .chat_msg .candel:hover {
            color: #009966;
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
