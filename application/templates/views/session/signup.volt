<h1>{{ t['form-registerform'] }}</h1>
<p>{{ t['form-signup'] }}</p>
<div style="text-align: center;">
    {{ form('class': 'form-horizontal', 'id': 'reg-form', 'action': 'session/signup') }}
    <fieldset>
        <!-- Prepended text-->
        <div class="form-group">
            <div class="col-md-12">
                <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="fa fa-user"></span> {{ t['form-nick'] }}</span>
                    {{ forms.get('signup').render('name', ['maxlength': 30, 'value': session.get('regName'), 'class': 'form-control', 'placeholder': t['form-write_nick']]) }}
                </div>

            </div>
        </div>

        <!-- Prepended text-->
        <div class="form-group">
            <div class="col-md-12">
                <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-envelope"></span> {{ t['form-email'] }}</span>
                    {{ forms.get('signup').render('semail', ['maxlength': 100, 'value': session.get('regEmail'), 'class': 'form-control', 'placeholder': t['form-write_email']]) }}
                </div>

            </div>
        </div>

        <!-- Prepended text-->
        <div class="form-group">
            <div class="col-md-12">
                <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="fa fa-minus-circle"></span> {{ t['form-password'] }}</span>
                    {{ forms.get('signup').render('spassword', ['class': 'form-control', 'placeholder': t['form-write_password']]) }}
                </div>

            </div>
        </div>

        <!-- Prepended text-->
        <div class="form-group">
            <div class="col-md-12">
                <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="fa fa-minus-circle"></span> {{ t['form-password'] }}</span>
                    {{ forms.get('signup').render('confirmPassword', ['class': 'form-control', 'placeholder': t['form-repeat_password']]) }}
                </div>

            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <div class="checkbox">
                    <label>
                        {{ forms.get('signup').render('terms') }} {{ t['form-rules'] }}
                    </label>
                </div>
            </div>
        </div>
        <!-- Button -->
        <div class="form-group">
            <label class="col-md-4 control-label" for="singlebutton"></label>

            <div class="col-md-4">
                {{ forms.get('signup').render(t['form-register']) }}
            </div>
        </div>
        {{ forms.get('signup').render('csrf', ['value': csrfToken]) }}

    </fieldset>
    </form>
</div>