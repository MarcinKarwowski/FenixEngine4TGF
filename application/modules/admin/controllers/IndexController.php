<?php

namespace Admin\Controller;

use App\Service\File,
    App\Service\Config,
    Admin\Forms\MainConfigureForm,
    Game\Shema\LoadDB,
    Main\Models\Characters,
    App\Service\Update;

use Composer\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\StreamOutput;

class IndexController extends ControllerBase
{
    /**
     * Dashboard
     */
    public function indexAction()
    {
        // template
        $this -> view -> pageHeader = $this -> translate['menu-dashboard'];
        $this -> view -> pageDesc = $this -> translate['dashboard-desc'];

        $this -> view -> configureGame = false;
    }

    /*
     * System info
     */
    public function infoAction()
    {
        ob_start(); phpinfo(INFO_MODULES); $s = ob_get_contents(); ob_end_clean();
        $s = strip_tags($s, '<h2><th><td>');
        $s = preg_replace('/<th[^>]*>([^<]+)<\/th>/', '<info>\1</info>', $s);
        $s = preg_replace('/<td[^>]*>([^<]+)<\/td>/', '<info>\1</info>', $s);
        $t = preg_split('/(<h2[^>]*>[^<]+<\/h2>)/', $s, -1, PREG_SPLIT_DELIM_CAPTURE);
        $r = array(); $count = count($t);
        $p1 = '<info>([^<]+)<\/info>';
        $p2 = '/'.$p1.'\s*'.$p1.'\s*'.$p1.'/';
        $p3 = '/'.$p1.'\s*'.$p1.'/';
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
        $this -> view -> stats = $r;
        $this -> view -> cache = $this -> config -> cache -> url;
        $this -> view -> logs = $this -> config -> logs -> url;
        $this -> view -> avatars = PUBLIC_PATH.$this->config->url->staticBaseUri . 'static/avatars/';
        // template
        $this -> view -> pageHeader = $this -> translate['info-desc'];
        $this -> view -> pageDesc = $this -> translate['info-more_desc'];
    }

    /*
     * Configure game
     */
    public function configureAction()
    {
        // template
        $this -> view -> pageHeader = $this -> translate['menu-config'];
        $this -> view -> pageDesc = $this -> translate['configuration-desc'];

        /*
         * Show game configure form
         */
        $form = new MainConfigureForm();
        if ($this->request->isPost()) {
            if (!$form->isValid($this->request->getPost())) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                // url data
                $arrUrl = parse_url($this->request->getPost('url'));
                // save config file
                Config::save(
                    array('game' => array(
                        'title' => $this->request->getPost('title'),
                        'startTime' => strtotime($this->request->getPost('starttime')),
                        'description' => $this->request->getPost('description'),
                        'keywords' => $this->request->getPost('keywords'),
                        'publicUrl' => $arrUrl['host'],
                        'baseUri' => (!in_array($arrUrl['scheme'], array('https', 'http')) ? 'http' : $arrUrl['scheme']) . '://' . $arrUrl['host'] . '/',
                        'registerOff' => ($this->request->getPost('registeroff') == 'true' ? true : false),
                        'GAIdentificator' => $this->request->getPost('ga'),
                        'custom' => htmlentities($this->request->getPost('custom')),
                    ),
                        'mail' => array(
                            'fromName' => $this->request->getPost('emailName'),
                            'fromEmail' => $this->request->getPost('email'),
                            'serverType' => (!in_array($this->request->getPost('emailServerType'), ['smtp', 'sendmail']) ? 'sendmail' : $this->request->getPost('emailServerType')),
                            'smtp' => array(
                                'server' => $this->request->getPost('emailServer'),
                                'port' => $this->request->getPost('emailServerPort'),
                                'security' => $this->request->getPost('emailServerSecurity'),
                                'username' => $this->request->getPost('emailServerUser'),
                                'password' => $this->request->getPost('emailServerPass'),
                            ),
                        )
                    )
                );

                $this->flash->success($this -> translate['configuration-success']);

                $this->response->redirect('/admin/configure');
                $this->view->disable();

                return;
            }
        }

        $this->forms->set('mainconfigure', $form);

    }

    /*
     * Update composer
     */
    public function composerAction()
    {
        ini_set('memory_limit', '1024M');

        require BASE_PATH.'/vendor/autoload.php'; // require composer dependencies

        putenv('COMPOSER_HOME=' . __DIR__ . '/../composer');
        chdir(BASE_PATH.'/');

        // Setup composer output formatter
        $stream = fopen('php://temp', 'w+');
        $output = new StreamOutput($stream);

        $application = new Application();
        $application->setAutoExit(false);
        $application->run(new ArrayInput(array('command' => 'install')), $output);

        if (file_exists(BASE_PATH.'/composer.lock')) {
            unlink(BASE_PATH.'/composer.lock');
        }

        rewind($stream);

        $this->flash->success('<pre>'.stream_get_contents($stream).'</pre>');
        return $this->response->redirect('/admin/check-update');
    }

    /*
     * Make update
     * @TODO - dostosować skrypt autoupdate
     */
    public function runupdateAction()
    {
        $this->view->disable();

        $updateinfo = (new Update) -> checkUpdate();

        if ($updateinfo)
        {
            // clear model cache
            File::delete(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'metadata' . DIRECTORY_SEPARATOR);

            $updateMessage = '';
            // update engine
            if ((int)str_replace('-','', $updateinfo['engine']) > (int)str_replace('.','',$this -> config -> game -> engineVer))
            {
                // download file
                $ch2=curl_init();
                $savetofile = fopen(BASE_PATH . '/update/engine.zip','w+');
                curl_setopt($ch2, CURLOPT_URL, 'http://e-fenix.info/get-update/'.hash('sha256', 'engine.'.$updateinfo['engine'].'.zip'));

                curl_setopt($ch2, CURLOPT_FILE, $savetofile); //auto write to file

                curl_setopt($ch2, CURLOPT_TIMEOUT, 5040);
                curl_setopt($ch2, CURLOPT_POST, 0);
                curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch2,CURLOPT_FAILONERROR,true);
                curl_exec($ch2);
                if(curl_exec($ch2) === false)
                {
                    $this->flash->error($this -> translate['update-wrong_file_download']);
                    return $this->response->redirect('/admin/check-update');
                }
                curl_close($ch2);
                fclose($savetofile);

                $zip = new \ZipArchive;
                if ($zip->open(BASE_PATH . '/update/engine.zip') === TRUE) {
                    $zip->extractTo(BASE_PATH.'');
                    $zip->close();
                } else {
                    $this->flash->error($this -> translate['update-worng_unzip']);
                    return $this->response->redirect('/admin/check-update');
                }

                // save new version to config
                Config::save(
                    array('game' => array(
                        'engineVer' => str_replace('-','.', $updateinfo['engine'])
                    )));

                // Update database
                if (is_file(APPLICATION_PATH . '/modules/install/sql/database.php'))
                {
                    $sqlupdate = include(BASE_PATH . '/update/database/sql.php');
                    foreach ($sqlupdate as $query)
                    {
                        if (isset($query['check']))
                        {
                            $check = $this->db->query($query['check'])->fetch();
                            if ($check) continue;
                        }
                        $this->db->query($query['make']);
                    }
                }

                $updateMessage = $this -> translate['update-engine_done'].'<br />';
            }

            // Update of games
            $cdir = scandir(APPLICATION_PATH . '/storage/');
            foreach ($cdir as $key => $value) {
                if ($value != '..' && $value != '.' && $value != 'index.php') {
                    if (is_dir(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . $value . DIRECTORY_SEPARATOR)) {
                        if (file_exists(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . $value . DIRECTORY_SEPARATOR . 'config.php')) {
                            $gameconfig = include (APPLICATION_PATH . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . $value . DIRECTORY_SEPARATOR . 'config.php');

                            if (isset($updateinfo['games'][$value]) && (int)str_replace('-','', $updateinfo['games'][$value]) > (int)str_replace('.','',$gameconfig -> version))
                            {
                                // download file
                                $ch2=curl_init();
                                $savetofile = fopen(BASE_PATH . '/update/'.$value.'.zip','w+');
                                curl_setopt($ch2, CURLOPT_URL, 'http://e-fenix.info/get-update/'.hash('sha256', 'game.'.$value.'.'.$updateinfo['games'][$value].'.zip'));

                                curl_setopt($ch2, CURLOPT_FILE, $savetofile); //auto write to file

                                curl_setopt($ch2, CURLOPT_TIMEOUT, 5040);
                                curl_setopt($ch2, CURLOPT_POST, 0);
                                curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, 0);
                                curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, FALSE);
                                curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, true);
                                curl_setopt($ch2,CURLOPT_FAILONERROR,true);
                                curl_exec($ch2);
                                if(curl_exec($ch2) === false)
                                {
                                    $this->flash->error($this -> translate['update-wrong_file_download']);
                                    return $this->response->redirect('/admin/check-update');
                                }
                                curl_close($ch2);
                                fclose($savetofile);

                                $zip = new \ZipArchive;
                                if ($zip->open(BASE_PATH . '/update/'.$value.'.zip') === TRUE) {
                                    $zip->extractTo(BASE_PATH.'');
                                    $zip->close();

                                    // Update installed game files
                                    if ($value == $this -> config -> game -> gameEngine)
                                    {
                                        // remove old game if exist
                                        File::delete(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'game' . DIRECTORY_SEPARATOR);
                                        // copy new game
                                        File::copyDir(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'storage'. DIRECTORY_SEPARATOR . $value . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR, APPLICATION_PATH . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'game' . DIRECTORY_SEPARATOR);
                                        // Load default config
                                        $newGameConfig = [];
                                        if (file_exists(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . $value . DIRECTORY_SEPARATOR . 'config.php')) {
                                            $newGameConfig = include (APPLICATION_PATH . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . $value . DIRECTORY_SEPARATOR . 'config.php');
                                        }

                                        // Remove params that already set in game config so we dont need to override them
                                        foreach ($newGameConfig -> defaultConfig as $k => $param)
                                        {
                                            if (isset($this -> config -> game -> params -> $k)) unset($newGameConfig -> defaultConfig[$k]);
                                        }

                                        // save config file
                                        Config::save(array(
                                            'game' => array(
                                                'params' => (isset($newGameConfig -> defaultConfig) ? $newGameConfig -> defaultConfig : [])
                                            )
                                        ));

                                        /*
                                         * Load db shema
                                         */
                                        if (file_exists(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'game' . DIRECTORY_SEPARATOR . 'shema'.DIRECTORY_SEPARATOR.'LoadDB.php'))
                                        {
                                            include(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'game' . DIRECTORY_SEPARATOR . 'shema'.DIRECTORY_SEPARATOR.'LoadDB.php');
                                            (new LoadDB()) -> install($this -> db, $this -> config -> db -> schema);
                                        }
                                    }

                                    $updateMessage = $updateMessage . $this -> translate['update-game_done'].' ('.$value.')<br />';
                                } else {
                                    $this->flash->error($this -> translate['update-worng_unzip']);
                                    return $this->response->redirect('/admin/check-update');
                                }
                            }
                        }
                    }
                }
            }
            $this->flash->success($updateMessage);
            return $this->response->redirect('/admin/check-update');
        }
        else
        {
            $this->flash->error($this -> translate['update-no_information']);
            return $this->response->redirect('/admin/check-update');
        }
    }
}
