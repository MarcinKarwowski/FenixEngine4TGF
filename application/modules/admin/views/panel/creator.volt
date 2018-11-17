{% extends "admin.volt" %}

{% block pageContent %}
    <script>
        var tinyMCConfig = {
            selector: '#mytextarea',
            mode: 'none',
            theme: 'modern',
            width: 550,
            height: 500,
            menubar: false,
            language: 'pl',
            plugins: [
                'advlist autolink link image lists charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking spellchecker',
                'table contextmenu directionality emoticons paste textcolor responsivefilemanager colorpicker codemirror'],
            toolbar1: 'pastetext searchreplace code | undo redo | visualblocks preview removeformat charmap | hr | link unlink anchor ',
            toolbar2: '| bold italic underline strikethrough | subscript superscript | alignleft aligncenter alignright alignjustify ',
            toolbar3: '| image responsivefilemanager | bullist numlist outdent indent | forecolor backcolor ',
            toolbar4: '| fontsizeselect styleselect | table ',
            codemirror: {
                indentOnInit: true, // Whether or not to indent code on init.
                path: 'codemirror-5', // Path to CodeMirror distribution
                config: {           // CodeMirror config object
                    mode: 'htmlmixed',
                    lineNumbers: true
                },
                jsFiles: ['mode/css/css.js', 'mode/xml/xml.js', 'mode/htmlmixed/htmlmixed.js', 'mode/htmlembedded/htmlembedded.js', 'mode/javascript/javascript.js', 'mode/xml/xml.js'],
                cssFiles: ['theme/eclipse.css', 'theme/elegant.css', 'theme/neat.css', 'theme/colorforth.css']
            },
            image_advtab: true,
            relative_urls: false,

            external_filemanager_path: '/assets/scripts/filemanager/',
            filemanager_title: 'Manager Plików',
            filemanager_sort_by: 'date',
            filemanager_descending: 'ascending',
            external_plugins: {'filemanager': 'plugins/responsivefilemanager/plugin.min.js'}
        };
    </script>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary btn-lg"
            onclick="openAjaxModal('/admin/game/panel/creator/0', '#myModal', '#creatorTemplate', function() { tinymce.init(tinyMCConfig); })"
            style="float: right; position: relative; top: -50px;">
        {{ t['panel-charcreator_addoption'] }}
    </button>
    <div class="box">
        <div class="box-body">
            <table id="dataTableData" class="table table-bordered table-striped" cellspacing="0" width="100%"
                   style="visibility: hidden;">
                <thead>
                <tr>
                    <th>{{ t['panel-charcreator_rowtitle'] }}</th>
                    <th class="text-center">{{ t['panel-charcreator_dodaj'] }}</th>
                    <th class="text-center">{{ t['game-panel_edit'] }}</th>
                    <th class="text-center">{{ t['delete'] }}</th>
                </tr>
                </thead>

                <tfoot>
                <tr>
                    <th>{{ t['panel-charcreator_rowtitle'] }}</th>
                    <th class="text-center">{{ t['panel-charcreator_dodaj'] }}</th>
                    <th class="text-center">{{ t['game-panel_edit'] }}</th>
                    <th class="text-center">{{ t['delete'] }}</th>
                </tr>
                </tfoot>
                <tbody>
                <?php
                $creators = Game\Models\Creator::find(['order' => 'orderid ASC']);
                foreach ($creators as $data) {
                ?>
                <tr>
                    <td style="font-weight: bold;">{{ data.name }}</td>
                    <td class="text-center">
                        <a class="btn btn-primary btn-sm"
                           onclick="openAjaxModal('/admin/game/panel/creations/{{ data.id }}/0', '#myModal', '#creationsTemplate', function() { tinymce.init(tinyMCConfig); })">
                            {{ t['panel-charcreator_dodaj'] }}
                        </a>
                    </td>
                    <td class="text-center">
                        <a onclick="openAjaxModal('/admin/game/panel/creator/{{ data.id }}', '#myModal', '#creatorTemplate', function() { tinymce.init(tinyMCConfig); })"
                           class="btn btn-success btn-sm" type="button">{{ t['edit'] }}</a></td>
                    <td class="text-center"><a href="/admin/game/panel/delcreator/{{ data.id }}" class="btn btn-danger btn-sm"
                           type="button">{{ t['delete'] }}</a></td>
                </tr>
                {% for option in data.options %}
                    <tr>
                        <td style="font-weight: bold;">---- {{ option.name }}</td>
                        <td></td>
                        <td class="text-center">
                            <a onclick="openAjaxModal('/admin/game/panel/creations/{{ data.id }}/{{ option.id }}', '#myModal', '#creationsTemplate', function() { tinymce.init(tinyMCConfig); })"
                               class="btn btn-success btn-sm" type="button">{{ t['edit'] }}</a></td>
                        <td class="text-center"><a href="/admin/game/panel/delcreations/{{ option.id }}" class="btn btn-danger btn-sm"
                               type="button">{{ t['delete'] }}</a></td>
                    </tr>
                {% endfor %}
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script id="creatorTemplate" type="text/x-jsrender">
        <div class="art_edit_form">
            <form  action="/admin/game/panel/creator/[#* if(data.obj.id) { #]/[#>obj.id#][#* } #]" method="post" class="" id="modalForm" name="modalForm">
                    <!-- text input -->
                    <div class="form-group">
                      <label>{{ t['articles-title'] }}</label>
                      <input type="text" value="[#>obj.name#]" name="title" class="form-control input-sm">
                    </div>
                    <div class="form-group">
                      <label>Widoczne w profilu</label>
                      <select class="form-control" name="inprofile">
                        <option value="1" [#* if(data.obj.showinprofile==1) { #]selected="selected"[#* } #]>Tak</option>
                        <option value="0" [#* if(data.obj.showinprofile==0) { #]selected="selected"[#* } #]>Nie</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Widoczne w kreatorze postaci</label>
                      <select class="form-control" name="showincreator">
                        <option value="1" [#* if(data.obj.showincreator==1) { #]selected="selected"[#* } #]>Tak</option>
                        <option value="0" [#* if(data.obj.showincreator==0) { #]selected="selected"[#* } #]>Nie</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Kolejność</label>
                      <select class="form-control" name="orderid">
                      {% for cr in creators %}
                        <option value="{{ cr.orderid }}" [#* if(data.obj.orderid=={{ cr.orderid }}) { #]selected="selected"[#* } #]>{{ cr.name }}</option>
                      {% endfor %}
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Prezentacja</label>
                      <select class="form-control" name="type" id="change_view">
                        <option value="list" [#* if(data.obj.type=='list') { #]selected="selected"[#* } #]>Lista z opisami</option>
                        <option value="grid" [#* if(data.obj.type=='grid') { #]selected="selected"[#* } #]>Grid lista</option>
                        <option value="stats" [#* if(data.obj.type=='stats') { #]selected="selected"[#* } #]>Lista wyboru cech</option>
                      </select>
                    </div>
                    <div id="show_view_params">
                    [#* if(data.obj.type=='stats') { #]
                        <div class="form-group"><label>{{ t['panel-charcreator_freepoints'] }}</label><input type="text" value="[#* if(data.obj.params && data.obj.params['freepoints']) { #][#>obj.params['freepoints']#][#* } #]" name="freepoints" class="form-control input-sm"></div>
                        <div class="form-group"><label>{{ t['panel-charcreator_basepoints'] }}</label><input type="text" value="[#* if(data.obj.params && data.obj.params['basepoints']) { #][#>obj.params['basepoints']#][#* } #]" name="basepoints" class="form-control input-sm"></div>
                        <div class="form-group"><label>{{ t['panel-charcreator_nextlevelstats'] }}</label><input type="text" value="[#* if(data.obj.params && data.obj.params['pc']) { #][#>obj.params['pc']#][#* } #]" name="pc" class="form-control input-sm"></div>
                    [#* } #]
                    </div>
                    <div class="form-group">
                        <label>Opis</label>
                        <div style="position: relative;">
                            <textarea id="mytextarea" name="text">[#* if(data.obj.text) { #][#>obj.text#][#* } #] </textarea>
                        </div>
                    </div>
            </form>

        </div>


    </script>

    <script id="creationsTemplate" type="text/x-jsrender">
        <div class="art_edit_form">
            <form  action="/admin/game/panel/creations/[#>category_id#][#* if(data.obj.id) { #]/[#>obj.id#][#* } #]" method="post" class="" id="modalForm" name="modalForm">
                    <!-- text input -->
                    <div class="form-group">
                      <label>{{ t['articles-title'] }}</label>
                      <input type="text" value="[#>obj.name#]" name="title" class="form-control input-sm">
                    </div>
                    <div class="form-group">
                      <label>Podepnij artykuł z wiki</label>
                      <select class="form-control" name="wiki_id">
                      <option value="0" [#* if(data.obj.wiki_id==0) { #]selected="selected"[#* } #]>Bez połączenia z wiki</option>
                      <?php
                        $allarts = Main\Models\Wikipedia::find(['order'=>'orderid'])-> toArray();
                        $treeClass = new App\Facets\TreeView($allarts);
                        foreach ($treeClass -> retArr as $data) {
                        ?>
                        <option value="{{ data['id'] }}" [#* if(data.obj.wiki_id=={{ data['id'] }}) { #]selected="selected"[#* } #]>{{ data['deep'] }} {{ data['title'] }}</option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="form-group">
                        <label>Lub dodaj opis</label>
                        <div style="position: relative;">
                            <textarea id="mytextarea" name="text">[#* if(data.obj.text) { #][#>obj.text#][#* } #] </textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Dodaj wymagania/wartości podstawowe dla tego elementu</label>
                        <div style="position: relative;">
                            <select class="form-control" name="link_page" id="link_page" style="width: 40%; float: left;">
                            <?php
                                foreach (Game\Models\Creations::find(['order' => 'name ASC']) as $page) {
                            ?>
                                <option [#* if(data.obj.category_id=={{ page.category_id }}) { #]disabled="disabled"[#* } #] value="{{ page.id }}">{{ page.name }}</option>
                            <?php } ?>
                            </select>

                            <input type="text" value="1" name="link_value" id="link_value" placeholder="Wartość" class="form-control input-sm" style="width: 40%; margin-left: 15px; float: left;">

                            <button type="button" class="btn btn-primary btn-sm" style="margin: 0 0 0 15px;" id="send_link">{{ t['send'] }}</button>
                        </div>
                        <div id="links_need" style="margin-top: 10px; border-top: 1px solid;"></div>
                        [#* $(document).ajaxComplete(function () { if(data.links) { $.each(data.links, function(index, value) { console.log(value);  $('#links_need').append('<div id="link_elem_'+value.link_page_id+'">'+value.name+' (Poziom: '+value.value+') <input type="hidden" value="'+value.link_page_id+'" name="links[]"></div>'); }); } }); #]
                    </div>
            </form>

        </div>


    </script>
{% endblock %}

{% block addJS %}
    <script type="text/javascript">
        $.views.settings.allowCode = true;
        $.views.settings.delimiters("[#", "#]");

        $(document).ready(function () {
            $(document).on('change', '#change_view', function(e) {
                e.preventDefault();
                var view = $(this);
                if (view.val() == 'stats')
                {
                    $('#show_view_params').html('<div class="form-group"><label>{{ t['panel-charcreator_freepoints'] }}</label><input type="text" value="1" name="freepoints" class="form-control input-sm"></div><div class="form-group"><label>{{ t['panel-charcreator_basepoints'] }}</label><input type="text" value="1" name="basepoints" class="form-control input-sm"></div>');
                }
                else $('#show_view_params').html('');

            });
            $(document).on('click', '#send_link', function(e) {
                e.preventDefault();
                var link = $('#link_page option:selected');
                var value = $('#link_value').val();
                if ($('#link_elem_'+link.val()).length == 0)
                {
                    $('#links_need').append('<div id="link_elem_'+link.val()+'">'+link.text()+' (Poziom: '+value+') <input type="hidden" value="'+link.val()+'-'+value+'" name="links[]"></div>');
                }
            });
            $(document).on('click', '#links_need div', function(e) {
                e.preventDefault();
                $(this).remove();
            });
            $(document).on('focusin', function (e) {
                if ($(e.target).closest(".mce-window").length) {
                    e.stopImmediatePropagation();
                }
            });
            $('#myModal').on('shown.bs.modal', function () {
                tinyMCE.execCommand('mceAddControl', false, 'mytextarea');
            });
            $('#myModal').on('hidden.bs.modal', function () {
                tinymce.execCommand('mceRemoveEditor', true, "mytextarea");
            });

            $('#dataTableData').DataTable({
                paginate: true,
                sort: false,
                stateSave: true,
                order: [[1, "asc"]],
                responsive: false,
                iDisplayLength: 100,
                "columnDefs": [{
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                }],
                "aoColumnDefs": [
                    {"sType": "html", "aTargets": [0]}
                ],
                language: {
                    "sProcessing": "{{ t['datatable-sProcessing'] }}",
                    "sLengthMenu": "{{ t['datatable-sLengthMenu'] }}",
                    "sZeroRecords": "{{ t['datatable-sZeroRecords'] }}",
                    "sInfoThousands": "{{ t['datatable-sInfoThousands'] }}",
                    "sInfo": "{{ t['datatable-sInfo'] }}",
                    "sInfoEmpty": "{{ t['datatable-sInfoEmpty'] }}",
                    "sInfoFiltered": "{{ t['datatable-sInfoFiltered'] }}",
                    "sInfoPostFix": "{{ t['datatable-sInfoPostFix'] }}",
                    "sSearch": "{{ t['datatable-sSearch'] }}",
                    "sUrl": "{{ t['datatable-sUrl'] }}",
                    "oPaginate": {
                        "sFirst": "{{ t['datatable-sFirst'] }}",
                        "sPrevious": "{{ t['datatable-sPrevious'] }}",
                        "sNext": "{{ t['datatable-sNext'] }}",
                        "sLast": "{{ t['datatable-sLast'] }}"
                    },
                    "sEmptyTable": "{{ t['datatable-sEmptyTable'] }}",
                    "sLoadingRecords": "{{ t['datatable-sLoadingRecords'] }}",
                    "oAria": {
                        "sSortAscending": "{{ t['datatable-sSortAscending'] }}",
                        "sSortDescending": "{{ t['datatable-sSortDescending'] }}"
                    }
                },
                "initComplete": function (settings, json) {
                    $(this).css("visibility", "visible");
                }
            });
        });
    </script>
{% endblock %}
