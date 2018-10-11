<?php
/*
  +------------------------------------------------------------------------+
  | Fenix Engine                                                           |
  +------------------------------------------------------------------------+
  | Copyright (c) 2014-2015 Fenix Engine Team (http://e-fenix.info/)       |
  +------------------------------------------------------------------------+
  | Author: Marcin Karwowski <admin@e-fenix.info>                          |
  +------------------------------------------------------------------------+
 */

namespace Install\Controller;

use App\Service\Config;
use Main\Models\Chats;
use Phalcon\Db\Adapter\Pdo\Mysql as Connection;
use Main\Models\Users;
use Phalcon\Db\RawValue;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        ob_start();
        phpinfo(INFO_MODULES);
        $s = ob_get_contents();
        ob_end_clean();
        $s = strip_tags($s, '<h2><th><td>');
        $s = preg_replace('/<th[^>]*>([^<]+)<\/th>/', '<info>\1</info>', $s);
        $s = preg_replace('/<td[^>]*>([^<]+)<\/td>/', '<info>\1</info>', $s);
        $t = preg_split('/(<h2[^>]*>[^<]+<\/h2>)/', $s, -1, PREG_SPLIT_DELIM_CAPTURE);
        $r = array();
        $count = count($t);
        $p1 = '<info>([^<]+)<\/info>';
        $p2 = '/' . $p1 . '\s*' . $p1 . '\s*' . $p1 . '/';
        $p3 = '/' . $p1 . '\s*' . $p1 . '/';
        for ($i = 1; $i < $count; $i++) {
            if (preg_match('/<h2[^>]*>([^<]+)<\/h2>/', $t[$i], $matchs)) {
                $name = trim($matchs[1]);
                $vals = explode("\n", $t[$i + 1]);
                foreach ($vals AS $val) {
                    if (preg_match($p2, $val, $matchs)) { // 3cols
                        $r[$name][trim($matchs[1])] = array(trim($matchs[2]), trim($matchs[3]));
                    } elseif (preg_match($p3, $val, $matchs)) { // 2cols
                        $r[$name][trim($matchs[1])] = trim($matchs[2]);
                    }
                }
            }
        }
        $this->view->stats = $r;
        $this->view->cache = $this->config->cache->url;
        $this->view->logs = $this->config->logs->url;
        $this->view->avatars = PUBLIC_PATH . $this->config->url->staticBaseUri . 'static/avatars/';

        $this->view->gamename = '';
        $this->view->email = '';
        $this->view->dbuser = '';
        $this->view->dbname = '';
        $this->view->dbpass = '';

        if ($this->request->isPost()) {
            $errors = [];
            if (strlen($this->request->getPost('gamename', 'striptags')) < 5) $errors[] = 'Wpisz przynajmniej 5 znaków w tytule gry';
            if (!filter_var($this->request->getPost('email', 'striptags'), FILTER_VALIDATE_EMAIL)) $errors[] = 'Wpisz poprawny adres email';
            if (strlen($this->request->getPost('dbuser', 'striptags')) < 1) $errors[] = 'Wpisz nazwę użytkownika bazy danych';
            if (strlen($this->request->getPost('dbname', 'striptags')) < 1) $errors[] = 'Wpisz nazwę bazy danych';
            if ($this->request->getPost('dbpass', 'striptags') === null) $errors[] = 'Wpisz hasło bazy danych';

            $this->view->gamename = $this->request->getPost('gamename', 'striptags');
            $this->view->email = $this->request->getPost('email', 'striptags');
            $this->view->dbuser = $this->request->getPost('dbuser', 'striptags');
            $this->view->dbname = $this->request->getPost('dbname', 'striptags');
            $this->view->dbpass = $this->request->getPost('dbpass', 'striptags');

            $parameters = [];
            $parameters['db'] = [
                'adapter' => 'Mysql',
                'host' => 'localhost',
                'username' => $this->view->dbuser,
                'password' => $this->view->dbpass,
                'dbname' => $this->view->dbname,
                'schema' => $this->view->dbname
            ];
            try {
                $connection = new Connection(
                    $parameters['db'] + array('options' => array(
                        \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                        \PDO::ATTR_PERSISTENT => false,
                        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    ))
                );
            } catch (\PDOException $e) {
                $errors[] = 'Wystąpił błąd podczas połączenia do bazy danych: ' . $e->getMessage();
            }

            if (count($errors) > 0) {
                $this->view->errors = $errors;
            }

            $this->di->set(
                'db',
                $connection
            );

            // Update database
            $sqlupdate = include(APPLICATION_PATH . '/modules/install/sql/database.php');
            foreach ($sqlupdate as $query) {
                if (isset($query['check'])) {
                    $check = $connection->query($query['check'])->fetch();
                    if ($check) continue;
                }
                $connection->query($query['make']);
            }

            // create admin user
            $user = new Users();
            $user->assign(array(
                'name' => 'Admin',
                'email' => $this->view->email,
                'password' => $this->security->hash('password'),
                'date_created' => new RawValue('NOW()'),
                'date_modified' => new RawValue('NOW()'),
                'group_id' => 2,
                'published' => 0,
                'deleted' => 0,
                'active' => 1,
                'template' => 'default'
            ));
            if ($user->save()) {
                exit('Nie udało się utworzyć użytkownika. Zgłoś problem na forum.');
            }

            // create default Chats
            $chat = new Chats();
            $chat->assign(array(
                'showinn' => 0,
                'owner_id' => $user->id,
                'title' => 'Karczma',
                'desc' => 'Opis karczmy',
                'days' => 0,
                'hide' => 0,
                'archived' => 0,
                'last_msg_id' => 0,
                'priv' => 1,
            ));

            // save new version to config
            Config::save([
                'game' => [
                    'engineVer' => '1.0.0',
                    'title' => $this->view->gamename,
                ],
                'db' => $parameters['db'],
                'mail' => [
                    'fromName' => $this->view->gamename,
                    'fromEmail' =>  $this->view->email,
                    'serverType' => 'sendmail',
                    'smtp' => [
                        'server' => '',
                        'port' => 25,
                        'security' => '',
                        'username' => '',
                        'password' => ''
                    ]
                ],
            ]);

            $this->flash->success($this -> translate['configuration-success']);
            return  $this->response->redirect('/', false, 301);
        }
    }
}
