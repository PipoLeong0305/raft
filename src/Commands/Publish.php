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

        $publisher = new Publisher( VENDORPATH . 'pipo/raft/local', ROOTPATH);

        try {

            $publisher->publish();

        } catch (Throwable $e) {

            $this->showError($e);

        }
    }
}