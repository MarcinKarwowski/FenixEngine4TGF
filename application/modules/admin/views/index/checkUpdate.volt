{% extends "../../../templates/admin.volt" %}

{% block pageContent %}
    <?php $needupdate = false; ?>
    <table class="table table-hover">
        <thead>
        <tr>
            <th class="active">{{ t['info-name'] }}</th>
            <th class="active">{{ t['update-current_version'] }}</th>
            <th class="active">{{ t['update-next_version'] }}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Fenix Engine ver.</td>
            <td>{{ engineVer }}</td>
            <td>
                {% if updateinfo['engine'] %}
                    <?php
                        echo str_replace('-', '.', $updateinfo['engine']);
                        if ((int)str_replace('-', '', $updateinfo['engine']) > (int)str_replace('.', '', $engineVer)) $needupdate = true;
                    ?>
                {% else %}---{% endif %}
            </td>
        </tr>
        {% for game in games %}
            <tr>
                <td>Game Template <b>{{ game.title }}</b></td>
                <td>{{ game.version }}</td>
                <td>
                    {% if updateinfo['games'][game.folder] %}
                        <?php
                            echo str_replace('-', '.', $updateinfo['games'][$game -> folder]);
                            if ((int)str_replace('-', '', $updateinfo['games'][$game -> folder]) > (int)str_replace('.', '', $game -> version)) $needupdate = true;
                        ?>
                    {% else %}---{% endif %}
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>
    {% if needupdate %}
        <div style="width: 100%; margin-top: 30px; text-aling: center;"><a href="/admin/run-update" class="btn btn-success" style="width: 100%;">Aktualizuj silnik</a></div>
    {% endif %}
    {% if config.game.publicUrl == 'e-fenix.info' %}
        <div style="width: 100%; margin-top: 30px; text-aling: center;"><a href="/prepare-update/08f96b5f30f722489b1325c3144d0c3a50615a55acc0956315437cdaba84f1f2" class="btn btn-danger" style="width: 100%;">Przygotuj aktualizację</a></div>
    {% endif %}
    <style>
        .table td, .table th {
            text-align: center;
        }
    </style>
{% endblock %}