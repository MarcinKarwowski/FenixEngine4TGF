{% extends "profile/index.volt" %}

{% block content %}
    <div class="module_content profile_module">
        <div>
            {{ form("/game/profile/edit/"~(article.id is not empty ? article.id : 0), "method": "post") }}

            <div class="form-group">
                <label for="respondMsg">{{ t['title'] }}</label>
                {{ text_field("title", "value": (article.title is not empty ? article.title : '')) }}
            </div>

            <div class="form-group">
                {{ text_area("desc", "cols": 10, "rows": 8, "value": (article.text is not empty ? article.text : '')) }}
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
        });
    </script>
{% endblock %}