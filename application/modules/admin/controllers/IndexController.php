<?php

namespace Admin\Controller;

use App\Service\File,
    App\Service\Config,
    Admin\Forms\MainConfigureForm;
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
        $this->view->pageHeader = $this->translate['menu-dashboard'];
        $this->view->pageDesc = $this->translate['dashboard-desc'];

        $this->view->configureGame = false;
    }

    /*
     * System info
     */
    public function infoAction()
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
        // template
        $this->view->pageHeader = $this->translate['info-desc'];
        $this->view->pageDesc = $this->translate['info-more_desc'];
    }

    /*
     * Configure game
     */
    public function configureAction()
    {
        // template
        $this->view->pageHeader = $this->translate['menu-config'];
        $this->view->pageDesc = $this->translate['configuration-desc'];

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
                        'template' => htmlentities($this->request->getPost('template')),
                        'template_text_color' => htmlentities($this->request->getPost('template_text_color'))
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

                $this->flash->success($this->translate['configuration-success']);

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

        require BASE_PATH . '/vendor/autoload.php'; // require composer dependencies

        putenv('COMPOSER_HOME=' . __DIR__ . '/../composer');
        chdir(BASE_PATH . '/');

        // Setup composer output formatter
        $stream = fopen('php://temp', 'w+');
        $output = new StreamOutput($stream);

        $application = new Application();
        $application->setAutoExit(false);
        $application->run(new ArrayInput(array('command' => 'install')), $output);

        if (file_exists(BASE_PATH . '/composer.lock')) {
            unlink(BASE_PATH . '/composer.lock');
        }

        rewind($stream);

        $this->flash->success('<pre>' . stream_get_contents($stream) . '</pre>');
        return $this->response->redirect('/admin/check-update');
    }

    public function checkupdateAction()
    {
        // template
        $this->view->pageHeader = $this->translate['menu-update'];
        $this->view->pageDesc = '';
        $this->view->new_version = trim(file_get_contents('https://raw.githubusercontent.com/ThoranRion/FenixEngine4TGF/master/VERSION.md'.'?'.mt_rand()));
        if (version_compare($this->view->new_version, $this->config->game->engineVer, '>')) {
            $this->view->showUpdate = true;
        }
    }

    /*
     * Make update
     * @TODO - dostosować skrypt autoupdate
     */
    public function runupdateAction()
    {
        $this->view->disable();

        if (!is_dir(APPLICATION_PATH . "/update")) {
            mkdir(APPLICATION_PATH . "/update");
            chmod(APPLICATION_PATH . "/update", 0777);
        }
        file_put_contents(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'update/master.zip',
            file_get_contents('https://github.com/ThoranRion/FenixEngine4TGF/archive/master.zip')
        );

        $zip = new \ZipArchive;
        $res = $zip->open(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'update/master.zip');
        if ($res === TRUE) {
            $zip->extractTo(APPLICATION_PATH . '/update/');
            $zip->close();
        } else {
            $this->flash->error($this->translate['update-worng_unzip']);
            return $this->response->redirect('/admin/check-update');
        }

        // clear model cache
        File::delete(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR);
        if (!is_dir(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'metadata')) {
            mkdir(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'metadata');
            chmod(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'metadata', 0777);
        }

        // Update database
        $sqlupdate = include(APPLICATION_PATH . '/update/FenixEngine4TGF-master/application/modules/install/sql/database.php');
        foreach ($sqlupdate as $query) {
            if (isset($query['check'])) {
                $check = $this->db->query($query['check'])->fetch();
                if ($check) continue;
            }
            $this->db->query($query['make']);
        }

        File::copyDir(APPLICATION_PATH . '/update/FenixEngine4TGF-master', BASE_PATH);
        File::delete(APPLICATION_PATH . '/update/FenixEngine4TGF-master/', true);

        Config::save(
            array('game' => array('engineVer' => trim(file_get_contents('https://raw.githubusercontent.com/ThoranRion/FenixEngine4TGF/master/VERSION.md'.'?'.mt_rand()))))
        );

        $this->flash->success($this->translate['update-game_done']);
        return $this->response->redirect('/admin/check-update');
    }
}
