{% extends "chat/rpg.volt" %}

{% block content %}
    <div class="module_content chat_module">

        <div style="float: left; width: auto; margin: 0px 10px 20px;">{{ t['chat-create_session'] }}</div>

        <div>
            {{ form("/game/chat/create/"~(rpg.id is not empty ? rpg.id : 0), "method": "post") }}

            <div class="form-group">
                <label for="respondMsg">{{ t['title'] }}</label>
                {{ text_field("title", "value": (rpg.title is not empty ? rpg.title : '')) }}
            </div>

            <div class="form-group">
                <label for="respondMsg">{{ t['chat-private_session'] }}</label>
                <?php
                echo $this->tag->selectStatic(array("hide",array(0 => "Nie",1 => "Tak"),"value" => (isset($rpg -> hide)
                ? $rpg -> hide : 0)));
                ?>
            </div>

            <div class="form-group">
                <label for="respondMsg">{{ t['chat-archived_session'] }}</label>
                <?php
                echo $this->tag->selectStatic(array("archived",array(0 => "Nie",1 => "Tak"),"value" => (isset($rpg ->
                archived) ? $rpg -> archived : 0)));
                ?>
            </div>

            <div class="form-group">
                {{ text_area("desc", "cols": 10, "rows": 8, "value": (rpg.desc is not empty ? rpg.desc : '')) }}
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="ReceiverName"
                       placeholder="{{ t['chat-session_permissions'] }}" value="" style="width: 100%;">

                <div id="permission_users">
                    {% if rpg.id is not empty %}
                        {% for chater in rpg.RoomCharacters %}
                            <div id="session_chater_{{ chater.id }}">{{ chater.name }} <i class="fa fa-times"></i> <input type="hidden" value="{{ chater.id }}" name="permissions[]" /></div>
                        {% endfor %}
                    {% endif %}
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-12" style="text-align: center;">
                    <?php echo Phalcon\Tag::submitButton(array($t['send'], 'style' => 'width: 70px;')); ?>
                </div>
            </div>


            {{ end_form() }}
        </div>
    </div>

    <style type="text/css">
        .form-group label {
            width: 30%;
        }

        .form-group input {
            padding: 5px;
            width: 69%;
        }

        .form-group textarea {
            width: 100%;
        }
        #permission_users div {
            float: left;
            width: 100%;
            margin: 5px 0 0 10px;
            cursor: pointer;
        }
    </style>

    <script>
        $(function () {
            $("textarea").sceditor(sceditoroptions);
            $('#permission_users').on('click', 'div', function() {
               $(this).remove();
            });
            $("#ReceiverName").autocomplete({
                source: "/game/refresh/getusers",
                minLength: 1,
                select: function (event, ui) {
                    if($('#session_chater_' + ui.item.id).length == 0)
                    {
                        $('#permission_users').append('<div id="session_chater_' + ui.item.id + '">' + ui.item.value + ' <i class="fa fa-times"></i> <input type="hidden" value="' + ui.item.id + '" name="permissions[]" /></div>');
                    }
                    $('#ReceiverName').val('').text('');
                }
            });
        });
    </script>
{% endblock %}