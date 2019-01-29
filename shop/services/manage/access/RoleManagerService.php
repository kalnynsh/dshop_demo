<?php

namespace shop\services\manage\access;

use yii\rbac\ManagerInterface;

/**
 * Managing roles
 *
 * @property ManagerInterface $manager
 */
class RoleManagerService
{
    private $manager;

    public function __construct(ManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Adds a role to user by id via the RBAC system.
     * The user can have only one role.
     *
     * @param int $userId
     * @param string $name - the role name
     * @return void
     * @throws \DomainException if the name of the role is not exist
     */
    public function assign($userId, $name): void
    {
        if (!$role = $this->manager->getRole($name)) {
            throw new \DomainException('Given role "' . $name . '" doesn`t exist.');
        }

        $this->manager->revokeAll($userId);
        $this->manager->assign($role, $userId);
    }

    /**
     * Returns the roles that are assigned to the user via assign()
     * Note that child roles that are not assigned directly to the user will not be returned.
     * @param int $userId the user ID
     * @return Role[] all roles directly assigned to the user. The array is indexed by the role names.
     */
    public function getRolesByUser($userId): array
    {
        return $manager->getRolesByUser($userId);
    }

    /**
     * Returns all roles in the system.
     * @return Role[] all roles in the system. The array is indexed by the role names.
     */
    public function getRoles(): array
    {
        return $manager->getRoles();
    }
}
