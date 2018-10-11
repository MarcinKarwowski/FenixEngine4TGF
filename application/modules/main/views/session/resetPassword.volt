<h1>{{ t['email-reset-password'] }}</h1>
<p>{{ t['form-reset_pass'] }}</p>
<div style="text-align: center;">
    {{ form('class': 'form-horizontal', 'id': 'resetpass-form', 'action': '') }}
    <!-- Prepended text-->
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-circle"></span> {{ t['form-password'] }}</span>
                {{ forms.get('resetpassword').render('password', ['class': 'form-control', 'placeholder': t['form-write_password']]) }}
            </div>

        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-circle"></span> {{ t['form-password'] }}</span>
                {{ forms.get('resetpassword').render('confirmPassword', ['class': 'form-control', 'placeholder': t['form-write_password']]) }}
            </div>

        </div>
    </div>
    <!-- Button -->
    <div class="form-group">
        <label class="col-md-4 control-label" for="singlebutton"></label>

        <div class="col-md-4">
            {{ forms.get('resetpassword').render(t['save']) }}
        </div>
    </div>
    {{ forms.get('resetpassword').render('csrf', ['value': csrfToken]) }}
    </form>
</div>