<?php
/**
 * Created by PhpStorm.
 * User: kachuru
 * Date: 12/06/15
 * Time: 20:22
 */

namespace Lunchbot\Entity;


interface Members
{
    public function add(Member $member);

    public function all();
}