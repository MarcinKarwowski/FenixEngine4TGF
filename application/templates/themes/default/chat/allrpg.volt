{% extends "chat/rpg.volt" %}

{% block content %}
    <div class="module_content chat_module">

        <div style="float: left; width: 100%; margin: 0px 10px 20px;">{{ t['chat-session_list'] }}</div>

        <div>
            {% if page.items|length > 0 %}
                {% for rpg in page.items %}
                    <div msg="{{ rpg.id }}" class="chat_msg row_{{ rpg.id }}">
                        <div>{% if rpg.hide == 1 %}<i class="fa fa-lock"></i>{% else %}<i
                                    class="fa fa-unlock"></i>{% endif %} <a
                                    href="/game/chat/change/{{ rpg.id }}">{{ rpg.title }}</a></div>
                    </div>
                {% endfor %}
                <nav style="text-align: center;">
                    <ul class="pagination">
                        <li>
                            <a href="/game/chat/allrpg/<?= $page->before; ?>/<?php echo $type; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <li><a {% if page.current == 1 %}style="text-decoration: underline;"{% endif %} href="/game/chat/allrpg/1/<?php echo $type; ?>">1</a></li>
                        <?php

                        for ($i = ($page->current - 4); $i <= ($page->current + 4); $i++)
                        {
                        if ($i > 0 && $i != 1 && $i < $page -> total_pages)
                        {
                        echo '<li><a '.($page->current == $i ? 'style="text-decoration: underline;"' : '').' href="/game/chat/allrpg/'.$i.'/'. $type .'">'.$i.'</a></li>';
                        }
                        }
                        ?>
                        {% if page.total_pages > 1 %}<li><a {% if page.current == page.total_pages %}style="text-decoration: underline;"{% endif %} href="/game/chat/allrpg/<?= $page->total_pages ?>/<?php echo $type; ?>"><?= $page->total_pages ?></a></li>{% endif %}
                        <li>
                            <a href="/game/chat/allrpg/<?= $page->next; ?>/<?php echo $type; ?>" aria-label="Next">
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