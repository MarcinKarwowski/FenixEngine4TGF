{% extends "../../../templates/admin.volt" %}

{% block pageContent %}
    {{ form('class': 'form-horizontal', 'id': 'mainconfigure-form', 'action': '') }}
    <!-- Prepended text-->
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-circle"></span> {{ t['configuration-game_title'] }}</span>
                {{ forms.get('mainconfigure').render('title', ['value': config.game.title, 'maxlength': 50, 'class': 'form-control', 'placeholder': t['configuration-game_title']]) }}
            </div>

        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-circle"></span> {{ t['configuration-game_description'] }}</span>
                {{ forms.get('mainconfigure').render('description', ['value': config.game.description, 'maxlength': 100, 'class': 'form-control', 'placeholder': t['configuration-game_description']]) }}
            </div>

        </div>
    </div>
        <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-circle"></span> {{ t['configuration-game_timetostart'] }}</span>
                {{ forms.get('mainconfigure').render('starttime', ['value': date('d-m-y', config.game.startTime), 'data-mask': '', 'data-inputmask': "'alias': 'dd-mm-yyyy'", 'maxlength': 25, 'class': 'form-control', 'placeholder': t['configuration-game_timetostart']]) }}
            </div>

        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-circle"></span> {{ t['configuration-game_keywords'] }}</span>
                {{ forms.get('mainconfigure').render('keywords', ['value': config.game.keywords, 'maxlength': 100, 'class': 'form-control', 'placeholder': t['configuration-game_description_desc']]) }}
            </div>

        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-circle"></span> {{ t['configuration-game_url'] }}</span>
                {{ forms.get('mainconfigure').render('url', ['value': config.game.baseUri, 'maxlength': 50, 'class': 'form-control', 'placeholder': t['configuration-game_url']]) }}
            </div>

        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-circle"></span> {{ t['configuration-register_off'] }}</span>
                {{ forms.get('mainconfigure').render('registeroff', ['class': 'form-control', 'placeholder': t['configuration-register_off']]) }}
            </div>

        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-circle"></span> {{ t['configuration-google_analitycs'] }}</span>
                {{ forms.get('mainconfigure').render('ga', ['value': config.game.GAIdentificator, 'maxlength': 50, 'class': 'form-control', 'placeholder': t['configuration-google_analitycs']]) }}
            </div>

        </div>
    </div>
    <h3>{{ t['configuration-layout'] }}</h3>
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-circle"></span> {{ t['configuration-template'] }}</span>
                {{ forms.get('mainconfigure').render('template', ['class': 'form-control', 'placeholder': t['configuration-template']]) }}
            </div>

        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
                <div class="input-group-addon" data-toggle="tooltip" data-placement="right" title="{{ t['configuration-template_text_color_desc'] }}"><i class="fa fa-question-circle"></i></div>
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-circle"></span> {{ t['configuration-template_text_color'] }}</span>
                {{ forms.get('mainconfigure').render('template_text_color', ['value': config.game.template_text_color, 'maxlength': 6, 'class': 'form-control', 'placeholder': t['configuration-template_text_color']]) }}
            </div>

        </div>
    </div>
    <h3>{{ t['configuration-emailconf'] }}</h3>
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-circle"></span> {{ t['configuration-email_sender'] }}</span>
                {{ forms.get('mainconfigure').render('emailName', ['value': config.mail.fromName, 'maxlength': 50, 'class': 'form-control', 'placeholder': t['configuration-email_sender']]) }}
            </div>

        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-circle"></span> {{ t['configuration-email'] }}</span>
                {{ forms.get('mainconfigure').render('email', ['value': config.mail.fromEmail, 'maxlength': 50, 'class': 'form-control', 'placeholder': t['configuration-email']]) }}
            </div>

        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-circle"></span> {{ t['configuration-servet_type'] }}</span>
                {{ forms.get('mainconfigure').render('emailServerType', ['value': config.mail.serverType, 'maxlength': 50, 'class': 'form-control', 'placeholder': t['configuration-servet_type']]) }}
            </div>

        </div>
    </div>
    <h4>{{ t['configuration-email_smtp'] }}</h4>
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-circle"></span> {{ t['configuration-smtp_server'] }}</span>
                {{ forms.get('mainconfigure').render('emailServer', ['value': config.mail.smtp.server, 'maxlength': 50, 'class': 'form-control', 'placeholder': t['configuration-smtp_server']]) }}
            </div>

        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-circle"></span> {{ t['configuration-smtp_port'] }}</span>
                {{ forms.get('mainconfigure').render('emailServerPort', ['value': config.mail.smtp.port, 'maxlength': 5, 'class': 'form-control', 'placeholder': t['configuration-smtp_port']]) }}
            </div>

        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-circle"></span> {{ t['configuration-smtp_security'] }}</span>
                {{ forms.get('mainconfigure').render('emailServerSecurity', ['class': 'form-control']) }}
            </div>

        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-circle"></span> {{ t['configuration-smtp_username'] }}</span>
                {{ forms.get('mainconfigure').render('emailServerUser', ['value': config.mail.smtp.username, 'maxlength': 50, 'class': 'form-control', 'placeholder': t['configuration-smtp_username']]) }}
            </div>

        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-circle"></span> {{ t['configuration-smtp_password'] }}</span>
                {{ forms.get('mainconfigure').render('emailServerPass', ['value': config.mail.smtp.password, 'maxlength': 50, 'class': 'form-control', 'placeholder': t['configuration-smtp_password']]) }}
            </div>

        </div>
    </div>
    <h3>{{ t['configuration-custom_el'] }}</h3>
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-circle"></span> {{ t['configuration-custom_code'] }}</span>
                {{ forms.get('mainconfigure').render('custom', ['value': config.game.custom, 'maxlength': 1000, 'class': 'form-control', 'placeholder': t['configuration-custom_code']]) }}
            </div>

        </div>
    </div>

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
