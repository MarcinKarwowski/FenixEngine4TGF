{% extends "../../../templates/admin.volt" %}

{% block pageContent %}
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary btn-lg" onclick="openAjaxModal('/admin/media/edit/0', '#myModal', '#mediaTemplate', function() { })" style="float: right; position: relative; top: -50px;">
        {{ t['media-add'] }}
    </button>
    <div class="box">
        <div class="box-body">
            <table id="dataTableData" class="table table-bordered table-striped" cellspacing="0" width="100%" style="visibility: hidden;">
                <thead>
                <tr>
                    <th>{{ t['articles-title'] }}</th>
                    <th>{{ t['media-file'] }}</th>
                    <th>{{ t['game-panel_edit'] }}</th>
                </tr>
                </thead>

                <tfoot>
                <tr>
                    <th>{{ t['articles-title'] }}</th>
                    <th>{{ t['media-file'] }}</th>
                    <th>{{ t['game-panel_edit'] }}</th>
                </tr>
                </tfoot>
                <tbody>
                <?php
                foreach (Main\Models\Media::find() as $data) {
                ?>
                <tr>
                    <td style="font-weight: bold;">{{ data.title }}</td>
                    <td>
                        {% if data.type == 'IMG' %}
                            <img src="{{ data.url }}" style="width: 30px;" />
                        {% endif %}
                    </td>
                    <td><a onclick="openAjaxModal('/admin/media/edit/{{ data.id }}', '#myModal', '#mediaTemplate', function() { })" class="btn btn-primary btn-sm" type="button">{{ t['edit'] }}</a></td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script id="mediaTemplate" type="text/x-jsrender">
        <div class="art_edit_form">
            <form  action="/admin/media/save[#* if(data.obj.id) { #]/[#>obj.id#][#* } #]" method="post" class="" id="modalForm" name="modalForm" enctype="multipart/form-data">
                    <!-- text input -->
                    <div class="form-group">
                      <label>{{ t['articles-title'] }}</label>
                      <input type="text" value="[#>obj.title#]" name="title" class="form-control input-sm">
                    </div>
                    <div class="form-group">
                      <label>Typ</label>
                      <select class="form-control" name="type">
                        <option value="IMG" [#* if(data.obj.type=="IMG") { #]selected="selected"[#* } #]>Obrazek</option>
                      </select>
                    </div>
                    <div class="form-group">
                        <label>Plik</label>
                        <div style="position: relative;">
                            [#* if(data.obj.url) { #] <img src="[#>obj.url#]" style="width: 50px; display: inline-block;" />[#* } #]
                            <input type="file" name="pic" accept="image/*" style="display: inline-block;">
                        </div>
                    </div>
            </form>
        </div>
    </script>
{% endblock %}

{% block addJS %}
    <script type="text/javascript">
        $.views.settings.allowCode = true;
        $.views.settings.delimiters("[#","#]");

        $(document).ready(function() {
            $('#myModal').on('hidden.bs.modal', function () {
                tinymce.execCommand('mceRemoveEditor',true,"mytextarea");
            });

            $('#dataTableData').DataTable( {
                paginate: true,
                sort: false,
                stateSave: true,
                order: [[ 1, "asc" ]],
                responsive: false,
                iDisplayLength: 100,
                "columnDefs": [ {
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                } ],
                "aoColumnDefs": [
                    { "sType": "html", "aTargets": [ 0 ] }
                ],
                language: {
                    "sProcessing":   "{{ t['datatable-sProcessing'] }}",
                    "sLengthMenu":   "{{ t['datatable-sLengthMenu'] }}",
                    "sZeroRecords":  "{{ t['datatable-sZeroRecords'] }}",
                    "sInfoThousands":  "{{ t['datatable-sInfoThousands'] }}",
                    "sInfo":         "{{ t['datatable-sInfo'] }}",
                    "sInfoEmpty":    "{{ t['datatable-sInfoEmpty'] }}",
                    "sInfoFiltered": "{{ t['datatable-sInfoFiltered'] }}",
                    "sInfoPostFix":  "{{ t['datatable-sInfoPostFix'] }}",
                    "sSearch":       "{{ t['datatable-sSearch'] }}",
                    "sUrl":          "{{ t['datatable-sUrl'] }}",
                    "oPaginate": {
                        "sFirst":    "{{ t['datatable-sFirst'] }}",
                        "sPrevious": "{{ t['datatable-sPrevious'] }}",
                        "sNext":     "{{ t['datatable-sNext'] }}",
                        "sLast":     "{{ t['datatable-sLast'] }}"
                    },
                    "sEmptyTable":     "{{ t['datatable-sEmptyTable'] }}",
                    "sLoadingRecords": "{{ t['datatable-sLoadingRecords'] }}",
                    "oAria": {
                        "sSortAscending":  "{{ t['datatable-sSortAscending'] }}",
                        "sSortDescending": "{{ t['datatable-sSortDescending'] }}"
                    }
                },
                "initComplete": function(settings, json) {
                    $(this).css("visibility", "visible");
                }
            } );
        } );
    </script>
{% endblock %}