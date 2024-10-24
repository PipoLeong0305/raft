<?php

namespace Pipo\Raft\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\Publisher\Publisher;
use Throwable;

class Publish extends BaseCommand
{

    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Raft';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'raft:publish';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = '';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'raft:publish [arguments] [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    public function run(array $params)
    {
        // TODO: Implement run() method.


        // Use the Autoloader to figure out the module path
        $source = service('autoloader')->getNamespace('Pipo\\Raft')[0];
        $publisher  = new Publisher( $source, ROOTPATH);

        try {
            $publisher->addFile('docker-compose.yml');

            $publisher->copy();
//            $publisher->publish();

            // Check if .env file exists
//            if (!file_exists(ROOTPATH . '.env')) {
//                // If .env doesn't exist, copy env to .env
//                copy($source . 'env', ROOTPATH . '.env');
//            }
//
//            $publisher = new Publisher($source, APPPATH);

            
        } catch (Throwable $e) {

            $this->showError($e);

        }

        // Show success message
        CLI::write('Raft published successfully.', 'green');
    }
}