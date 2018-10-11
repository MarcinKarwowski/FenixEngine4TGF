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

namespace Main\Controller;

use Phalcon\Tag;
use Phalcon\Db\RawValue;
use Main\Forms\LoginForm;
use Main\Forms\SignUpForm;
use Main\Forms\ForgotPasswordForm;
use Main\Forms\ChangePasswordForm;
use App\Exception as AuthException;
use Main\Models\Users;
use Main\Models\ResetPasswords;
use Main\Models\EmailJobs;

/**
 * Controller used handle non-authenticated session actions like login/logout, user signup, and forgotten passwords
 */
class SessionController extends ControllerBase
{

    /**
     * Default action. Set the public layout (layouts/index.volt)
     */
    public function initialize()
    {

    }

    public function indexAction()
    {
        $this->response->redirect('');
        $this->view->disable();

        return false;
    }

    /**
     * Allow a user to signup to the system
     */
    public function signupAction()
    {
        // If register is off in config
        if ($this -> config -> game -> registerOff === true)
        {
            $this->flash->error($this -> translate['register-off']);
            $this->response->redirect('');
            $this->view->disable();

            return false;
        }

        $form = new SignUpForm();

        if ($this->request->isPost()) {

            if ($form->isValid($this->request->getPost()) != false) {

                $user = new Users();

                $user->assign(array(
                    'name' => $this->request->getPost('name', 'striptags'),
                    'email' => $this->request->getPost('semail'),
                    'password' => $this->security->hash($this->request->getPost('spassword')),
                    'date_created' => new RawValue('NOW()'),
                    'date_modified' => new RawValue('NOW()'),
                    'group_id' => 1,
                    'published' => 0,
                    'deleted' => 0,
                    'template' => 'default'
                ));

                if ($user->save()) {

                    // Usuwam zapamiętane dane z formularza
                    $this->session->remove("regName");
                    $this->session->remove("regEmail");

                    $this->flash->success($this -> translate['acl-account_created']);

                    // add events
                    $this->eventsManager->fire("session:afterRegister", $this, $user);
                }
                $strMsg = '';
                foreach ($user->getMessages() as $message) {
                    $strMsg = $strMsg . $message . '<br />';
                }
                if ($strMsg != '') $this->flash->error($strMsg);
            } else {

                $this->session->set('regName', $this->request->getPost('name', 'striptags'));
                $this->session->set('regEmail', $this->request->getPost('semail', 'striptags'));

                $strMsg = '';
                foreach ($form->getMessages() as $message) {
                    $strMsg = $strMsg . $message . '<br />';
                }
                if ($strMsg != '') $this->flash->error($strMsg);
            }

            $this->response->redirect('session/signup');
            $this->view->disable();

            return true;
        }
        else
        {
            $this->forms->set('signup', $form);
        }
    }

    /**
     * Starts a session in the admin backend
     */
    public function loginAction()
    {
        $form = new LoginForm();

        try {

            if (!$this->request->isPost()) {

                if ($this->auth->hasRememberMe()) {
                    return $this->auth->loginWithRememberMe();
                }
            } else {

                if ($form->isValid($this->request->getPost()) == false) {
                    $strMsg = '';
                    foreach ($form->getMessages() as $message) {
                        $strMsg = $strMsg . $message . '<br />';
                    }
                    $this->flash->error($strMsg);
                } else {

                    $this->auth->check(array(
                        'email' => $this->request->getPost('email'),
                        'password' => $this->request->getPost('password'),
                        'remember' => $this->request->getPost('remember')
                    ));

                    // add events
                    $this->eventsManager->fire("session:afterLogin", $this);

                    return $this->response->redirect('/play');
                }
            }
        } catch (AuthException $e) {
            $this->flash->error($e->getMessage());
        }

        return $this->response->redirect();
    }

    /**
     * Shows the forgot password form
     */
    public function forgotPasswordAction()
    {
        $form = new ForgotPasswordForm();

        if ($this->request->isPost()) {

            if ($form->isValid($this->request->getPost()) == false) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {

                $user = Users::findFirstByEmail($this->request->getPost('email'));
                if (!$user) {
                    $this->flash->success($this -> translate['no-user-on-email']);
                } else {

                    $resetPassword = new ResetPasswords();
                    $resetPassword->usersId = $user->id;
                    if ($resetPassword->save()) {
                        $this->flash->success($this -> translate['email-reset-password-confirm']);
                    } else {
                        foreach ($resetPassword->getMessages() as $message) {
                            $this->flash->error($message);
                        }
                    }
                }
            }

            $this->view->disable();

            return $this->response->redirect('session/forgot-password');
        }
        else
        {
            $this->forms->set('forgotpassword', $form);
        }
    }


    /**
     * Confirms an e-mail, if the user must change thier password then changes it
     */
    public function confirmEmailAction()
    {
        $code = $this->dispatcher->getParam('code');
        $confirmation = EmailJobs::findFirstByCode($code);
        if (!$confirmation) {
            $this->flash->error($this -> translate['respas-wrong-code']);
            return $this->dispatcher->forward(array(
                'controller' => 'index',
                'action' => 'index'
            ));
        }
        if ($confirmation->confirmed == 1) {
            $this->flash->error($this -> translate['respas-wrong-code']);
            return $this->dispatcher->forward(array(
                'controller' => 'session',
                'action' => 'login'
            ));
        }
        $confirmation->confirmed = 1;
        /**
         * Change the confirmation to 'confirmed' and update the user to 'active'
         */
        if (!$confirmation->save()) {
            foreach ($confirmation->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->dispatcher->forward(array(
                'controller' => 'index',
                'action' => 'index'
            ));
        }
        $confirmation->user->save(['active' => 1]);
        /**
         * Identify the user in the application
         */
        $this->auth->authUserById($confirmation->user->id);
        /**
         * Check if the user must change his/her password
         */
        $this->flash->success($this -> translate['email-confirm-success']);
        return $this->dispatcher->forward(array(
            'controller' => 'index',
            'action' => 'index'
        ));
    }

    public function resetPasswordAction()
    {

        $code = $this->dispatcher->getParam('code');
        $resetPassword = ResetPasswords::findFirstByCode($code);
        if (!$resetPassword) {
            $this->flash->error($this -> translate['respas-wrong-code']);
            return $this->dispatcher->forward(array(
                'controller' => 'index',
                'action' => 'index'
            ));
        }
        if ($resetPassword->reset != 'N') {
            $this->flash->error($this -> translate['respas-wrong-code']);
            return $this->dispatcher->forward(array(
                'controller' => 'index',
                'action' => 'index'
            ));
        }

        /*
         * Show reset password form
         */
        $form = new ChangePasswordForm();
        if ($this->request->isPost()) {
            if (!$form->isValid($this->request->getPost())) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                $user = Users::findFirstById($resetPassword->usersId);
                $user->password = $this->security->hash($this->request->getPost('password'));

                $resetPassword->reset = 'Y';
                /**
                 * Change the confirmation to 'reset'
                 */
                if (!$resetPassword->save()) {
                    foreach ($resetPassword->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                    return $this->dispatcher->forward(array(
                        'controller' => 'index',
                        'action' => 'index'
                    ));
                }

                if (!$user->save()) {
                    $this->flash->error($user->getMessages());
                } else {
                    $this->flash->success($this -> translate['respas-success']);
                    Tag::resetInput();

                    /**
                     * Identify the user in the application
                     */
                    $this->auth->authUserById($resetPassword->usersId);

                    return $this->dispatcher->forward(array(
                        'controller' => 'index',
                        'action' => 'index'
                    ));
                }
            }
        }
        else
        {
            $this->flash->success($this -> translate['respas-write-pass']);
            $this->forms->set('resetpassword', $form);
        }
    }


    /**
     * Closes the session
     */
    public function logoutAction()
    {
        $this->modelsManager->executeQuery("UPDATE Main\Models\Users SET lpv = ".(time()-900)." WHERE id = ?0", array(0 => $this->auth->getIdentity()['id']));
        $this->auth->remove();
        $this->view->disable();
        return $this->response->redirect('');
    }
}
