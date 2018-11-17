{% extends "admin.volt" %}

{% block pageContent %}
    {{ form('class': 'form-horizontal', 'id': 'templateconfigure-form', 'action': '') }}
    {{ forms.get('templateconfigure').renderDecorated('template', ['value': config.game.template, 'class': 'form-control']) }}
    {{ forms.get('templateconfigure').renderDecorated('template_text_color', ['value': config.game.template_text_color is defined ? config.game.template_text_color : '', 'class': 'form-control', 'maxlength': 6, 'tooltip': t['configuration-template_text_color_desc']]) }}
    {{ forms.get('templateconfigure').renderDecorated('custom', ['value': config.game.custom, 'class': 'form-control text-field', 'maxlength': 1000]) }}
    <!-- Button -->
    <div class="form-group">
        <label class="col-md-4 control-label" for="singlebutton"></label>

        <div class="col-md-4" style="text-align: center;">
            {{ forms.get('templateconfigure').render(t['save']) }}
        </div>
    </div>
    {{ forms.get('templateconfigure').render('csrf', ['value': csrfToken]) }}
    </form>
    <h3 style="text-align: center;">{{ t['template-header'] }}</h3>
    <form action="/admin/template/upload" method="POST" enctype="multipart/form-data">
        <input type="file" name="fileData" style="margin: 10px auto;">
        <div class="col-md-12" style="text-align: center;">
            <input type="submit" class="btn btn-success" value="{{ t['account-send_file'] }}">
        </div>
    </form>
{% endblock %}
