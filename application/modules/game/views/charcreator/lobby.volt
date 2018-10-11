{% extends "darklayer.volt" %}


{% block content %}
    <h1 style="text-align: left; font-size: 30px; font-family: Gatintas;">{{ config.game.title }} {{ t['charcreator-lobby'] }}</h1>
    <div style="position: relative; top: -10px; text-indent: 30px;">{{ t['charcreator-lobby_desc'] }}{% if auth['group']=='Admin' %}{{ t['charcreator-lobby_panel'] }}{% else %}.{% endif %}</div>
    <div id="lobby_content" style="margin-top: 20px;">
        {{ flash.output() }}

        {% for character in userChars %}
            <div class="normalBox">
                <div class="oneChar" onclick="window.location.href = '/charcreator/chose/{{ character.id }}';">
                    <i class="fa fa-user"></i>

                    <div style="height: 40px; text-align: center; line-height: 40px; font-size: 20px; color: rgb(52, 209, 18);">{{ character.name }}</div>
                </div>
            </div>
        {% endfor %}
        {% if userChars.count() < config.game.params.charactersAmount %}
            <div class="normalBox"
                 onclick="loadCRData('/charcreator/begin/1', 'creatorTmpl', 'lobby_content'); return false;">
                <div class="newChar">
                    <i class="fa fa-plus-circle"></i>
                </div>
            </div>
        {% endif %}
    </div>

    <style type="text/css">
        .ramka_content {
            position: static;
        }

        .skilcheck {
            cursor: pointer;
        }
    </style>

    <script type="text/javascript">
        $.views.settings.delimiters("[#", "#]");

        $(function () {
            $('#lobby_content').tooltip({selector: '[data-toggle="tooltip"]'});

            $('#lobby_content').on('click', '.fa-chevron-circle-left', function () {
                var input = $(this).parents('.stat_controls').find('.stat input');
                if (parseInt(input.val()) > 1) {
                    input.val(parseInt(input.val()) - 1);
                    var freepoints = $('#freepoints');
                    freepoints.html(parseInt(freepoints.html()) + 1);
                    $('#creator_nextstep').hide();
                }
            });
            $('#lobby_content').on('click', '.fa-chevron-circle-right', function () {
                var input = $(this).parents('.stat_controls').find('.stat input');
                var freepoints = $('#freepoints');
                if (parseInt(freepoints.html()) - 1 >= 0) {
                    if (parseInt(input.val()) + 1 <= 1) {
                        input.val(parseInt(input.val()) + 1);
                        freepoints.html(parseInt(freepoints.html()) - 1);
                        if (parseInt(freepoints.html()) == 0) $('#creator_nextstep').show();
                    }
                }
                else $('#creator_nextstep').show();
            });

            $('#lobby_content').on('click', '.skilcheck', function () {
                var skil = $(this);
                var ability = skil.attr('ability');
                var skilID = skil.attr('skilid');
                var freepoints = parseInt($('#ability_' + ability).html());


                if (document.getElementById("skil_" + skilID).checked) {
                    $("#skil_" + skilID).attr('checked', false);
                    $('#ability_' + ability).html(freepoints + 1);
                    skil.removeClass('marked');
                }
                else {
                    if (freepoints > 0) {
                        $("#skil_" + skilID).attr('checked', true);
                        $('#ability_' + ability).html(freepoints - 1);
                        skil.addClass('marked');
                    }
                    else {
                        jAlert('{{ t['charcreator-wrong_stat_limit'] }}');
                    }
                }

            });
        });
        function loadCRData(url, templateName, destinationDiv) {
            $.ajaxq('standard', {
                url: url,
                context: document.body
            }).done(function (data) {
                if (data.contents.error) {
                    jAlert(data.contents.error);
                }
                else {
                    var template = $.templates("#" + templateName);
                    var htmlOutput = template.render(data.contents);
                    $("#" + destinationDiv).html(htmlOutput);

                    CRHelper(data.contents);
                }
            });
        }
        function CRHelper(data) {
            if (data.stats) {
                if (parseInt($('#freepoints').html()) == 0) $('#creator_nextstep').show();
                $('#creator_nextstep').html('{{ t['charcreator-next_step'] }}');
            }
            else if (data.skils) {
                $('.scrollbar-dynamic').scrollbar();
                $('#creator_nextstep').html('{{ t['charcreator-next_step'] }}').show();
            }
            else if (data.chardata) {
                $('#creator_nextstep').html('{{ t['charcreator-create'] }}').show();
            }
            else if (data.endCr) {
                window.location.href = "/charcreator/lobby";
            }
        }
    </script>


    <script id="creatorTmpl" type="text/x-jsrender">
        <div class="creator_contener">
            <div id="creator_backtolobby" onclick="document.location='/charcreator/lobby';">{{ t['charcreator-back_to_lobby'] }}</div>
            <div id="creator_menu">
                <ul>
                    [#props labels#]
                        <li class="[#>prop.active#]" onclick="loadCRData('/charcreator/begin/[#>prop.order#]', 'creatorTmpl', 'lobby_content'); return false;">
                            [#>prop.name#]
                        </li>
                    [#/props#]
                </ul>
            </div>
            <div id="creator_describe">
                [#if stats tmpl="#statTemplate"#]
                [#else skils tmpl="#skilsTemplate"#]
                [#else chardata tmpl="#chardataTemplate"#]
                [#/if#]
            </div>
            <div id="creator_nextstep" onclick="ajaxLoad('/charcreator/next/[#:step#]', {'formName': 'form_[#:step#]'}, true, function(data) { if (data.error) { jAlert(data.error); } else { var template = $.templates('#creatorTmpl'); var htmlOutput = template.render(data); $('#lobby_content').html(htmlOutput); CRHelper(data);} }); return false;">{{ t['charcreator-next_step'] }}</div>
        </div>

    </script>
    <script id="statTemplate" type="text/x-jsrender">
        <div id="stats_content">
                    <div id="stats_desc">{{ t['charcreator-stats_desc'] }}</div>
                    <div class="stat_info">
                        <div class="stat_label" style="width: 100%; text-align: center;">{{ t['charcreator-free_points'] }} <div id="freepoints" style="display: inline; margin: 20px;">[#:freepoints#]</div></div>
                    </div>
                    <form id="form_[#:step#]" name="form_[#:step#]" method="POST">
            [#props statsList#]
                    <div class="stat_info">
                        <div class="stat_label" data-toggle="tooltip" data-placement="left" title="[#>prop.desc#]">[#>prop.label#]</div>
                        <div class="stat_controls">
                            <div><i class="fa fa-chevron-circle-left"></i></div>
                            <div class="stat"><input name="stat_[#>key#]" value="[#>prop.value#]" /></div>
                            <div><i class="fa fa-chevron-circle-right"></i></div>
                        </div>
                    </div>
            [#/props#]
            </form>
        </div>

    </script>
    <script id="chardataTemplate" type="text/x-jsrender">
    <div id="skils_desc">{{ t['charcreator-chardata_desc'] }}</div>
    <div class="scrolbar_content" style="display: block; margin-top: 50px; margin-bottom: 30px;">
        <div id="stats_content"  class="scrollbar-dynamic" style="position: relative; overflow: auto; max-height: 300px; float: none; margin: 0; padding: 0; width: 100%;">
        <form id="form_[#:step#]" name="form_[#:step#]" method="POST">
            <div class="form-group">
                <label for="nameLable">{{ t['charcreator-char_name'] }}</label>
                <input type="text" class="form-control" id="charName" name="charName">
            </div>
            <div class="form-group">
                <label for="nameLable">{{ t['charcreator-char_gender'] }}</label>
                <select class="form-control" id="charGender" name="charGender"><option value="F">{{ t['char-gender_F'] }}
        </option><option value="M">{{ t['char-gender_M'] }}</option></select>
            </div>
        </form>
        </div>
    </div>

    </script>
{% endblock %}