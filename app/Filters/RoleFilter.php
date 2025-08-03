<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserRoleModel;

class RoleFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please log in to access this page.');
        }

        $userId = $session->get('user_id');
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Invalid session. Please log in again.');
        }

        // If no specific roles/permissions required, just check if logged in
        if (empty($arguments)) {
            return;
        }

        $userRoleModel = new UserRoleModel();
        
        // Check each argument (can be roles or permissions)
        foreach ($arguments as $requirement) {
            if ($this->checkRequirement($userRoleModel, $userId, $requirement)) {
                return; // User has required access
            }
        }

        // User doesn't have required access
        if ($request->isAJAX()) {
            return service('response')
                ->setStatusCode(403)
                ->setJSON(['error' => 'Access denied. Insufficient permissions.']);
        }

        return redirect()->to('/dashboard')->with('error', 'Access denied. You do not have permission to access this page.');
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an exception or error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do here
    }

    /**
     * Check if user meets the requirement (role or permission)
     */
    private function checkRequirement(UserRoleModel $userRoleModel, int $userId, string $requirement): bool
    {
        // Check if it's a permission (contains a dot)
        if (strpos($requirement, '.') !== false) {
            return $userRoleModel->userHasPermission($userId, $requirement);
        }

        // Check if it's a role
        return $userRoleModel->userHasRole($userId, $requirement);
    }
}
