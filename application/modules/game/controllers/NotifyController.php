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

namespace Game\Controller;

use Main\Models\Notifications;
use Main\Models\CharactersNotifications;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;

class NotifyController extends ControllerBase
{
    public $icons = [
        'CHAR' => 'fa-info-circle',
        'NEWS' => 'fa-asterisk',
        'ACTION' => 'fa-bolt',
    ];

    public function indexAction()
    {
        // get whole topic with pagination
        $page = $this->dispatcher->getParam('digit');
        if (empty($page)) $page = 1;

        $type = $this->dispatcher->getParam('type');
        if (isset($type) && !isset($this -> icons[$type])) unset($type);

        $query = new \Phalcon\Mvc\Model\Query\Builder();
        $query->columns(array('Notify.*', 'CharactersNotifications.readed', 'CharactersNotifications.expiry'));
        $query->from(array('Notify' => 'Main\Models\Notifications'));
        $query->leftjoin('Main\Models\CharactersNotifications', 'Notify.id = CharactersNotifications.game_notifications_id', 'CharactersNotifications');
        $query->where('CharactersNotifications.character_id = :charID:', array('charID' => $this->auth->getIdentity()['activeChar']));
        if (isset($type)) $query->andwhere("Notify.type = ?1", array(1 => $type));
        $query->orwhere("Notify.globals = 1");
        if (isset($type)) $query->andwhere("Notify.type = ?1", array(1 => $type));
        $query->orderBy('Notify.id DESC');

        // Create a Model paginator, show 10 rows by page starting from $currentPage
        $paginator = new PaginatorQueryBuilder(
            array(
                "builder" => $query,
                "limit" => 10,
                "page" => $page
            )
        );

        // Get the paginated results
        $this->view->page = $paginator->getPaginate();
        $this->view->nicons = $this->icons;
    }

    public function showAction()
    {
        $phql = 'SELECT Notify.id, Notify.title, Notify.text, Notify.type, Notify.popup, Notify.globals, Notify.date, CharactersNotifications.readed, CharactersNotifications.expiry FROM Main\Models\Notifications AS Notify
                                LEFT JOIN Main\Models\CharactersNotifications AS CharactersNotifications ON Notify.id = CharactersNotifications.game_notifications_id
                                WHERE CharactersNotifications.character_id = :charID: OR Notify.globals = 1
                                ORDER BY Notify.id DESC
                                LIMIT 10';

        $this->view->notifications = $this->modelsManager->executeQuery($phql, array('charID' => $this->auth->getIdentity()['activeChar']))->toArray();
        $this->view->nicons = $this->icons;

        $this->modelsManager->executeQuery("UPDATE Main\Models\CharactersNotifications SET readed = 1 WHERE character_id = ?0 AND readed = 0", array(0 => $this->auth->getIdentity()['activeChar']));
        $this->modelsManager->executeQuery("UPDATE Main\Models\Characters SET newlogs = 0 WHERE id = ?0", array(0 => $this->auth->getIdentity()['activeChar']));
    }
}
