{% extends "admin.volt" %}

{% block pageContent %}
    {{ form('class': 'form-horizontal', 'id': 'editcharacter-form', 'action': '') }}
    <!-- Prepended text-->
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-circle"></span> {{ t['characters-name'] }}</span>
                {{ forms.get('editcharacter').render('name', ['class': 'form-control', 'placeholder': t['characters-name']]) }}
            </div>

        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-circle"></span> {{ t['characters-equipment'] }}</span>
                {{ forms.get('editcharacter').render('equipment', ['class': 'form-control', 'placeholder': t['characters-equipment']]) }}
            </div>

        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-circle"></span> {{ t['characters-spells'] }}</span>
                {{ forms.get('editcharacter').render('spells', ['class': 'form-control', 'placeholder': t['characters-spells']]) }}
            </div>

        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-circle"></span> {{ t['characters-events'] }}</span>
                {{ forms.get('editcharacter').render('events', ['class': 'form-control', 'placeholder': t['characters-events']]) }}
            </div>

        </div>
    </div>

    <!-- Button -->
    <div class="form-group">
        <label class="col-md-4 control-label" for="singlebutton"></label>

        <div class="col-md-4" style="text-align: center;">
            {{ forms.get('editcharacter').render(t['save']) }}
            <a href="/admin/users/show/{{ pageUsersId }}" class="btn btn-primary">{{ t['back'] }}</a>
        </div>
    </div>
    {{ forms.get('editcharacter').render('csrf', ['value': csrfToken]) }}
    </form>
{% endblock %}
