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
            onclick="openAjaxModal('/admin/game/panel/items/0', '#myModal', '#categoryTemplate', function() { tinymce.init(tinyMCConfig); })"
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
                $categories = Game\Models\ItemsCat::find(['order' => 'id ASC']);
                foreach ($categories as $data) {
                ?>
                <tr>
                    <td style="font-weight: bold;">{{ data.name }}</td>
                    <td class="text-center">
                        <a class="btn btn-primary btn-sm"
                           onclick="openAjaxModal('/admin/game/panel/item/{{ data.id }}/0', '#myModal', '#itemTemplate', function() { tinymce.init(tinyMCConfig); })">
                            {{ t['panel-charcreator_dodaj'] }}
                        </a>
                    </td>
                    <td class="text-center">
                        <a onclick="openAjaxModal('/admin/game/panel/items/{{ data.id }}', '#myModal', '#categoryTemplate', function() { tinymce.init(tinyMCConfig); })"
                           class="btn btn-success btn-sm" type="button">{{ t['edit'] }}</a></td>
                    <td class="text-center"><a href="/admin/game/panel/delitems/{{ data.id }}" class="btn btn-danger btn-sm"
                                               type="button">{{ t['delete'] }}</a></td>
                </tr>
                {% for option in data.items %}
                    <tr>
                        <td style="font-weight: bold;">---- {{ option.name }}</td>
                        <td></td>
                        <td class="text-center">
                            <a onclick="openAjaxModal('/admin/game/panel/item/{{ data.id }}/{{ option.id }}', '#myModal', '#itemTemplate', function() { tinymce.init(tinyMCConfig); })"
                               class="btn btn-success btn-sm" type="button">{{ t['edit'] }}</a></td>
                        <td class="text-center"><a href="/admin/game/panel/delitem/{{ option.id }}" class="btn btn-danger btn-sm"
                                                   type="button">{{ t['delete'] }}</a></td>
                    </tr>
                {% endfor %}
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script id="categoryTemplate" type="text/x-jsrender">
        <div class="art_edit_form">
            <form  action="/admin/game/panel/items/[#* if(data.obj.id) { #]/[#>obj.id#][#* } #]" method="post" class="" id="modalForm" name="modalForm">
                    <!-- text input -->
                    <div class="form-group">
                      <label>{{ t['items-name'] }}</label>
                      <input type="text" value="[#>obj.name#]" name="title" class="form-control input-sm">
                    </div>
                    <div class="form-group">
                        <label>{{ t['description'] }}</label>
                        <div style="position: relative;">
                            <textarea id="mytextarea" name="text">[#* if(data.obj.text) { #][#>obj.text#][#* } #] </textarea>
                        </div>
                    </div>
            </form>
        </div>
    </script>

    <script id="itemTemplate" type="text/x-jsrender">
        <div class="art_edit_form">
            <form  action="/admin/game/panel/item/[#>category_id#][#* if(data.obj.id) { #]/[#>obj.id#][#* } #]" method="post" class="" id="modalForm" name="modalForm">
                    <!-- text input -->
                    <div class="form-group">
                      <label>{{ t['item-name'] }}</label>
                      <input type="text" value="[#>obj.name#]" name="title" class="form-control input-sm">
                    </div>
                    <div class="form-group">
                      <label>{{ t['item-cost'] }}</label>
                      <input type="text" value="[#>obj.price#]" name="price" class="form-control input-sm">
                    </div>
                    <div class="form-group">
                        <label>{{ t['description'] }}</label>
                        <div style="position: relative;">
                            <textarea id="mytextarea" name="text">[#* if(data.obj.text) { #][#>obj.text#][#* } #] </textarea>
                        </div>
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
