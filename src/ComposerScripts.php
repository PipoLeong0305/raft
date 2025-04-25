<?php

namespace Raft;

use Composer\Script\Event;
use Composer\Installer\PackageEvent;

class ComposerScripts
{
    /**
     * Handle the post-install Composer event.
     *
     * @param  \Composer\Script\Event  $event
     * @return void
     */
    public static function postInstall(Event $event)
    {
        self::addRaftBinary();
        self::displayWelcomeMessage($event);
    }

    /**
     * Handle the post-update Composer event.
     *
     * @param  \Composer\Script\Event  $event
     * @return void
     */
    public static function postUpdate(Event $event)
    {
        self::addRaftBinary();
    }

    /**
     * Add Raft binary to the project root if not already there
     * 
     * @return void
     */
    protected static function addRaftBinary()
    {
        // Get the root directory of the project
        $rootDir = dirname(dirname(dirname(dirname(__DIR__))));
        
        if (file_exists($rootDir . '/vendor/yourname/codeigniter-raft/bin/raft')) {
            $contents = file_get_contents($rootDir . '/vendor/yourname/codeigniter-raft/bin/raft');
            
            // Create raft binary in project root if it doesn't exist
            if (!file_exists($rootDir . '/raft')) {
                file_put_contents(
                    $rootDir . '/raft',
                    $contents
                );
                chmod($rootDir . '/raft', 0755);
            }
        }
    }

    /**
     * Display the welcome message for Raft installation.
     *
     * @param  \Composer\Script\Event  $event
     * @return void
     */
    protected static function displayWelcomeMessage(Event $event)
    {
        $io = $event->getIO();
        
        $io->write([
            '',
            '    <fg=blue>        _____          _      ___            _ _           _____       __ _   </>',
            '    <fg=blue>       / ____|        | |    |_ _|          (_) |         |  __ \     / _| |  </>',
            '    <fg=blue>      | |     ___   __| | ___ | |_ __   __ _ _| |_ ___ _ __| |__) |__ | |_| |_ </>',
            '    <fg=blue>      | |    / _ \ / _` |/ _ \| | \'_ \ / _` | | __/ _ \ \'__|  _  / _ \|  _| __|</>',
            '    <fg=blue>      | |___| (_) | (_| |  __/| | | | | (_| | | ||  __/ |  | | \ \ __/ | |_| |_</>',
            '    <fg=blue>       \_____\___/ \__,_|\___|___|_| |_|\__, |_|\__\___|_|  |_|  \_\___|_|  \__|</>',
            '    <fg=blue>                                         __/ |                                 </>',
            '    <fg=blue>                                        |___/                                  </>',
            '',
            '    <fg=green>CodeIgniter Raft has been installed successfully!</>',
            '    <fg=green>To get started, run the following command:</>',
            '',
            '    <fg=white>./raft install</>',
            '',
            '    <fg=green>After installation, you can start the environment with:</>',
            '',
            '    <fg=white>./raft up</>',
            '',
        ]);
    }
}