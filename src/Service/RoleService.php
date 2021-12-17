<?php

namespace App\Service;

use App\Entity\Roles;
use App\Repository\RolesRepository;
use Doctrine\ORM\EntityManagerInterface;

class RoleService
{
    public function __construct(RolesRepository $rolesRepository, EntityManagerInterface $em)
    {
        $this->rolesRepository = $rolesRepository;
        $this->em = $em;
    }

    public function addRole($data)
    {
        $role = new Roles();
        $role->setRole($data["role"]);
        $role->setTimestamp();
        $this->em->persist($role);
        try {
            $this->em->flush();
        } catch (\Throwable $th) {
            return ["error" => $th->getMessage()];
        }

        return ["result" => "Role added"];
    }

    public function getRoles()
    {
        try {
            $roles = $this->rolesRepository->fetchRoles();
        } catch (\Throwable $th) {
            return ["error" => $th->getMessage()];
        }

        return $roles;
    }
}