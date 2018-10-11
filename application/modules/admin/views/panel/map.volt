{% extends "../../../templates/admin.volt" %}

{% block pageContent %}
    <div class="module_content">
        <div class="box">
            <div class="box-body">
                <p style="text-align: center;">
                    {{ t['map-describe'] }}<br/>
                    {% if countfiles is defined %}
                        {{ t['map-countfiles'] }} {{ countfiles }}<br/>
                    {% endif %}
                    {{ t['map-memorylimit'] }} {{ memorylimit }} MB
                </p>

                <div style="text-align: center;">
                    <form action="/admin/game/panel/map" method="POST" class="form-horizontal"
                          enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="ReceiverID" class="col-sm-2 control-label">{{ t['panel-map'] }}</label>

                            <div class="col-sm-10">
                                <input type="file" class="form-control" name="fileToUpload" id="fileToUpload">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                                <input type="submit" value="{{ t['send'] }}" style="width: 70px;">
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
{% endblock %}

{% block addJS %}
    <script type="text/javascript">
        $(document).ready(function () {

        });
    </script>
{% endblock %}