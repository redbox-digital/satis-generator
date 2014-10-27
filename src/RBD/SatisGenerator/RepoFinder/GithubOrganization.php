<?php

namespace RBD\SatisGenerator\RepoFinder;

use GuzzleHttp\Client;

use RBD\SatisGenerator\RepoFinderInterface;

class GithubOrganization implements RepoFinderInterface
{
    public function findRepos($account, $username, $password)
    {
        $client = new Client();
        $url = 'https://api.github.com/orgs/' . $account . '/repos';

        $authArray = array($username, $password);
        $apiRepos = $client->get($url, array('auth' => $authArray))->json();

        $repoConfigs = array_map(
            function ($apiRepo) {
                return array(
                    'type' => 'vcs',
                    'url' => $apiRepo['ssh_url']
                );
            },
            $apiRepos
        );

        return $repoConfigs;

    }
}

