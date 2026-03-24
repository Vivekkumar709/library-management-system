<?php
namespace App\Filters;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class UserTypeFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $requiredType = $arguments[0] ?? null;
        $userId = service('auth')->id();
        $user = model('UserModel')
            ->select('auth_user_type.name')
            ->join('auth_user_type', 'users.user_type_id = auth_user_type.id', 'left')
            ->find($userId);

        if (!$user || $user['name'] !== $requiredType) {
            return redirect()->to('/no-permission');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}