<h1>{{ t['email-reset-password-remember'] }}</h1>
<p>{{ t['form-reset_pass_email'] }}</p>
<div style="text-align: center;">
    {{ form('class': 'form-horizontal', 'id': 'resendpass-form', 'action': 'session/forgot-password') }}
    <!-- Prepended text-->
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-envelope"></span> {{ t['form-email'] }}</span>
                {{ forms.get('forgotpassword').render('email', ['class': 'form-control', 'placeholder': t['form-write_email']]) }}
            </div>

        </div>
    </div>
    <!-- Button -->
    <div class="form-group">
        <label class="col-md-4 control-label" for="singlebutton"></label>

        <div class="col-md-4">
            {{ forms.get('forgotpassword').render(t['send']) }}
        </div>
    </div>
    {{ forms.get('forgotpassword').render('csrf', ['value': csrfToken]) }}
    </form>
</div>