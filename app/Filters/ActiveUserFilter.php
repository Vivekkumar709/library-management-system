<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ActiveUserFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $auth = service('auth');
        
        if ($auth->loggedIn()) {
            $user = $auth->user();
            
            // Check if status exists and is not active
            // if (property_exists($user, 'status') && $user->status != 0) {
            //     $auth->logout();
            //     return redirect()->to('/login')
            //         ->with('error', 'Your account is inactive');
            // }
            if (property_exists($user, 'status') && $user->status == 1) {
                $auth->logout();
                return redirect()->to('/login')
                    ->with('error', 'Your account was deactivated during your session');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after response
    }
}        /** @var \CodeIgniter\Shield\Models\UserModel $userModel */
        $userModel = model(\CodeIgniter\Shield\Models\UserModel::class);
        $user      = $userModel->findById($token->user_id);

        if ($user === null) {
            return;
        }

        /**         if (empty($remember) || strpos($remember, ':') === false) {
validator);

        if (! hash_equals($token->hashedValidator, $hashedValidator)) {
            return;
        }

        // Use the same provider and entity type as Shield/Auth
        /** @var \CodeIgniter\Shield\Auth $authService */
        $authService = service('auth');
        $userModel   = $authService->getProvider();
        $user        = $userModel->findById($token->user_id);

        if ($user === null) {
            return;
        }

        // Log         $hashedValidator = hash('sha256', $validator);

        if (! hash_equals($token->hashedValidator, $hashedValidator)) {
            return;
        }

        /** @var \CodeIgniter\Shield\Models\UserModel $userModel */
        $userModel = model(\CodeIgniter\Shield\Models\UserModel::class);
        $user      = $userModel->findById($token->user_id);

        if ($user === null) {
            return;
        }

        /** @var \CodeIgniter\Shield\Authentication\Authenticators\Session $authenticator */
        $authenticator = auth('session')->getAuthenticator();
        $authenticator->login($user);
