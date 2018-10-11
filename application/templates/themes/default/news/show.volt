{% extends "news/index.volt" %}

{% block content %}
    <div class="module_content news_module">
        <div>
            <h1>{{ article.title }}</h1>

            <div>{{ text }}</div>
        </div>

        <form action="/game/news/comment/{{ article.id }}" method="POST" id="chat_send_form" class="form-horizontal">
            <div class="form-group">
                <div class="col-sm-12">
                    <textarea class="form-control" rows="3" name="messageText" id="chat_write_area"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <input type="submit" value="{{ t['send'] }}" style="width: 70px;">
                </div>
            </div>
        </form>

        <div id="news_comments">
            {% if acl.checkPermit('chat_delpost') === true %}
                {% set candel = 1 %}
            {% else %}
                {% set candel = 0 %}
            {% endif %}
            {% for comment in comments.items %}
                <div msg="{{ comment.id }}" class="chat_msg row_{{ comment.id }}">
                    <img src="{{ comment.author.getAvatar() }}" style="width: 50px; float: left; margin: 10px;" />
                    <div style="margin-top: 10px;">{{ date('d-m-Y H:i:s', comment.publishdate) }} - <a href="/game/profile/show/{{ comment.character_id }}">{{ comment.author.name }}</a>
                        {% if candel == 1 %}
                            <i class="fa fa-times candel"></i>
                        {% endif %}
                    </div>
                    <div>{{ comment.text }}</div>
                </div>
            {% endfor %}
        </div>
        <nav style="text-align: center;">
            <ul class="pagination">
                <li>
                    <a href="/game/news/show/{{ article.id }}/<?= $comments->before; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php
                for ($i = 1; $i <= $comments->total_pages; $i++)
                {
                echo '<li><a href="/game/news/show/'.$article -> id.'/'.$i.'">'.$i.'</a></li>';
                }
                ?>
                <li>
                    <a href="/game/news/show/{{ article.id }}/<?= $comments->next; ?>" aria-label="Next">
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
            $('#news_comments').on('click', 'i.candel', function() {
                var post = $(this).parents('.chat_msg');
                var postid = post.attr('msg');
                ajaxLoad('/game/news/delete/'+postid, {}, false, function() {
                    //
                });
                post.remove();
            });
        });
    </script>
{% endblock %}