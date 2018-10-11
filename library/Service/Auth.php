<?php

namespace App\Service;

use Main\Models\Characters;
use Phalcon\Db\RawValue;
use Phalcony\Validator\Exception;
use Main\Models\Users;
use Main\Models\RememberTokens;
use Main\Models\SuccessLogins;
use Main\Models\FailedLogins;

/**
 * Class Auth
 * @package App\Service
 */
class Auth extends \Phalcon\Mvc\User\Component
{
    /**
     * Checks the user credentials
     *
     * @param array $credentials
     * @return boolan
     */
    public function check($credentials)
    {

        // Check if the user exist
        $user = Users::findFirstByEmail($credentials['email']);
        if ($user === false) {
            $this->flash->error($this -> translate['acl-wrongLogin']);
        }
        else
        {
            // Check the password
            if (!$this->security->checkHash($credentials['password'], $user->password)) {
                $this->registerUserThrottling($user->id);
                $this->flash->error($this -> translate['acl-wrongLogin']);
            }
            else
            {
                // Check if the user was flagged
                if ($this->checkUserFlags($user))
                {
                    // Register the successful login
                    $this->saveSuccessLogin($user -> id);

                    // Check if the remember me was selected
                    if (isset($credentials['remember'])) {
                        $this->createRememberEnviroment($user);
                    }

                    $this->setSessionIdentity($user);
                }
                else
                {
                    $this->flash->error($this -> translate['acl-user_inactive']);
                }
            }
        }
    }

    /**
     * Creates the remember me environment settings the related cookies and generating tokens
     *
     * @param \Main\Models\Users $user
     */
    public function saveSuccessLogin($userID)
    {
        $successLogin = new SuccessLogins();
        $successLogin->user_id = $userID;
        $successLogin->ip = $this->request->getClientAddress();
        $successLogin->userAgent = $this->request->getUserAgent();
        if (!$successLogin->save()) {
            $messages = $successLogin->getMessages();
            throw new Exception($messages[0]);
        }
    }

    /**
     * Implements login throttling
     * Reduces the efectiveness of brute force attacks
     *
     * @param int $userId
     */
    public function registerUserThrottling($userId)
    {
        $failedLogin = new FailedLogins();
        $failedLogin->users_id = $userId;
        $failedLogin->ip = $this->request->getClientAddress();
        $failedLogin->attempted = time();
        $failedLogin->save();

        $attempts = FailedLogins::count(array(
            'ip = ?0 AND attempted >= ?1',
            'bind' => array(
                $this->request->getClientAddress(),
                time() - 3600 * 6
            )
        ));

        switch ($attempts) {
            case 1:
            case 2:
                // no delay
                break;
            case 3:
            case 4:
                sleep(2);
                break;
            default:
                sleep(4);
                break;
        }
    }

    /**
     * Creates the remember me environment settings the related cookies and generating tokens
     *
     * @param \Main\Models\Users $user
     */
    public function createRememberEnviroment(Users $user)
    {
        $userAgent = $this->request->getUserAgent();
        $token = md5($user->email . $user->password . $userAgent);

        $remember = new RememberTokens();
        $remember->users_id = $user->id;
        $remember->token = $token;
        $remember->userAgent = $userAgent;

        if ($remember->save() != false) {
            $expire = time() + 86400 * 8;
            $this->cookies->set('RMU', $user->id, $expire);
            $this->cookies->set('RMT', $token, $expire);
        }
    }

    /**
     * Check if the session has a remember me cookie
     *
     * @return boolean
     */
    public function hasRememberMe()
    {
        return $this->cookies->has('RMU');
    }

    /**
     * Logs on using the information in the coookies
     *
     * @return \Phalcon\Http\Response
     */
    public function loginWithRememberMe()
    {
        $userId = $this->cookies->get('RMU')->getValue();
        $cookieToken = $this->cookies->get('RMT')->getValue();

        $user = Users::findFirstById($userId);
        if (isset($user -> id)) {

            $userAgent = $this->request->getUserAgent();
            $token = md5($user->email . $user->password . $userAgent);

            if ($cookieToken == $token) {

                $remember = RememberTokens::findFirst(array(
                    'users_id = ?0 AND token = ?1',
                    'bind' => array(
                        $user->id,
                        $token
                    )
                ));

                if ($remember) {

                    // Check if the cookie has not expired
                    if ((time() - (86400 * 8)) < $remember->created_at) {

                        // Check if the user was flagged
                        if ($this->checkUserFlags($user))
                        {
                            // Register identity
                            $this->setSessionIdentity($user);

                            // Register the successful login
                            $this->saveSuccessLogin($user -> id);

                            return true;
                        }
                        else
                        {
                            $this->flash->error($this -> translate['acl-user_inactive']);
                        }
                    }
                    else $remember ->  delete();
                }
            }
        }

        $this->cookies->get('RMU')->delete();
        $this->cookies->get('RMT')->delete();

        return false;
    }

    /**
     * Checks if the user is banned/inactive/suspended
     *
     * @param \Main\Models\Users $user
     */
    public function checkUserFlags(Users $user)
    {
        if ($user->active != 1) {
            return false;
        }
        return true;
    }

    /**
     * Returns the current identity
     *
     * @return array
     */
    public function getIdentity()
    {
        $identity = $this->session->get('auth-identity');

        if (!is_null($identity)) {
            return $identity;
        }
        elseif ($this->hasRememberMe())
        {
            if ($this->auth->loginWithRememberMe())
            {
                $identity = $this->session->get('auth-identity');
                if (!is_null($identity)) {
                    return $identity;
                }
            }
        }
        return false;
    }

    /**
     * Returns the current identity
     *
     * @return ParseString
     */
    public function getName()
    {
        $identity = $this->session->get('auth-identity');
        return $identity['name'];
    }

    /**
     * Removes the user identity information from session
     */
    public function remove()
    {
        if ($this->cookies->has('RMU')) {
            $this->cookies->get('RMU')->delete();
        }
        if ($this->cookies->has('RMT')) {
            $this->cookies->get('RMT')->delete();
        }
        //$this->session->remove('auth-identity');
        $this->session->destroy();
    }

    /**
     * Auths the user by his/her id
     *
     * @param int $id
     */
    public function authUserById($id)
    {
        $user = Users::findFirstById($id);
        if ($user == false) {
            throw new Exception($this -> translate['acl-user_dontexist']);
        }

        if ($this->checkUserFlags($user))
        {
            $this->setSessionIdentity($user);
        }
        else
        {
            $this->flash->error($this -> translate['acl-user_inactive']);
        }
    }

    /**
     * Get the entity related to user in the active identity
     *
     * @return \Main\Models\Users
     */
    public function getUser()
    {
        $identity = $this->session->get('auth-identity');
        if (isset($identity['id'])) {

            $user = Users::findFirstById($identity['id']);
            if ($user == false) {
                throw new Exception($this -> translate['acl-user_dontexist'].': '.$identity['id']);
            }

            return $user;
        }

        return false;
    }

    /*
     * Get user perms
     */
    private function getUserPermissions(Users $user)
    {
        // load perms to save in session
        $perms = $user -> getPermissions() -> toArray();
        $permslist = [];
        foreach ($perms as $perm)
        {
            $permslist[$perm['permission_name']] = $perm['permission_url'];
            if ($perm['permission_url'] != '') $permslist['adminlink'] = true;
        }
        return $permslist;
    }

    /*
     * Set session identity
     */
    private function setSessionIdentity(Users $user)
    {
        $this->session->set('auth-identity', array(
            'id' => $user->id,
            'name' => $user->name,
            'template' => $user -> template,
            'activeChar' => $user -> active_characters_id,
            'group' => $user -> getGroup() -> title,
            'permissions' => $this -> getUserPermissions($user)
        ));
    }
}
