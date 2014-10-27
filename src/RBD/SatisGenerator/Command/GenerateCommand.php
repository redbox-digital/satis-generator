<?php

/**
 * Add a commond to satis to find modules.
 *
 * Search the redbox-digital Bitbucket account for all magento
 * modules, and generate a file, satis.json. This will be used to
 * build the satis repository.
 *
 * @author Max Bucknell <max.bucknell@redboxdigital.com>
 * @copyright Redbox Digital 2014
 * @licence Proprietary
 */



namespace RBD\SatisGenerator\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

use RBD\SatisGenerator\RepoFinder;

/**
 * @author Max Bucknell <max.bucknell@redboxdigital.com>
 */
class GenerateCommand extends Command
{
    private $config;
    private $repoFinder;

    public function __construct($config)
    {
        $this->config = $config;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('generate')
             ->setDescription('Builds satis.json from a Github or Bitbucket account');

        $repoFinders = array(
            'bitbucket' => new RepoFinder\Bitbucket(),
            'github-org' => new RepoFinder\GithubOrganization(),
        );

        $apiMethod = $this->getConfig()['connection']['api'];

        $this->repoFinder = $repoFinders[$apiMethod];
    }

    private function getPackageFinder()
    {
        return $this->repoFinder;
    }

    private function getConfig()
    {
        return $this->config;
    }

    private function getOutput()
    {
        $output = array(
            'name' => $this->getConfig()['output']['name'],
            'description' => $this->getConfig()['output']['description'],
            'homepage' => $this->getConfig()['output']['homepage']
        );

        return $output;
    }

    private function getRepositories()
    {
        $account = $this->getConfig()['connection']['account'];
        $username = $this->getConfig()['connection']['username'];
        $password = $this->getConfig()['connection']['password'];

        $repoFinder = $this->getPackageFinder();

        $repositories = $repoFinder->findRepos($account, $username, $password);

        return $repositories;
    }

    private function encodeOutput($output)
    {
        $encodedOutput = json_encode($output, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

        return $encodedOutput;
    }

    private function getDestinationFile()
    {
        return $this->getConfig()['output']['file'];
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output = $this->getOutput();
        $output['repositories'] = $this->getRepositories();
        $encodedOutput = $this->encodeOutput($output);

        $destination = $this->getDestinationFile();

        $result = file_put_contents($destination, $encodedOutput);

        return $result === false ? 1 : 0;
    }
}

