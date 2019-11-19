<?php
declare(strict_types=1);

namespace DateTi\DI\Nette;

use DateTi\DateTi;
use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Processor;

class Extension extends CompilerExtension
{
    protected $defaultWork = [
        'start' => [
            'hour' => 8,
            'minute' => 0,
        ],
        'end' => [
            'hour' => 16,
            'minute' => 30,
        ],
        'weekend' => false,
    ];

    protected $defaultShipper = [
        'endHour' => 14,
        'endMinute' => 0,
        'weekend' => false,
        'deliveryTime' => 1,
    ];


    public function loadConfiguration(): void
    {
        $schema = Expect::structure([
            'timezone' => Expect::string('Europe/Prague'),
            'countries' => Expect::array([]),
            'localizations' => Expect::array([]),
        ]);
        $builder = $this->getContainerBuilder();
        $config = $this->getConfig();
        $processor = new Processor();
        $config = $processor->process($schema, $config);

        $timezone = new \DateTimeZone($config->timezone);

        $builder->addDefinition($this->prefix('calendar'))
            ->setFactory(DateTi::class, ['now', $timezone]);
    }
}
