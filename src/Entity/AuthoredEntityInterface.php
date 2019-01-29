<?php
/**
 * Created by PhpStorm.
 * User: jvictor
 * Date: 29/01/19
 * Time: 19:04
 */

namespace App\Entity;


use Symfony\Component\Security\Core\User\UserInterface;

interface AuthoredEntityInterface
{
    public function setAuthor(UserInterface $user): AuthoredEntityInterface;
}