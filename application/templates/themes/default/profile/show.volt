{% extends "profile/index.volt" %}

{% block content %}
    <div class="module_content profile_module">
        <div class="profile_bio">
            <div class="profile_avatar"><img src="{{ avatarPath }}"/></div>
            <div class="profile_info">
                <div class="profile_char_name"><a href="/game/profile/next/{{ charid }}/1" style="float: left;"><i
                                class="fa fa-chevron-circle-left"></i></a> {{ name }} [{{ users_id }}] <a
                            href="/game/profile/next/{{ charid }}/2" style="float: right;"><i
                                class="fa fa-chevron-circle-right"></i></a></div>

                {% for special in specials %}
                    <div class="profile_special">
                        <div class="special_label">{{ special['label'] }}</div>{{ special['value'] }}{% if special['level'] != 0 %}: {{ special['level'] }}{% endif %}
                    </div>
                {% endfor %}
            </div>
        </div>
        <div class="profile_additions">
            <div class="additions_menu">
                <div show="statistics">Statystyki</div>
                <div show="equip">Ekwipunek</div>
                <div show="magic">Czary/Skile</div>
                <div show="works">Zdarzenia</div>
            </div>
            <div id="statistics" class="addition_tabs">
                <div style="float: left; width: 49%; text-align: center;">
                    {% set statlabel = '' %}
                    {% for index, stat in stats %}
                        {% if statlabel != index %}
                            <div class="statistic_label">{{ index }}</div>{% endif %}
                        {% for elem in stat %}
                            <div class="statistic_stat">
                                <div class="statistic_stat_label" data-toggle="tooltip" data-placement="right"
                                     title="{{ elem['desc'] }}">{{ elem['name'] }}:
                                </div> <span id="statistic_{{ elem['id'] }}">{{ elem['value'] }}</span> {% if pc > 0 and users_id == character.acc %}<i statid="{{ elem['id'] }}" class="fa fa-plus-circle expand_stats"></i>{% endif %}</div>
                        {% endfor %}
                    {% endfor %}
                </div>
                {% if config.game.params.levelOff === false %}
                <div style="float: left; width: 49%; text-align: center;">
                    <div class="statistic_label">{{ t['profile-level_label'] }}</div>
                    <div class="statistic_stat"><div class="statistic_stat_label">{{ t['profile-level'] }}:</div> {{ level }}</div>
                    {% if users_id == character.acc %}
                    <div class="statistic_stat"><div class="statistic_stat_label">{{ t['profile-pd'] }}:</div> {{ pd }}/{{ nextlevel }}</div>
                    <div class="statistic_stat"><div class="statistic_stat_label">{{ t['profile-pc'] }}:</div> <span id="stats_points">{{ pc }}</span></div>
                    {% endif %}
                </div>
                {% endif %}
            </div>
            <div id="equip" class="addition_tabs" style="display: none;">
                <h1>Ekwipunek</h1>
                {% if equipment|length == 0 %}
                    <div style="text-align: center;">Brak przedmiotów</div>
                {% else %}
                    {{ equipment }}
                {% endif %}
                    <div class="spell_editor" style="{% if auth['permissions']['profile_edit'] is not defined %}display: none;{% endif %} margin-top: 20px;">
                            {{ form("/game/profile/extend/equipment/"~(charid), "method": "post") }}

                            <div class="form-group">
                                {{ text_area("desc", "style": 'width: 100%', "rows": 18, "value": (equipment is not empty ? equipment : '')) }}
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12" style="text-align: center;">
                                    <?php echo Phalcon\Tag::submitButton(array($t['send'], 'style' => 'width: 70px;')); ?>
                                </div>
                            </div>

                            {{ end_form() }}
                    </div>
            </div>
            <div id="magic" class="addition_tabs" style="display: none;">
                <h1>Czary/Skile</h1>
                {% if spells|length == 0 %}
                    <div style="text-align: center;">Brak czarów/skili</div>
                {% else %}
                    {{ spells }}
                {% endif %}
                    <div class="spell_editor" style="{% if auth['permissions']['profile_edit'] is not defined %}display: none;{% endif %} margin-top: 20px;">
                            {{ form("/game/profile/extend/spells/"~(charid), "method": "post") }}

                            <div class="form-group">
                                {{ text_area("desc", "style": 'width: 100%', "rows": 18, "value": (spells is not empty ? spells : '')) }}
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12" style="text-align: center;">
                                    <?php echo Phalcon\Tag::submitButton(array($t['send'], 'style' => 'width: 70px;')); ?>
                                </div>
                            </div>

                            {{ end_form() }}
                    </div>
            </div>
            <div id="works" class="addition_tabs" style="display: none;">
                <h1>Zdarzenia</h1>
                  {% if events|length == 0 %}
                      <div style="text-align: center;">Brak zdarzeń</div>
                  {% else %}
                      {{ events }}
                  {% endif %}
                                      <div class="spell_editor" style="{% if auth['permissions']['profile_edit'] is not defined %}display: none;{% endif %} margin-top: 20px;">
                                              {{ form("/game/profile/extend/events/"~(charid), "method": "post") }}

                                              <div class="form-group">
                                                  {{ text_area("desc", "style": 'width: 100%', "rows": 18, "value": (events is not empty ? events : '')) }}
                                              </div>

                                              <div class="form-group">
                                                  <div class="col-sm-12" style="text-align: center;">
                                                      <?php echo Phalcon\Tag::submitButton(array($t['send'], 'style' => 'width: 70px;')); ?>
                                                  </div>
                                              </div>

                                              {{ end_form() }}
                                      </div>
            </div>
        </div>

        <div style="margin-top: 20px; float: left;">
            <div id="history" class="addition_tabs">
                <?php if ($users_id == $character -> acc && $status == null) { ?>
                <div class="profile_mood" id="profile_mood">{{ t['profile-char_status'] }}</div>
                <?php } elseif ($status != null) { ?>
                <div class="profile_mood" id="profile_mood">{{ status }}</div>
                <?php } ?>
            </div>
        </div>
    </div>

    <script type="application/javascript">
        $(function () {
            $('.expand_stats').click(function() {
                var statid = $(this).attr('statid');
                $.ajaxq('standard', {
                    url: '/game/profile/statsup/'+statid,
                    context: document.body
                }).done(function (data) {
                    if (data.done === false) {
                        jAlert('Nie można rozwinąć cechy');
                    }
                    else {
                        var pc = parseInt($('#stats_points').text());
                        if (pc-1 <= 0)
                        {
                            $('.expand_stats').each(function () {
                                $(this).remove();
                            })
                        }
                        else
                        {
                            $('#stats_points').html(pc-1);
                            $('#statistic_'+statid).html((parseInt($('#statistic_'+statid).text())+1));
                        }
                    }
                });
            });
            $('.additions_menu div').click(function () {
                var show = $(this).attr('show');
                $('.addition_tabs').each(function () {
                    $(this).hide();
                });
                $('#' + show).show();
            });

            $.fn.editable.defaults.mode = 'popup';
            {% if users_id is sameas(character.acc) %}
            $('#profile_mood').editable({
                placement: 'bottom',
                type: 'textarea',
                pk: {{ character.id }},
                url: '/game/profile/savemood',
                title: 'Opisz nastrój',
                showbuttons: 'bottom',
                validate: function (value) {
                    if ($.trim(value) == '') {
                        return '{{ t['profile-char_status_edit'] }}';
                    }
                },
                escape: true
            });
            {% endif %}
        });
    </script>

    <style type="text/css">
        .editable-click, a.editable-click, a.editable-click:hover {
            border: 0;
        }

        .profile_bio {
            float: left;
        }

        .profile_avatar {
            width: 300px;
            float: left;
        }

        .profile_avatar img {
            width: 100%;
        }

        .profile_info {
            width: 240px;
            float: left;
            padding: 10px;
        }

        .profile_info .profile_char_name {
            font-family: LithosR;
            font-size: 14px;
            text-align: center;
            margin-bottom: 20px;
        }

        .profile_special {
            margin-bottom: 10px;
            text-align: center;
            width: 100%;
            color: #e7cf9f;
        }

        .profile_special .special_label {
            font-family: LithosR;
            font-size: 14px;
            text-align: left;
            color: #6a604c;
            text-align: center;
        }

        .profile_additions {
            float: left;
            margin-top: 30px;
            width: 100%;
        }

        .additions_menu {
            width: 100%;
            float: left;
            border-bottom: 1px solid;
        }
        .expand_stats {
            cursor: pointer;
        }

        .additions_menu div {
            float: left;
            width: 25%;
            font-family: LithosR;
            font-size: 14px;
            text-align: center;
            color: #e7cf9f;
            cursor: pointer;
        }

        #statistics {
            float: left;
            width: 100%
        }

        #statistics .statistic_label {
            font-family: LithosR;
            font-size: 16px;
            margin-top: 20px;
            text-indent: 30px;
        }

        .statistic_stat_label {
            float: left;
            width: 150px;
        }
    </style>
{% endblock %}