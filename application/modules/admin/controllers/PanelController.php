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

namespace Admin\Controller;

use Admin\Forms\GameConfigureForm;
use App\Service\Config;
use App\Service\Notify;
use App\Service\ParseString;
use App\Service\File;
use Game\Library\EgoData;
use Game\Models\Achivements;
use Game\Models\Creations;
use Game\Models\Creator;
use Game\Models\Items;
use Game\Models\ItemsCat;
use Game\Models\Locations;
use Game\Models\Relations;
use Main\Models\Chats;
use Main\Models\Users;
use Main\Models\Characters;

class PanelController extends ControllerBase
{
    public function indexAction()
    {
        // template
        $this->view->pageHeader = $this->translate[ 'panel-game_configuration' ];
        $this->view->pageDesc = $this->translate[ 'panel-game_configuration_desc' ];

        /*
         * Show game configure form
         */
        $form = new GameConfigureForm();
        if ($this->request->isPost())
        {
            if (!$form->isValid($this->request->getPost()))
            {
                foreach ($form->getMessages() as $message)
                {
                    $this->flash->error($message);
                }
            }
            else
            {

                // save config file
                Config::save(
                    array('game' => array(
                        'params' => [
                            'charactersAmount' => (int)$this->request->getPost('charactersAmount'),
                            'levelOff' => boolval($this->request->getPost('leveloff')),
                            'levelCap' => (int)$this->request->getPost('levelCap'),
                            'eraDate' => (int)$this->request->getPost('eraDate'),
                            'saveDate' => time(),
                            'mapOn' => (int)$this->request->getPost('mapOn'),
                        ],
                    ),
                    )
                );

                $this->flash->success($this->translate[ 'configuration-success' ]);

                return $this->response->redirect('/admin/game/panel');
            }
        }

        $this->forms->set('gameconfigure', $form);
    }

    /*
     * function refreshes game shema
     */
    public function reloadshemaAction()
    {
        include(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'game' . DIRECTORY_SEPARATOR . 'shema' . DIRECTORY_SEPARATOR . 'LoadDB.php');
        (new LoadDB())->install($this->db, $this->config->db->schema);

        // Update database
        if (is_file(BASE_PATH . '/update/database/sql.php'))
        {
            $sqlupdate = include(BASE_PATH . '/update/database/sql.php');
            foreach ($sqlupdate as $query)
            {
                if (isset($query[ 'check' ]))
                {
                    $check = $this->db->query($query[ 'check' ])->fetch();
                    if ($check) continue;
                }
                $this->db->query($query[ 'make' ]);
            }
        }

        return $this->response->redirect('/admin/game/panel');
    }


    /*
 * function refreshes game shema
 */
    public function gameresetAction()
    {
        // Remove characters
        foreach (Users::find() as $user) {
            foreach ($user->characters as $char)
            {
                $char -> update(['users_id' => 0]);
            }
        }

        // Remove sessions
        foreach (Chats::find() as $chat) {
            $chat -> update(['archived' => 1]);
        }

        return $this->response->redirect('/admin/game/panel');
    }

    /*
     * functions of the panel
     */

    public function creatorAction($params = null)
    {
        if ($this->request->isPost())
        {
            if ($params) $article = Creator::findFirst(["id = ?0", "bind" => [$params]]);
            else $article = new Creator();

            $article->name = $this->request->getPost('title', 'striptags');
            $article->text = $this->request->getPost('text');
            $article->showinprofile = (int)$this->request->getPost('inprofile', 'int');
            $article->showincreator = (int)$this->request->getPost('showincreator', 'int');
            $article->orderid = ($article->showincreator == 0 ? 99 : (int)$this->request->getPost('orderid', 'int') - 1);
            $article->type = $this->request->getPost('type', 'striptags');
            if ($article->type == 'stats')
            {
                $article -> params = json_encode(
                    [
                        'freepoints' => ((int)$this->request->getPost('freepoints', 'int') <= 0 ? 5 : (int)$this->request->getPost('freepoints', 'int')),
                        'basepoints' => ((int)$this->request->getPost('basepoints', 'int') <= 0 ? 1 : (int)$this->request->getPost('basepoints', 'int')),
                        'pc' => ((int)$this->request->getPost('pc', 'int') <= 0 ? 0 : (int)$this->request->getPost('pc', 'int'))
                    ]);
            }
            else $article -> params = '';

            if (!$article->save())
            {
                $strMsg = '';
                foreach ($article->getMessages() as $message)
                {
                    $strMsg = $strMsg . $message . '<br />';
                }
                if ($strMsg != '') $this->flash->error($strMsg);
            }

            // reorder items
            if ($article->showincreator == 1)
            {
                $i = 2;
                foreach (Creator::find(['order' => 'orderid ASC']) as $creator)
                {
                    $creator->update(['orderid' => $i]);
                    $i = $i + 2;
                }
            }

            return $this->response->redirect('admin/game/panel/creator');
        }
        else
        {
            $article = Creator::findFirst(["id = ?0", "bind" => [$params]]);
            if (isset($article -> id) && $article -> params != '')
            {
                $article -> params = json_decode($article -> params, true);
            }
            $this->view->obj = $article;
        }

        $this->view->pageHeader = $this->translate[ 'panel-charcreator_title' ];
        $this->view->pageDesc = '';
    }

    public function delcreatorAction($params = null)
    {
        $article = Creator::findFirst(["id = ?0", "bind" => [$params]]);
        if ($article)
        {
            $this->flash->error($this->translate[ 'panel-charcreator_deleted' ]);
            $article->delete();
        }

        return $this->response->redirect('admin/game/panel/creator');
    }

    public function creationsAction($params = 0, $param = null)
    {
        if ($this->request->isPost())
        {
            if ($param) $article = Creations::findFirst(["id = ?0", "bind" => [$param]]);
            else $article = new Creations();

            $article->name = $this->request->getPost('title', 'striptags');
            $article->text = $this->request->getPost('text');
            $article->category_id = $params;
            $article->wiki_id = $this->request->getPost('wiki_id', 'int');
            $article->params = ' ';

            if (!$article->save())
            {
                $strMsg = '';
                foreach ($article->getMessages() as $message)
                {
                    $strMsg = $strMsg . $message . '<br />';
                }
                if ($strMsg != '') $this->flash->error($strMsg);
            }

            // links
            $links = $this->request->getPost('links');
            $article->links->delete();
            foreach ($links as $link)
            {
                $vals = explode('-', $link);
                if (count($vals) == 2)
                {
                    $nl = new Relations();
                    $nl->page_id = $article->id;
                    $nl->link_page_id = (int) $vals[ 0 ];
                    $nl->value = (int) $vals[ 1 ];
                    $nl->save();
                }

            }

            return $this->response->redirect('admin/game/panel/creator');
        }
        else
        {
            $article = Creations::findFirst(["id = ?0", "bind" => [$param]]);
            $this->view->obj = $article;
            if ($param)
            {
                $links = $article->links;
                $arrLinks = [];
                foreach ($links as $key => $link)
                {
                    $arrLinks[ ] = ['name' => $link->needit->name, 'value' => $link -> value, 'link_page_id' => $link->link_page_id . '-' . $link->value];
                }
                $this->view->links = $arrLinks;
            }
            $this->view->category_id = $params;
        }


    }

    public function delcreationsAction($params = null)
    {
        $article = Creations::findFirst(["id = ?0", "bind" => [$params]]);
        if ($article)
        {
            $this->flash->error($this->translate[ 'panel-charcreator_deleted' ]);
            $article->delete();
        }

        return $this->response->redirect('admin/game/panel/creator');
    }

    /* Items */
    public function itemsAction($params = null)
    {
        if ($this->request->isPost())
        {
            if ($params) $category = ItemsCat::findFirst(["id = ?0", "bind" => [$params]]);
            else $category = new ItemsCat();

            $category->name = $this->request->getPost('title', 'striptags');
            $category->text = $this->request->getPost('text');
            $category->type = 'ITEM';

            if (!$category->save())
            {
                $strMsg = '';
                foreach ($category->getMessages() as $message)
                {
                    $strMsg = $strMsg . $message . '<br />';
                }
                if ($strMsg != '') $this->flash->error($strMsg);
            }

            return $this->response->redirect('admin/game/panel/items');
        }
        else
        {
            $this->view->obj = ItemsCat::findFirst(["id = ?0", "bind" => [$params]]);
        }

        $this->view->pageHeader = $this->translate[ 'items-panel_desc' ];
        $this->view->pageDesc = '';
    }

    public function delitemsAction($params = null)
    {
        $category = ItemsCat::findFirst(["id = ?0", "bind" => [$params]]);
        if (isset($category -> id))
        {
            $this->flash->success($this->translate[ 'items-category_deleted' ]);
            $category->delete();
        }

        return $this->response->redirect('admin/game/panel/items');
    }

    public function itemAction($params = 0, $param = null)
    {
        if ($this->request->isPost())
        {
            if ($param) $item = Items::findFirst(["id = ?0", "bind" => [$param]]);
            else $item = new Items();

            $item->name = $this->request->getPost('title', 'striptags');
            $item->text = $this->request->getPost('text');
            $item->price = $this->request->getPost('price', 'int');

            $parent = ItemsCat::findFirst(["id = ?0", "bind" => [$params]]);
            if (isset($parent -> id))
            {
                $item -> category_id = $parent -> id;
            }
            else $item -> category_id = 0;

            if (!$item->save())
            {
                $strMsg = '';
                foreach ($item->getMessages() as $message)
                {
                    $strMsg = $strMsg . $message . '<br />';
                }
                if ($strMsg != '') $this->flash->error($strMsg);
            }

            return $this->response->redirect('admin/game/panel/items');
        }
        else
        {
            $this->view->obj = Items::findFirst(["id = ?0", "bind" => [$param]]);
            $this->view->category_id = $params;
        }
    }

    public function delitemAction($params = null)
    {
        $item = Items::findFirst(["id = ?0", "bind" => [$params]]);
        if (isset($item -> id))
        {
            $this->flash->success($this->translate[ 'item-deleted' ]);
            $item->delete();
        }

        return $this->response->redirect('admin/game/panel/items');
    }

    public function locationsAction($params = null)
    {
        if ($this->request->isPost())
        {
            if ($params) $location = Locations::findFirst(["id = ?0", "bind" => [$params]]);
            else $location = new Locations();

            $location->name = $this->request->getPost('title', 'striptags');
            $location->type = $this->request->getPost('type', 'striptags');
            $location->text = $this->request->getPost('text');
            $location->coords = ($this -> request->getPost('coords') ? $this -> request->getPost('coords') : 0);

            $parent = Locations::findFirst(["id = ?0", "bind" => [$this->request->getPost('parent', 'int')]]);
            if (isset($parent -> id))
            {
                $location -> parent_id = $parent -> id;
            }
            else $location -> parent_id = 0;

            if (!$location->save())
            {
                $strMsg = '';
                foreach ($location->getMessages() as $message)
                {
                    $strMsg = $strMsg . $message . '<br />';
                }
                if ($strMsg != '') $this->flash->error($strMsg);
            }

            $this->flash->success($this->translate[ 'locations-saved' ]);
            return $this->response->redirect('admin/game/panel/locations');
        }
            else
        {
            $this->view->obj = Locations::findFirst(["id = ?0", "bind" => [$params]]);
        }

        $this->view->pageHeader = $this->translate[ 'locations-desc' ];
        $this->view->pageDesc = '';
    }

    public function dellocationsAction($params = null)
    {
        $location = Locations::findFirst(["id = ?0", "bind" => [$params]]);
        if (isset($location->id))
        {
            // set children locations to root
            $this->modelsManager->executeQuery("UPDATE Game\Models\Locations SET parent_id = 0 WHERE parent_id = ?0", array(0 => $location -> id));

            $this->flash->success($this->translate[ 'locations-deleted' ]);
            $location->delete();
        }

        return $this->response->redirect('admin/game/panel/locations');
    }

    public function pdAction($params = null)
    {
        if ($this->request->isPost())
        {
            if ($params) $achive = Achivements::findFirst(["id = ?0", "bind" => [$params]]);
            else $achive = new Achivements();

            $character = Characters::findFirst(["id = ?0", "bind" => [$this->request->getPost('character', 'int')]]);
            if (!isset($character -> id))
            {
                $this->flash->error($this->translate[ 'no-character' ]);
                return $this->response->redirect('admin/game/panel/pd');
            }

            // check PD data
            $character -> pd = $character -> pd + $this->request->getPost('pd', 'int');
            if ('PD' == 'PD')
            {
                if ($character -> level >= $this -> config -> game -> params -> levelCap)
                {
                    $this->flash->error($this->translate[ 'pd-reach_levelcap' ]);
                    return $this->response->redirect('admin/game/panel/pd');
                }
                if ($character -> pd >= ($nextlevel = EgoData::nextLevel($character -> level)))
                {
                    $gainpc = 0;
                    $stats = Creator::findFirstByType('stats');
                    if (isset($stats -> id) && $stats -> params != '')
                    {
                        $gainpc = json_decode($stats -> params, true)['pc'];
                    }
                    while ($character -> pd >= $nextlevel)
                    {
                        $character -> level = $character -> level + 1;
                        $character -> pd = $character -> pd - $nextlevel;
                        $character -> pc = $character -> pc + $gainpc;

                        $nextlevel = EgoData::nextLevel($character -> level);
                    }
                }
                // send notify to user
                Notify::send([
                    'title' => 'Doświadczenie',
                    'text' => 'Zdobywasz '.$this->request->getPost('pd', 'int').' doświadczenia.',
                    'type' => 'PD',
                    'popup' => 0,
                    'globals' => 0,
                    'character_id' => $character -> id
                ]);
                $character -> newlogs = $character -> newlogs + 1;
                $character -> update();
            }

            $achive->character_id = $character -> id;
            $achive->gain = $this->request->getPost('pd', 'int');
            $achive->type = 'PD';
            $achive->text = (new ParseString())->bbcodetohtml($this->request->getPost('text'));
            $achive->date = time();

            if (!$achive->save())
            {
                $strMsg = '';
                foreach ($achive->getMessages() as $message)
                {
                    $strMsg = $strMsg . $message . '<br />';
                }
                if ($strMsg != '') $this->flash->error($strMsg);
            }

            return $this->response->redirect('admin/game/panel/pd');
        }

        $this->view->pageHeader = $this->translate[ 'pd-label' ];
        $this->view->pageDesc = '';
    }

    public function delpdAction($params = null)
    {
        $achive = Achivements::findFirst(["id = ?0", "bind" => [$params]]);
        if (!isset($achive -> id))
        {
            $this->flash->error($this->translate[ 'pd-no_archive' ]);
            return $this->response->redirect('admin/game/panel/pd');
        }
        if ($achive -> type == 'PD')
        {
            $achive -> owner -> pd = $achive -> owner -> pd - $achive -> gain;
            if ($achive -> owner -> pd < 0)
            {
                $achive -> owner -> level = $achive -> owner -> level - 1;
                $nextlevel = EgoData::nextLevel($achive -> owner -> level);
                while ($achive -> owner -> pd < 0)
                {
                    $achive -> owner -> pd = $achive -> owner -> pd + $nextlevel;
                    if ($achive -> owner -> pd >= 0) break;

                    $achive -> owner -> level = $achive -> owner -> level - 1;
                    if ($achive -> owner -> level < 1) $achive -> owner -> level = 1;

                    $nextlevel = EgoData::nextLevel($achive -> owner -> level);
                }

                $gain = ['freepoints' => 6, 'basepoints' => 1, 'pc' => 3];
                $stats = Creator::findFirstByType('stats');
                if (isset($stats -> id) && $stats -> params != '')
                {
                    $gain = json_decode($stats -> params, true);
                }
                // iterate through stats and update basic stats
                foreach ($stats -> getOptions() AS $option)
                {
                    $this -> db -> query('UPDATE game_cr_players SET `value`='.$gain['basepoints'].' WHERE character_id='.$achive -> owner -> id.' AND page_id='.$option -> id.' LIMIT 1');
                }
                $achive -> owner -> pc = $gain['freepoints'] + (($achive -> owner -> level - 1) * $gain['pc']);
            }
            $achive -> owner -> save();
        }
        $achive -> delete();

        $this->flash->success($this->translate[ 'pd-achive_deleted' ]);
        return $this->response->redirect('admin/game/panel/pd');
    }

    /*
     * Upload game map
     * only zip folder
     */
    public function mapAction()
    {
        $mapdir = PUBLIC_PATH.$this -> getDI()['config'] -> url -> staticBaseUri.'static/map/';
        if (!is_dir($mapdir))
        {
            mkdir($mapdir);
        }
        //Check if the user has uploaded files
        if ($this->request->hasFiles() == true && $this->request->isPost()) {
            $haserror = '';

            //Print the real file names and their sizes
            foreach ($this->request->getUploadedFiles() as $file){
                if (in_array($file ->getRealType(), array('application/x-zip-compressed', 'application/zip', 'zip application/x-compressed', 'zip application/x-zip-compressed', 'zip application/zip', 'zip multipart/x-zip')))
                {
                    if ($file -> getType() != 'application/x-zip-compressed')
                    {
                        unlink($file -> getTempName());
                        $haserror = $this -> translate['map-wrong_ext'].' ('.$file -> getType().')';
                    }
                    else
                    {
                        $zip = new \ZipArchive;
                        $zipContent = $zip -> open($file -> getTempName()); // , \ZIPARCHIVE::CREATE | \ZIPARCHIVE::OVERWRITE
                        if ($zipContent === true)
                        {
                            // delete old files
                            File::delete($mapdir);

                            // unpack new files
                            $extrRet = $zip -> extractTo($mapdir);
                            $zip -> close();

                            if ($extrRet === false)
                            {
                                $haserror = $this -> translate['map-wrong_folder'].' '.$zip -> getStatusString();
                            }
                            else
                            {
                                // If ok, scan folder
                                if ($handle = opendir($mapdir))
                                {
                                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                                    while (false !== ($files = readdir($handle))) {
                                        if ($files != "." && $files != "..")
                                        {
                                            //jeśli element katalogu nie jest także katalogiem usunięcie go
                                            if (is_dir($mapdir . $files)) File::delete($mapdir . $files, true);
                                            else
                                            {
                                                $imgPart = pathinfo($mapdir . $files);
                                                if ($imgPart['extension'] != 'jpg')
                                                {
                                                    unlink($mapdir . $files);
                                                }
                                            }
                                        }
                                    }
                                    closedir($handle);
                                }
                            }
                            // remove zip file
                            unlink($file -> getTempName());
                        }
                        else
                        {
                            $haserror = $this -> translate['map-wrong_zip'];
                        }
                    }
                }
                else
                {
                    $haserror = $this -> translate['map-wrong_ext'].' ('.$file ->getRealType().')';
                }
            }
            if ($haserror != '')
            {
                $this->flash->error($haserror);
                return $this->response->redirect('admin/game/panel/map');
            }

            $this->flash->success($this->translate[ 'map-succes_upload' ]);
            return $this->response->redirect('admin/game/panel/map');
        }
        elseif ($this->request->hasFiles() == false && $this->request->isPost())
        {
            $this->flash->error($this->translate[ 'map-no_upload' ]);
            return $this->response->redirect('admin/game/panel/map');
        }

        // some infos
        $this->view->countfiles = count(glob($mapdir. "*.jpg"));
        $this->view->memorylimit = min((int) (ini_get('upload_max_filesize')), (int) (ini_get('post_max_size')), (int) (ini_get('memory_limit')));

        $this->view->pageHeader = $this->translate[ 'panel-map' ];
        $this->view->pageDesc = '';
    }
}
