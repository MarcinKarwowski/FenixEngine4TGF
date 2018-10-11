{% extends "account/index.volt" %}

{% block content %}
    <div>{{ t['account-avatar_desc'] }}</div>
    <div class="module_content">
        <p style="text-align: center;"><img style="width: 170px;" src="{{ avatarPath }}" /></p>
        <div style="text-align: center;">
            <form action="/game/account/upload" method="POST" enctype="multipart/form-data">
                <input type="file" name="fileData" style="margin: 10px auto;">
                <input type="submit" value="{{ t['account-send_file'] }}">
            </form>
        </div>
    </div>
{% endblock %}