{% extends "../../../templates/admin.volt" %}

{% block pageContent %}

  {{ form('class': 'form-horizontal', 'id': 'gameconfigure-form', 'action': '') }}
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
            <div class="input-group-addon" data-toggle="tooltip" data-placement="right" title="{{ t['panel-characters_amount_desc'] }}"><i class="fa fa-question-circle"></i></div>
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-circle"></span> {{ t['panel-characters_amount'] }}</span>
                {{ forms.get('gameconfigure').render('charactersAmount', ['value': config.game.params.charactersAmount, 'maxlength': 1, 'class': 'form-control', 'placeholder': t['panel-characters_amount_desc']]) }}
            </div>

        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-circle"></span> {{ t['configuration-level_off'] }}</span>
                {{ forms.get('gameconfigure').render('leveloff', ['class': 'form-control', 'placeholder': t['configuration-level_off']]) }}
            </div>

        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
            <div class="input-group-addon" data-toggle="tooltip" data-placement="right" title="{{ t['panel-level_cap'] }}"><i class="fa fa-question-circle"></i></div>
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-circle"></span> {{ t['panel-level_cap'] }}</span>
                {{ forms.get('gameconfigure').render('levelCap', ['value': config.game.params.levelCap, 'maxlength': 3, 'class': 'form-control', 'placeholder': t['panel-level_cap']]) }}
            </div>

        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
            <div class="input-group-addon" data-toggle="tooltip" data-placement="right" title="{{ t['panel-era_date'] }}"><i class="fa fa-question-circle"></i></div>
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-circle"></span> {{ t['panel-era_date'] }}</span>
                {{ forms.get('gameconfigure').render('eraDate', ['value': config.game.params.eraDate, 'maxlength': 6, 'class': 'form-control', 'placeholder': t['panel-era_date']]) }}
            </div>

        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <div class="input-group">
            <div class="input-group-addon" data-toggle="tooltip" data-placement="right" title="{{ t['map-desc'] }}"><i class="fa fa-question-circle"></i></div>
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-circle"></span> {{ t['panel-map'] }}</span>
                {{ forms.get('gameconfigure').render('mapOn', ['value': config.game.params.mapOn, 'class': 'form-control', 'placeholder': t['panel-mapon']]) }}
            </div>

        </div>
    </div>


    <!-- Button -->
    <div class="form-group">
        <label class="col-md-4 control-label" for="singlebutton"></label>

        <div class="col-md-4" style="text-align: center;">
            {{ forms.get('gameconfigure').render(t['save']) }}
        </div>
    </div>
    {{ forms.get('gameconfigure').render('csrf', ['value': csrfToken]) }}
    </form>

{% endblock %}
