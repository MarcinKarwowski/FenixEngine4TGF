{% extends "../../../templates/admin.volt" %}

{% block pageContent %}
    {{ form('class': 'form-horizontal', 'id': 'mainconfigure-form', 'action': '') }}
    <!-- Prepended text-->

    {{ forms.get('mainconfigure').renderDecorated('title', ['value': config.game.title, 'class': 'form-control', 'maxlength': 50]) }}
    {{ forms.get('mainconfigure').renderDecorated('description', ['value': config.game.description, 'class': 'form-control', 'maxlength': 100]) }}
    {{ forms.get('mainconfigure').renderDecorated('starttime', ['value': date('d-m-y', config.game.startTime), 'data-mask': '', 'data-inputmask': "'alias': 'dd-mm-yyyy'", 'maxlength': 25, 'class': 'form-control', 'placeholder': t['configuration-game_timetostart']]) }}
    {{ forms.get('mainconfigure').renderDecorated('keywords', ['value': config.game.keywords, 'maxlength': 100, 'class': 'form-control']) }}
    {{ forms.get('mainconfigure').renderDecorated('url', ['value': config.game.baseUri, 'class': 'form-control', 'maxlength': 50]) }}
    {{ forms.get('mainconfigure').renderDecorated('registeroff', ['value': config.game.registerOff, 'class': 'form-control']) }}
    {{ forms.get('mainconfigure').renderDecorated('ga', ['value': config.game.GAIdentificator, 'class': 'form-control', 'maxlength': 50]) }}
    <h3>{{ t['configuration-layout'] }}</h3>
    {{ forms.get('mainconfigure').renderDecorated('template', ['value': config.game.template, 'class': 'form-control']) }}
    {{ forms.get('mainconfigure').renderDecorated('template_text_color', ['value': config.game.template_text_color, 'class': 'form-control', 'maxlength': 6, 'tooltip': t['configuration-template_text_color_desc']]) }}
    <h3>{{ t['configuration-emailconf'] }}</h3>
    {{ forms.get('mainconfigure').renderDecorated('emailName', ['value': config.mail.fromName, 'class': 'form-control', 'maxlength': 50]) }}
    {{ forms.get('mainconfigure').renderDecorated('email', ['value': config.mail.fromEmail, 'class': 'form-control', 'maxlength': 50]) }}
    {{ forms.get('mainconfigure').renderDecorated('emailServerType', ['value': config.mail.serverType, 'class': 'form-control', 'maxlength': 50]) }}
    <h4>{{ t['configuration-email_smtp'] }}</h4>
    {{ forms.get('mainconfigure').renderDecorated('emailServer', ['value': config.mail.smtp.server, 'class': 'form-control', 'maxlength': 50]) }}
    {{ forms.get('mainconfigure').renderDecorated('emailServerPort', ['value': config.mail.smtp.port, 'class': 'form-control', 'maxlength': 5]) }}
    {{ forms.get('mainconfigure').renderDecorated('emailServerSecurity', ['value': config.mail.smtp.security, 'class': 'form-control']) }}
    {{ forms.get('mainconfigure').renderDecorated('emailServerUser', ['value': config.mail.smtp.username, 'class': 'form-control', 'maxlength': 50]) }}
    {{ forms.get('mainconfigure').renderDecorated('emailServerPass', ['value': config.mail.smtp.password, 'class': 'form-control', 'maxlength': 50]) }}
    <h3>{{ t['configuration-custom_el'] }}</h3>
    {{ forms.get('mainconfigure').renderDecorated('custom', ['value': config.game.custom, 'class': 'form-control', 'maxlength': 1000]) }}

    <!-- Button -->
    <div class="form-group">
        <label class="col-md-4 control-label" for="singlebutton"></label>

        <div class="col-md-4" style="text-align: center;">
            {{ forms.get('mainconfigure').render(t['save']) }}
        </div>
    </div>
    {{ forms.get('mainconfigure').render('csrf', ['value': csrfToken]) }}
    </form>
{% endblock %}
