<?php

namespace RBD\SatisGenerator;

/**
 * @author Max Bucknell <max.bucknell@redboxdigital.com>
 */
interface RepoFinderInterface
{
    public function findRepos($account, $username, $password);
}
