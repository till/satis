<?php

/*
 * This file is part of Satis.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *     Nils Adermann <naderman@naderman.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Composer\Satis\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Command\Command;
use Composer\Composer;
use Composer\Config;
use Composer\Package\Dumper\ArrayDumper;
use Composer\Package\AliasPackage;
use Composer\Package\LinkConstraint\VersionConstraint;
use Composer\Package\PackageInterface;
use Composer\Json\JsonFile;
use Composer\Satis\Satis;
use Composer\Factory;
use Composer\Util\Filesystem;

/**
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
class S3Command extends Command
{
    protected function configure()
    {
        $this
            ->setName('s3')
            ->setDescription('Operations to manage the associated s3 bucket.')
            ->setDefinition(array(
                new InputArgument('file', InputArgument::OPTIONAL, 'Configuration file for satis.', './satis.json'),
                new InputOption('empty', null, InputOption::VALUE_NONE, 'Empty the bucket.'),
                new InputOption('sync', null, InputOption::VALUE_NONE, 'Sync all files upstream.'),
            ))
            ->setHelp(<<<EOT
The <info>s3</info> command to manage the s3 bucket.

The json config file may contain the following keys:

- "amazon-aws":
- "amazon-aws.key":
- "amazon-aws.secret":
EOT
            )
        ;
    }

    /**
     * @param InputInterface  $input  The input instance
     * @param OutputInterface $output The output instance
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $empty = $input->getOption('empty');
        $sync  = $input->getOption('sync');

        if ((!$empty && !$sync) || ($empty && $sync)) {
            $output->writeln('<error>Need --sync or --empty</error>');
            return 1;
        }

        $file = new JsonFile($input->getArgument('file'));
        if (!$file->exists()) {
            $output->writeln('<error>File not found: '.$input->getArgument('file').'</error>');
            return 1;
        }
        $config = $file->read();

        if (!$config['archive']['directory']) {
            $output->writeln(sprintf("<error>Please set archive.directory in '%s'</error>", $input->getArgument('file'));
            return 1;
        }

        if ($empty) {
            return $this->runEmpty($config);
        }
        return $this->runSync($config);
    }

    protected function runSync(array $config)
    {

    }

    protected function runEmpty(array $config)
    {

    }
}
