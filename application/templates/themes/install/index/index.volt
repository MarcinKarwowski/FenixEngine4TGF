{% extends "mainpage.volt" %}

{% block content %}
    <div class="install-form">
        <h1>Zainstaluj Fenix Engine</h1>
        <?php
            if (isset($errors)) { ?>
        <div class="alert alert-danger">
            <?php
                foreach ($errors as $error) { ?>
                    <p><strong>Błąd:</strong> <?php echo $error; ?></p>
            <?php } ?>
        </div>
        <?php } ?>
        <form class="form-horizontal" action="" method="post">
            <div class="form-group">
                <label class="control-label col-xs-3" for="gamename">Nazwa gry:</label>
                <div class="col-xs-9">
                    <input type="text" class="form-control" name="gamename" id="gamename" value="{{ gamename }}" placeholder="Nazwa gry"
                           required>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-3" for="email">Twój email:</label>
                <div class="col-xs-9">
                    <input type="email" class="form-control" name="email" id="email" value="{{ email }}" placeholder="Twój email"
                           required>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-offset-3 col-xs-9">
                    <div class="well">Informacje o twoim serwerze:</div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-3" for="dbuser">PhalconPHP</label>
                <div class="col-xs-9">
                    <div class="form-control" disabled>Minimalna wersja to 2.0 Twoja wersja to <?php  echo Phalcon\Version::get(); ?></div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-3" for="dbuser">PHP</label>
                <div class="col-xs-9">
                    <div class="form-control" disabled><?php if (isset($stats['Core'])) {
                        $phpversion = (float)phpversion();
                        $arrphpversion = explode('.', $phpversion);
                        if ($arrphpversion[0] < 5 || ($arrphpversion[0] == 5 && $arrphpversion[1] < 6)) echo '<span style="color: red;">Minimalna wersja PHP to 5.6 Twoja wersja to '.$phpversion.'</span>';
                        else echo '<span style="color: green;">Minimalna wersja PHP to 5.6 Twoja wersja to '.$phpversion.'</span>';
                        } else { echo 'Zainstaluj PHP'; } ?></div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-3" for="dbuser">PHP Memory limit</label>
                <div class="col-xs-9">
                    <div class="form-control" disabled><?php if (isset($stats['Core'])) {
                        if ($stats['Core']['memory_limit'][0] < 128) echo '<span style="color: red;">Za mało pamięci, ustaw przynajmniej 128MB</span>';
                        else echo '<span style="color: green;">'.$stats['Core']['memory_limit'][0].'</span>';
                    } else { echo 'Zainstaluj PHP'; } ?></div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-3" for="dbuser">Folder application/cache</label>
                <div class="col-xs-9">
                    <div class="form-control" disabled><?php
            if (substr(sprintf('%o', fileperms($cache)), -4) == '0777') echo '<span style="color: green;">Zapis możliwy</span>';
                        else echo '<span style="color: red;">Zapis niemożliwy, ustaw uprawnienia 0777</span>';
                        ?></div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-3" for="dbuser">Folder application/cache/metadata</label>
                <div class="col-xs-9">
                    <div class="form-control" disabled><?php
            if (substr(sprintf('%o', fileperms($cache.'metadata')), -4) == '0777') echo '<span style="color: green;">Zapis możliwy</span>';
                        else echo '<span style="color: red;">Zapis niemożliwy, ustaw uprawnienia 0777</span>';
                        ?></div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-3" for="dbuser">Folder application/logs</label>
                <div class="col-xs-9">
                    <div class="form-control" disabled><?php
           if (substr(sprintf('%o', fileperms($logs)), -4) == '0777') echo '<span style="color: green;">Zapis możliwy</span>';
                        else echo '<span style="color: red;">Zapis niemożliwy, ustaw uprawnienia 0777</span>';
                        ?></div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-3" for="dbuser">Folder assets/static/avatars</label>
                <div class="col-xs-9">
                    <div class="form-control" disabled><?php
           if (substr(sprintf('%o', fileperms($avatars)), -4) == '0777') echo '<span style="color: green;">Zapis możliwy</span>';
                        else echo '<span style="color: red;">Zapis niemożliwy, ustaw uprawnienia 0777</span>';
                        ?></div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-offset-3 col-xs-9">
                    <div class="well">Podaj dane Twojej bazy danych:</div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-3" for="dbuser">Użytkownik bazy danych:</label>
                <div class="col-xs-9">
                    <input type="text" class="form-control" name="dbuser" id="dbuser" value="{{ dbuser }}" required>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-3" for="dbpass">Hasło do bazy danych:</label>
                <div class="col-xs-9">
                    <input type="text" class="form-control" id="dbpass" name="dbpass" value="{{ dbpass }}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-3" for="dbname">Nazwa bazy danych:</label>
                <div class="col-xs-9">
                    <input type="text" class="form-control" id="dbname" name="dbname" value="{{ dbname }}" required>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-offset-3 col-xs-9">
                    <div class="alert alert-info">
                        <strong>Uwaga:</strong> Wszelkie informacje podane na tej stronie można edytować w pliku
                        parameters.php
                        znajdującym się w folderze application/config.
                        Można także skorzystać z formularza w panelu administratora gry.
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-offset-3 col-xs-9">
                    <input type="submit" class="btn btn-primary" value="Zapisz dane">
                </div>
            </div>
        </form>
    </div>
{% endblock %}
