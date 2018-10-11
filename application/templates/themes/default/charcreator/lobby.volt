{% extends "darklayer.volt" %}


{% block content %}
    <h1 style="text-align: left; font-size: 30px; font-family: Gatintas;">{{ config.game.title }} {{ t['charcreator-lobby'] }}</h1>
    <div style="position: relative; top: 0; text-indent: 30px;">{{ t['charcreator-lobby_desc'] }}{% if auth['group']=='Admin' %}{{ t['charcreator-lobby_panel'] }}{% else %}.{% endif %}</div>
    <div id="lobby_content" style="margin-top: 20px;">
        {{ flash.output() }}

        {% for character in userChars %}
            <div class="normalBox">
                <i class="fa fa-pencil"
                   onclick="loadCRData('/charcreator/edit/{{ character.id }}', 'editTemplate', 'lobby_content'); return false;"></i>

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

        .tooltip-inner {
            max-width: 350px;
            width: 350px;
            color: #e7cf9f;
        }

        .elem_info {
            border-bottom: 1px dotted #161616;
            border-right: 1px dotted #161616;
            color: #e7cf9f;
            float: left;
            padding: 5px 10px;
            cursor: pointer;
        }

        .elem_info:hover {
            color: #fff;
        }

        #elem_description {
            float: left;
            margin-bottom: 20px;
            margin-top: 20px;
            text-align: left;
            width: 100%;
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
                    var freepoints = $('#stats_freepoints');
                    freepoints.html(parseInt(freepoints.text()) + 1);
                    $('#creator_nextstep').hide();
                }
            });
            $('#lobby_content').on('click', '.fa-chevron-circle-right', function () {
                var input = $(this).parents('.stat_controls').find('.stat input');
                var freepoints = $('#stats_freepoints');
                if (parseInt(freepoints.text()) - 1 >= 0) {
                    //if (parseInt(input.val()) + 1 <= 1) {
                    input.val(parseInt(input.val()) + 1);
                    freepoints.text(parseInt(freepoints.text()) - 1);
                    if (parseInt(freepoints.text()) == 0) $('#creator_nextstep').show();
                    //}
                }
                else $('#creator_nextstep').show();
            });

            $('#lobby_content').on('click', '.elem_info', function () {
                $(this).find('input').prop("checked", true);
                $('#elem_description').html($("<div/>").html($(this).find('.elem_description').html()).text());
                $('#creator_nextstep').show();
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
                $('#lobby_content').tooltip({selector: '[data-toggle="tooltip"]'});
                if (parseInt($('#freepoints').html()) == 0) $('#creator_nextstep').show();
                $('#creator_nextstep').html('{{ t['charcreator-next_step'] }}');
            }
            else if (data.list) {
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
                [#if list tmpl="#listTemplate"#]
                [#else grid tmpl="#gridTemplate"#]
                [#else stats tmpl="#statsTemplate"#]
                [#else chardata tmpl="#chardataTemplate"#]
                [#/if#]
            </div>
            <div id="creator_nextstep" onclick="ajaxLoad('/charcreator/next/[#:step#]', {'formName': 'form_[#:step#]'}, true, function(data) { if (data.contents.error) { jAlert(data.contents.error); } else { var template = $.templates('#creatorTmpl'); var htmlOutput = template.render(data.contents); $('#lobby_content').html(htmlOutput); CRHelper(data.contents);} }); return false;">{{ t['charcreator-next_step'] }}</div>
        </div>

    </script>
    <script id="listTemplate" type="text/x-jsrender">
                    <div id="skils_desc">[#:text#]</div>
                    <form id="form_[#:step#]" name="form_[#:step#]" method="POST">
            [#props creations#]
                    <div class="elem_info" id="elem_[#>prop.id#]">
                        <input type="radio" name="elem" value="[#>prop.id#]" style="visibility: hidden;" /> [#>prop.name#]
                        <div class="elem_description" style="display: none;">[#>prop.text#]</div>
                    </div>
            [#/props#]
            <div id="elem_description"></div>
            </form>

    </script>
    <script id="statsTemplate" type="text/x-jsrender">
    <div id="skils_desc">
        [#:text#]<br />
        <p>Masz do wykorzystania <span id="stats_freepoints">[#:freepoints#]</span> punktów</p>
    </div>
    <div style="display: block; margin-top: 20px; margin-bottom: 30px;">
        <div id="stats_content" style="position: relative; overflow: visible; float: none; margin: 0; padding: 0; width: 100%;">
            <form id="form_[#:step#]" name="form_[#:step#]" method="POST">
            [#props creations#]
                    <div class="stat_info">
                        <div class="stat_label" data-toggle="tooltip" data-placement="bottom" title="[#:prop.text#]">[#>prop.name#]</div>
                        <div class="stat_controls">
                            <div><i class="fa fa-chevron-circle-left"></i></div>
                            <div class="stat"><input name="stat_[#>prop.id#]" value="[#:prop.params.basepoints#]" /></div>
                            <div><i class="fa fa-chevron-circle-right"></i></div>
                        </div>
                    </div>
            [#/props#]
            </form>
        </div>
    </div>

    </script>
    <script id="chardataTemplate" type="text/x-jsrender">
    <div id="skils_desc">{{ t['charcreator-chardata_desc'] }}</div>
    <div style="display: block; margin-top: 20px; margin-bottom: 30px;">
        <div id="stats_content" style="position: relative; overflow: auto; max-height: 300px; float: none; margin: 0; padding: 0; width: 100%;">
        <form id="form_[#:step#]" name="form_[#:step#]" method="POST">
            <div class="form-group">
                <label for="nameLable">{{ t['charcreator-char_name'] }}</label>
                <input type="text" class="form-control" id="charName" name="charName">
            </div>
        </form>
        </div>
    </div>

    </script>
    <script id="editTemplate" type="text/x-jsrender">
        <div class="creator_contener" style="min-height: 150px;">
            <div id="creator_backtolobby" onclick="document.location='/charcreator/lobby';">{{ t['charcreator-back_to_lobby'] }}</div>
            <div id="creator_describe" style="width: 100%;">
            <form id="form_[#:charid#]" name="form_[#:charid#]" method="POST">
                <div class="form-group">
                    <label for="nameLable">{{ t['charcreator-char_name'] }}</label>
                    <input type="text" value="[#:charname#]" class="form-control" id="charName" name="charName">
                </div>
            </form>
            </div>
            <div id="creator_delete" onclick="window.location.href = '/charcreator/delete/[#:charid#]'">Usuń postać</div>
            <div id="creator_nextstep" style="display: block;" onclick="ajaxLoad('/charcreator/edit/[#:charid#]', {'formName': 'form_[#:charid#]'}, true, function(data) { if (data.contents.error) { jAlert(data.contents.error); } else { window.location.href = &quot;/charcreator/lobby&quot;; } }); return false;">Zapisz</div>
        </div>
    </script>
{% endblock %}