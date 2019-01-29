<?php
/**
 * Created by PhpStorm.
 * User: jvictor
 * Date: 29/01/19
 * Time: 19:17
 */

namespace App\Entity;


interface PublishedDateEntityInterface
{
    public function setPublished(\DateTimeInterface $published): PublishedDateEntityInterface;
}