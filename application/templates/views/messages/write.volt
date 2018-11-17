{% extends "messages/index.volt" %}

{% block content %}
    <div>{{ t['messages-write_desc'] }}</div>
    <div class="module_content">
        <p style="text-align: center;"></p>

        <div style="text-align: center;">
            <form action="/game/messages/write" method="POST" class="form-horizontal">
                <div class="form-group">
                    <label for="ReceiverID" class="col-sm-3 control-label">{{ t['messages-receiver_name'] }}</label>

                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="ReceiverName"
                               placeholder="{{ t['messages-receiver_desc'] }}" value="">
                        <input type="hidden" name="ReceiverID" value="0" id="ReceiverID"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="messageTopic" class="col-sm-3 control-label">{{ t['topic'] }}</label>

                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="messageTopic" name="messageTopic"
                               placeholder="{{ t['messages-topic_desc'] }}">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <textarea class="form-control" rows="3" name="messageText"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-9 col-sm-offset-3">
                        <input type="submit" value="{{ t['send'] }}" style="width: 70px;">
                    </div>
                </div>

            </form>
        </div>
    </div>

    <script>
        $(function () {
            $("#ReceiverName").autocomplete({
                source: "/game/refresh/getusers",
                minLength: 1,
                select: function (event, ui) {
                    $('#ReceiverID').attr('value', ui.item.id);
                }
            });
        });
    </script>
{% endblock %}