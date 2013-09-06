<?php

namespace RandomStuff\Command;

use Knp\Command\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AvatarImportCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('avatar:import')
            ->setDescription('Import user avatars from a Flickr photoset')
            ->addArgument('photoset-id', InputArgument::REQUIRED, 'The photoset ID?')
            ->addOption('step', null, InputOption::VALUE_REQUIRED, 'How many photos should we import per API call?', 50)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $photoset = $input->getArgument('photoset-id');
        $step = (int) $input->getOption('step');

        $output->writeln(sprintf('Fetching photoset %s, %d photos at a time', $photoset, $step));

        $this->fetch($output, $photoset, $step);
    }

    protected function fetch(OutputInterface $output, $photoset_id, $per_page)
    {
        $page = 1;

        do {
            $hasMore = $this->fetchPage($output, $photoset_id, $page++, $per_page);
        } while ($hasMore);
    }

    protected function fetchPage(OutputInterface $output, $photoset_id, $page, $per_page)
    {
        $app = $this->getSilexApplication();
        $res = $app['flickr']->callAnonymous('flickr.photosets.getPhotos', array(
            'photoset_id'   => $photoset_id,
            'extras'        => 'url_q,url_l',
            'per_page'      => $per_page,
            'page'          => $page,
        ));

        $output->writeln(sprintf('Fetching page %d/%d', $page, (int) $res->photoset->attributes()->pages));

        foreach ($res->photoset->photo as $photo) {
            $this->retrievePhoto((string) $photo->attributes()->url_q, (string) $photo->attributes()->url_l);
        }

        return $page < (int) $res->photoset->attributes()->pages;
    }

    protected function retrievePhoto($thumb_url, $url)
    {
        $app = $this->getSilexApplication();
        $filename = pathinfo($url, PATHINFO_BASENAME);

        $app['avatar.filesystem']->write($filename, $this->fetchUrl($url));
        $app['avatar.thumb.filesystem']->write($filename, $this->fetchUrl($thumb_url));
    }

    protected function fetchUrl($url)
    {
        return file_get_contents($url);
    }
}
