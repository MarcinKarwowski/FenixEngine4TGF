{% extends "admin.volt" %}

{% block pageContent %}
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary btn-lg" onclick="openAjaxModal('/admin/permissions/edit/0', '#myModal', '#permTemplate', function() { })" style="float: right; position: relative; top: -50px;">
        {{ t['perm-add'] }}
    </button>
    <div class="box">
        <div class="box-body">
            <table id="dataTableData" class="table table-bordered table-striped" cellspacing="0" width="100%" style="visibility: hidden;">
                <thead>
                <tr>
                    <th>{{ t['articles-title'] }}</th>
                    <th>{{ t['articles-data'] }}</th>
                    <th>{{ t['game-panel_edit'] }}</th>
                    <th>{{ t['delete'] }}</th>
                </tr>
                </thead>

                <tfoot>
                <tr>
                    <th>{{ t['articles-title'] }}</th>
                    <th>{{ t['articles-data'] }}</th>
                    <th>{{ t['game-panel_edit'] }}</th>
                    <th>{{ t['delete'] }}</th>
                </tr>
                </tfoot>
                <tbody>
                {% for data in permissions %}
                    <tr>
                        <td style="font-weight: bold;">{{ data.name }}</td>
                        <td>{{ data.email }}</td>
                        <td><a onclick="openAjaxModal('/admin/permissions/edit/{{ data.uid }}', '#myModal', '#permTemplate', function() { })" class="btn btn-primary btn-sm" type="button">{{ t['edit'] }}</a></td>
                        <td><a href="/admin/permissions/delete/{{ data.uid }}" class="btn btn-danger btn-sm" type="button">{{ t['delete'] }}</a></td>
                    </tr>
                {% endfor %}
                </tbody>
            </table>
        </div>
    </div>

    <script id="permTemplate" type="text/x-jsrender">
        <div class="art_edit_form">
            <form  action="/admin/permissions/save[#* if(typeof data.obj.id != 'undefined') { #]/[#>obj.id#][#* } #]" method="post" class="" id="modalForm" name="modalForm">
                <div class="form-group">
                    <label>{{ t['perm-user_id'] }}</label>
                    <div style="position: relative;">
                        <input type="text" name="users_id" value="[#* if(typeof data.obj.id != 'undefined') { #][#>obj.id#][#* } #]"  class="form-control input-sm" />
                    </div>
                </div>
                {% for index, value in adminMenu %}
                    {% if value is iterable %}
                        {% for label, menu in value %}
                            <div class="form-group">
                                <input type="checkbox" name="permissions[]" value="{{ label|e }}" [#* if(typeof data.obj.perms['{{ label|e }}'] != 'undefined') { #]checked="checked"[#* } #]> <label>{{ t[label|e] }}</label>
                            </div>
                        {% endfor %}
                    {% endif %}
                {% endfor %}
                <h2>{{ t['perm-cpermissions'] }}</h2>
                {% for index, value in cpermissions %}
                            <div class="form-group">
                                <input type="checkbox" name="permissions[]" value="{{ index|e }}" [#* if(typeof data.obj.perms['{{ index|e }}'] != 'undefined') { #]checked="checked"[#* } #]> <label>{{ t[index|e] }}</label>
                            </div>
                {% endfor %}
            </form>

        </div>
    </script>
{% endblock %}

{% block addJS %}
    <script type="text/javascript">
        $.views.settings.allowCode = true;
        $.views.settings.delimiters("[#","#]");

        $(document).ready(function() {
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
