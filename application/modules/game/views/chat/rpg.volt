{% extends "twocolumn.volt" %}

{% block menu %}
    <ul id="content_menu">
        <li class="content_menu_nagl chat_nagl">{{ t['chat-sessions_panel'] }}</li>
        <div id="chat_chaters">
                <li class="chat_chater">
                    <a href="/game/chat/rpg/1">Aktywne sesje</a>
                    <a href="">Lista sesji</a>
                    <a href="/game/chat/create/0">Rozpocznij sesję</a>
                    <a href="/game/chat/myrpg/1/0">Twoje sesję</a>
                </li>
        </div>
    </ul>
{% endblock %}

{% block content %}
    <div class="module_content chat_module">

        <div style="float: left; width: auto; margin: 0px 10px 20px;" id="chat_desc">{{ t['chat-session_list'] }}</div>

        <div id="chat_display_msg">
            {% if page.items|length > 0 %}
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
                        echo '
                        <li><a href="/game/messages/index/'.$i.'">'.$i.'</a></li>
                        ';
                        }
                        ?>
                        <li>
                            <a href="/game/messages/index/<?= $page->next; ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                {% for rpg in page.items %}
                    <div msg="{{ rpg.Chats.id }}" class="chat_msg row_{{ rpg.Chats.id }}">
                        <div>{% if rpg.Chats.priv == 1 %}<i class="fa fa-lock"></i>{% else %}<i class="fa fa-unlock"></i>{% endif %} <a href="/game/chat/change/{{ rpg.Chats.id }}">{{ rpg.Chats.title }}</a></div>
                        <div>{{ t['chat-latest_post'] }}: {{ rpg.ChatsMessages.writer.name }} - {{ date('H:i:s d-m-Y', rpg.ChatsMessages.date ) }}</div>
                    </div>
                {% endfor %}
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
                        echo '
                        <li><a href="/game/messages/index/'.$i.'">'.$i.'</a></li>
                        ';
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
                <div style="text-align: center;width: 100%;">{{ t['chat-no_sessions'] }}</div>
            {% endif %}
        </div>
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
        $(function () {
            $('#chat_display_msg').on('click', 'i.candel', function () {
                var post = $(this).parents('.chat_msg');
                var postid = post.attr('msg');
                ajaxLoad('/game/chat/delete/' + postid, {}, false, function () {
                    //
                });
                post.remove();
            });
        });
    </script>
{% endblock %}