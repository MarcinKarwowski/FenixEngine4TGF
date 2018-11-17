{% extends "admin.volt" %}

{% block pageContent %}
    <table class="table table-hover">
        <thead>
        <tr>
            <th class="active">{{ t['info-name'] }}</th>
            <th class="active">{{ t['info-value'] }}</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td>PhalconPHP ver.</td>
                <td><?php  echo Phalcon\Version::get(); ?></td>
            </tr>
            <?php if (isset($stats['Core'])) { ?>
            <tr>
                <td>PHP ver.</td>
                <td><?php  echo $stats['Core']['PHP Version']; ?></td>
            </tr>
            <tr>
                <td>PHP Memory limit</td>
                <td><?php  echo $stats['Core']['memory_limit'][0]; ?></td>
            </tr>
            <?php } ?>
            <?php if (isset($stats['apache2handler'])) { ?>
            <tr>
                <td>Apache ver.</td>
                <td><?php  echo $stats['apache2handler']['Apache Version']; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <h4>{{ t['info-files'] }}</h4>
    <table class="table table-hover">
        <thead>
        <tr>
            <th class="active">{{ t['info-name'] }}</th>
            <th class="active">{{ t['info-value'] }}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Cache</td>
            <td><?php
            if (substr(sprintf('%o', fileperms($cache)), -4) == '0777') echo '<span style="color: green;">'.$t['info-writable'].'</span>';
            else echo '<span style="color: red;">'.$t['info-unwritable'].'</span>';
            ?></td>
        </tr>
        <tr>
            <td>Metadane</td>
            <td><?php
            if (substr(sprintf('%o', fileperms($cache.'metadata')), -4) == '0777') echo '<span style="color: green;">'.$t['info-writable'].'</span>';
                else echo '<span style="color: red;">'.$t['info-unwritable'].'</span>';
                ?></td>
        </tr>
        <tr>
            <td>Logi</td>
            <td><?php
            if (substr(sprintf('%o', fileperms($logs)), -4) == '0777') echo '<span style="color: green;">'.$t['info-writable'].'</span>';
                else echo '<span style="color: red;">'.$t['info-unwritable'].'</span>';
                ?></td>
        </tr>
        <tr>
            <td>Grafiki</td>
            <td><?php
            if (substr(sprintf('%o', fileperms($avatars)), -4) == '0777') echo '<span style="color: green;">'.$t['info-writable'].'</span>';
                else echo '<span style="color: red;">'.$t['info-unwritable'].'</span>';
                ?></td>
        </tr>
        </tbody>
    </table>
    <h4>{{ t['info-system_control'] }}</h4>
    <table class="table table-hover">
        <thead>
        <tr>
            <th class="active">{{ t['info-module'] }}</th>
            <th class="active">{{ t['info-value'] }}</th>
        </tr>
        </thead>
        <tbody>

        <tr>
            <td>CURL</td>
            <td><?php if (isset($stats['curl'])) { ?><span style="color: green;">{{ t['info-plug'] }}</span><?php } else { ?><span style="color: red;">{{ t['info-unplug'] }}</span><?php } ?></td>
        </tr>
        <tr>
            <td>File Info</td>
            <td><?php if (isset($stats['fileinfo'])) { ?><span style="color: green;">{{ t['info-plug'] }}</span><?php } else { ?><span style="color: red;">{{ t['info-unplug'] }}</span><?php } ?></td>
        </tr>
        <tr>
            <td>GD</td>
            <td><?php if (isset($stats['gd'])) { ?><span style="color: green;">{{ t['info-plug'] }}</span><?php } else { ?><span style="color: red;">{{ t['info-unplug'] }}</span><?php } ?></td>
        </tr>
        <tr>
            <td>Iconv</td>
            <td><?php if (isset($stats['iconv'])) { ?><span style="color: green;">{{ t['info-plug'] }}</span><?php } else { ?><span style="color: red;">{{ t['info-unplug'] }}</span><?php } ?></td>
        </tr>
        <tr>
            <td>Json</td>
            <td><?php if (isset($stats['json'])) { ?><span style="color: green;">{{ t['info-plug'] }}</span><?php } else { ?><span style="color: red;">{{ t['info-unplug'] }}</span><?php } ?></td>
        </tr>
        <tr>
            <td>Libxml</td>
            <td><?php if (isset($stats['libxml'])) { ?><span style="color: green;">{{ t['info-plug'] }}</span><?php } else { ?><span style="color: red;">{{ t['info-unplug'] }}</span><?php } ?></td>
        </tr>
        <tr>
            <td>MBstring</td>
            <td><?php if (isset($stats['mbstring'])) { ?><span style="color: green;">{{ t['info-plug'] }}</span><?php } else { ?><span style="color: red;">{{ t['info-unplug'] }}</span><?php } ?></td>
        </tr>
        <tr>
            <td>MySQL</td>
            <td><?php if (isset($stats['mysql'])) { ?><span style="color: green;">{{ t['info-plug'] }}</span><?php } else { ?><span style="color: red;">{{ t['info-unplug'] }}</span><?php } ?></td>
        </tr>
        <tr>
            <td>PDO</td>
            <td><?php if (isset($stats['PDO'])) { ?><span style="color: green;">{{ t['info-plug'] }}</span><?php } else { ?><span style="color: red;">{{ t['info-unplug'] }}</span><?php } ?></td>
        </tr>
        <tr>
            <td>PDO MySQL</td>
            <td><?php if (isset($stats['pdo_mysql'])) { ?><span style="color: green;">{{ t['info-plug'] }}</span><?php } else { ?><span style="color: red;">{{ t['info-unplug'] }}</span><?php } ?></td>
        </tr>
        <tr>
            <td>Session</td>
            <td><?php if (isset($stats['session'])) { ?><span style="color: green;">{{ t['info-plug'] }}</span><?php } else { ?><span style="color: red;">{{ t['info-unplug'] }}</span><?php } ?></td>
        </tr>
        </tbody>
    </table>
{% endblock %}
