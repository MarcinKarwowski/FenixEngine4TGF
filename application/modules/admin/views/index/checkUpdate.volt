{% extends "../../../templates/admin.volt" %}

{% block pageContent %}
    <p>{{ t['update-info'] }}</p>
    <p>{{ t['update-current_version'] }} {{ config.game.engineVer }}</p>
    <p>{{ t['update-next_version'] }} {{ new_version }}</p>
    {% if showUpdate %}
        <div style="width: 100%; margin-top: 30px; text-aling: center;">
            <a href="/admin/run-update" class="btn btn-success" style="width: 100%;">Aktualizuj silnik</a>
        </div>
        {% else %}
<div style="width: 100%; margin-top: 30px; text-aling: center;">
    {{ t['update-no_need'] }}
</div>
    {% endif %}
    <br /><br />
    <h3>Changelog</h3>
    {{ changelog }}
{% endblock %}
