<?php

namespace RBD\SatisGenerator\RepoFinder;

use Composer\Repository\VcsRepository;

use GuzzleHttp\Client;

use RBD\SatisGenerator\RepoFinderInterface;

class Bitbucket implements RepoFinderInterface
{
    public function findRepos($account, $username, $password)
    {
        $client = new Client();
        $url = 'https://bitbucket.org/api/2.0/repositories/' . $account . '?pagelen=100';

        $authArray = array($username, $password);
        $data = $client->get($url, array('auth' => $authArray))->json();

        $pageLen = $data['pagelen'];
        $repoCount = $data['size'];
        $totalPages = (int)ceil($repoCount / $pageLen);
        $url = $data['next'];
        $apiRepos = $data['values'];

        for ($page = 2; $page <= $totalPages; $page += 1) {
            $url = $data['next'];
            $data = $client->get($url, array('auth' => $authArray))->json();
            $apiRepos = array_merge($apiRepos, $data['values']);
        }

        $repoConfigs = array_map(
            function ($apiRepo) {
                return array(
                    'type' => 'vcs',
                    'url' => $apiRepo['links']['clone'][1]['href']
                );
            },
            $apiRepos
        );

        return $repoConfigs;
    }
}

