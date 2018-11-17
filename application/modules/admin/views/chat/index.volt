{% extends "admin.volt" %}

{% block pageContent %}
    <div class="box">
        <div class="box-body">
            <table id="dataTableData" class="table table-bordered table-striped" cellspacing="0" width="100%" style="visibility: hidden;">
                <thead>
                <tr>
                    <th>{{ t['chat-id'] }}</th>
                    <th>{{ t['chat-title'] }}</th>
                    <th>{{ t['chat-owner'] }}</th>
                    <th>{{ t['delete'] }}</th>
                </tr>
                </thead>

                <tfoot>
                <tr>
                    <th>{{ t['chat-id'] }}</th>
                    <th>{{ t['chat-title'] }}</th>
                    <th>{{ t['chat-owner'] }}</th>
                    <th>{{ t['delete'] }}</th>
                </tr>
                </tfoot>
                <tbody>
                <?php
                foreach (Main\Models\Chats::find(['order' => 'id ASC']) as $data) {
                ?>
                <tr>
                    <th>{{ data.id }}</th>
                    <td style="font-weight: bold;">{{ data.title }}</td>
                    <td>{{ data.owner.name }}</td>
                    <td><a href="/admin/chat/delete/{{ data.id }}" class="btn btn-danger btn-sm" type="button">{{ t['delete'] }}</a></td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
{% endblock %}

{% block addJS %}
    <script type="text/javascript">
        $(document).ready(function(){
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
