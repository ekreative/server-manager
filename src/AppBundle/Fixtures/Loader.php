<?php
/**
 * Created by mcfedr on 1/20/16 16:39
 */

namespace AppBundle\Fixtures;

use h4cc\AliceFixturesBundle\Fixtures\FixtureManagerInterface;
use h4cc\AliceFixturesBundle\Fixtures\FixtureSet;
use Symfony\Component\Finder\Finder;

class Loader
{
    /**
     * @var FixtureManagerInterface
     */
    private $manager;

    public function __construct(FixtureManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function load()
    {
        $set = new FixtureSet([
            'do_drop' => true,
            'do_persist' => true
        ]);

        foreach ((new Finder())->files()->in(__DIR__ . '/../Resources/fixtures')->name('*.yml') as $file) {
            $set->addFile($file, 'yaml');
        }

        $this->manager->load($set);
    }
}
