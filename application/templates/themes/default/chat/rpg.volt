{% extends "threecolumns.volt" %}

{% block menu %}
    <h1>{{ t['chat-sessions_panel'] }}</h1>
    <ul id="content_menu">
        <div>
            <li class="chat_chater"><a href="/game/chat/rpg/1">Aktywne sesje</a></li>
            <li class="chat_chater"><a href="/game/chat/allrpg/1/0">Lista sesji</a></li>
            <li class="chat_chater">-- <a href="/game/chat/allrpg/1/1">Sesje archiwalne</a></li>
            <li class="chat_chater"><a href="/game/chat/create/0">Rozpocznij sesję</a></li>
            <li class="chat_chater"><a href="/game/chat/myrpg/1/0">Twoje sesje</a></li>
            <li class="chat_chater">-- <a href="/game/chat/myrpg/1/1">Archiwum</a></li>
        </div>
    </ul>

    <style type="text/css">
        #content_menu li.chat_chater {
            text-align: left;
            text-indent: 0px;
        }
        #content_menu li.chat_chater a {
            font-family: "LithosR";
            font-size: 14px;
        }
        #chat_display_msg .chat_msg {
            float: left;
            width: 100%;
        }
        #chat_display_msg div.chat_msg:nth-child(even) {
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
{% endblock %}

{% block content %}
    <div class="module_content chat_module">

        <div style="width: auto; margin: 0px 10px 20px;">{{ t['chat-session_list'] }}</div>

        <div>
            {% if page.items|length > 0 %}
                {% for rpg in page.items %}
                    <div msg="{{ rpg.Chats.id }}" class="chat_msg row_{{ rpg.Chats.id }}">
                        <div>{% if rpg.Chats.hide == 1 %}<i class="fa fa-lock"></i>{% else %}<i
                                    class="fa fa-unlock"></i>{% endif %} <a
                                    href="/game/chat/change/{{ rpg.Chats.id }}">{{ rpg.Chats.title }}</a></div>
                        <div>{{ t['chat-latest_post'] }}: {{ rpg.ChatsMessages.writer.name }}
                            - {{ date('H:i:s d-m-Y', rpg.ChatsMessages.date ) }}</div>
                    </div>
                {% endfor %}
                <nav style="text-align: center;">
                    <ul class="pagination">
                        <li>
                            <a href="/game/chat/rpg/<?= $page->before; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <li><a {% if page.current == 1 %}style="text-decoration: underline;"{% endif %} href="/game/chat/rpg/1">1</a></li>
                        <?php

                        for ($i = ($page->current - 4); $i <= ($page->current + 4); $i++)
                        {
                            if ($i > 0 && $i != 1 && $i < $page -> total_pages)
                            {
                                echo '<li><a '.($page->current == $i ? 'style="text-decoration: underline;"' : '').' href="/game/chat/rpg/'.$i.'">'.$i.'</a></li>';
                            }
                        }
                        ?>
                        {% if page.total_pages > 1 %}<li><a {% if page.current == page.total_pages %}style="text-decoration: underline;"{% endif %} href="/game/chat/rpg/<?= $page->total_pages ?>"><?= $page->total_pages ?></a></li>{% endif %}
                        <li>
                            <a href="/game/chat/rpg/<?= $page->next; ?>" aria-label="Next">
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