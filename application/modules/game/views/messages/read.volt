{% extends "messages/index.volt" %}

{% block content %}
    <div class="module_content message_content">
        <div class="avatar"><img src="{{ author.avatar }}" style="width: 100px;"/></div>
        <div class="topic">
            {{ msg.topic }}
            <div class="controls">
                {% if msg.saved is empty %}<i class="fa fa-times" type="1" msgid="{{ msg.id }}" data-toggle="tooltip" data-placement="bottom" title="{{ t['messages-del_topic'] }}"></i>
                <i class="fa fa-floppy-o" type="1" msgid="{{ msg.id }}" data-toggle="tooltip" data-placement="bottom" title="{{ t['messages-save_desc'] }}"></i>{% endif %}
            </div>
        </div>
        <div class="author">{% if msg.sended==0 %}{{ t['author'] }}{% else %}{{ t['messages-receiver'] }}{% endif %}: <a href="/game/profile/show/{{ msg.sender_id }}">{{ author.name }}</a> | {{ date('d-m-Y H:i:s', msg.date) }}</div>
        <div class="text" style="border-bottom: 1px dotted rgb(51, 102, 0);">{{ msgtext }}</div>

        {% if author.npc is empty %}
            <div style="margin: 10px; float: left; width: 100%;">
                <?php echo Phalcon\Tag::form(array('/game/messages/read/'.$msg -> id, 'method' => 'post')); ?>

                <div class="form-group">
                    <label for="respondMsg">{{ t['messages-respond'] }}</label>
                    <?php echo Phalcon\Tag::textArea(array("respond", "cols" => 10, "rows" => 4)); ?>
                </div>

                <div class="form-group">
                    <div class="col-sm-12" style="text-align: center;">
                        <?php echo Phalcon\Tag::submitButton(array($t['send'], 'style' => 'width: 70px;')); ?>
                    </div>
                </div>


                {{ end_form() }}
            </div>
        {% endif %}

        {% if page.items|length > 0 %}
        <div id="conversation_history">
            <div style="float: left; width: 100%; font-family: Pixel; border-top: 1px solid rgb(51, 102, 0); border-bottom: 1px solid rgb(51, 102, 0); padding: 10px 0px; text-align: center; margin-top: 20px;">{{ t['messages-topic_history'] }}</div>
            {% for conversation in page.items %}
                {% if conversation.Messages.readed == 0 %}
                <?php $conversation -> Messages -> save(['readed' => 1]); ?>
                {% endif %}
                <div style="float: left; width: 100%; margin: 5px 0px; padding-bottom: 5px; position: relative;" id="msg_conv_{{ conversation.Messages.id }}">
                    <div class="avatar">
                        <img src="<?php
                        if ($conversation -> Messages -> sended == 1) echo $character -> avatar;
                        else
                        {
                            if (isset($conversation -> Sender -> avatar)) echo $conversation -> Sender -> getAvatar();
                            else echo '/assets/images/defaultav.jpg';
                        }
                        ?>" style="width: 100px;" />
                    </div>
                    <div class="topic">
                        {{ conversation.Messages.topic }}
                        <div class="controls">
                            <i class="fa fa-times" type="2" msgid="{{ conversation.Messages.id }}" data-toggle="tooltip" data-placement="bottom" title="{{ t['messages-del_message'] }}"></i>
                            {% if conversation.Messages.saved is empty %}<i class="fa fa-floppy-o" type="2" msgid="{{ conversation.Messages.id }}" data-toggle="tooltip" data-placement="bottom" title="{{ t['messages-save_msg'] }}"></i>{% endif %}
                        </div>
                    </div>
                    <div class="author">
                        <?php
                        if ($conversation -> Messages -> sended == 1) echo $t['author'].': <a href="/game/profile/show/'.$character -> id.'">'.$character -> name.'</a>';
                        else
                        {
                            if (isset($conversation -> Sender -> name)) echo $t['author'].': <a href="/game/profile/show/'.$conversation -> Sender -> id.'">'.$conversation -> Sender -> name.'</a>';
                            else echo $t['author'].': '.$t['someone'];
                        }
                        ?> | <?php echo date('d-m-Y H:i:s', $conversation -> Messages -> date); ?>
                    </div>
                    <div class="text">{{ conversation.MyMessageText.text }}</div>
                </div>
            {% endfor %}
        </div>

        <nav style="text-align: center;">
            <ul class="pagination">
                <li>
                    <a href="/game/messages/read/{{ msg.id }}/<?= $page->before; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php
                for ($i = 1; $i <= $page->total_pages; $i++)
                {
                    echo '<li><a href="/game/messages/read/'.$msg -> id.'/'.$i.'">'.$i.'</a></li>';
                }
                ?>
                <li>
                    <a href="/game/messages/read/{{ msg.id }}/<?= $page->next; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
        {% endif %}
    </div>

    <script type="text/javascript">
        $(function() {
            $('.message_content .fa-times').click(function() {
                var delData = $(this);
                jConfirm('Czy na pewno chcesz usunąć wiadomości?', 'Potwierdzenie', function(r) {
                    if (r) {
                        if (delData.attr('type') == 1) document.location = '/game/messages/delete/' + delData.attr('msgid') + '/1';
                        else if (delData.attr('type') == 2)  ajaxLoad('/game/messages/delete/' + delData.attr('msgid') + '/2', {}, false, function(data) { if (data.counted > 0) $('#msg_conv_'+delData.attr('msgid')).hide(1000); else document.location = '/game/messages/index'; });
                    }
                });
            });
            $('.message_content .fa-floppy-o').click(function() {
                var delData = $(this);
                jConfirm('Czy na pewno chcesz zapisać wiadomości?', 'Potwierdzenie', function(r) {
                    if (r) {
                        if (delData.attr('type') == 1) document.location = '/game/messages/save/' + delData.attr('msgid') + '/1';
                        else if (delData.attr('type') == 2)  ajaxLoad('/game/messages/save/' + delData.attr('msgid') + '/2', {}, false, function(data) { delData.hide(1000); });
                    }
                });
            });
        });
    </script>

{% endblock %}