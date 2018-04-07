<?php

namespace Saq\RabbitMqQueueBundle\Command;

use Saq\RabbitMqQueueBundle\Exception\MqException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Saq\RabbitMqQueueBundle\Handler\MqLockHandler;

/**
 * class:  MqJobsRunListenersCommand
 * -----------------------------------------------------
 * @author   Saqot (Mihail Shirnin) <saqott@gmail.com>
 * @package  Saq\RabbitMqQueueBundle\Command
 * -----------------------------------------------------
 * 09.01.2018
 */
class MqJobsRunListenersCommand extends ContainerAwareCommand
{
	use LockableTrait;

	/**
	 * php bin/console mq.jobs:run.listeners
	 * php bin/console mq.jobs:run.listeners --service=*** --limit=99
	 */
	protected $commandName = 'mq.jobs:run.listeners';

	/**
	 * @var string
	 */
	protected $commandDescription = 'Контроль запуска слушателей';

	/**
	 * Количество запущенных копий демона
	 * @var int
	 */
	protected $countProcesses = 3;

	/**
	 * MqJobsRunListenersCommand constructor.
	 */
	public function __construct()
	{
		parent::__construct();
	}

	protected function configure()
	{
		// задаем имя командной строки
		$this->setName($this->commandName);

		// описание для листенера
		$this->setDescription($this->commandDescription);

		// пример указания опции
		$this->setDefinition([
			new InputOption('service', null, InputOption::VALUE_OPTIONAL, '', null),
			new InputOption('limit', null, InputOption::VALUE_OPTIONAL, '', null),
		]);

	}

	/**
	 * @param InputInterface  $input
	 * @param OutputInterface $output
	 * @return int|null|void
	 * @throws MqException
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$io = new SymfonyStyle($input, $output);

		$mqConn = $this->getContainer()->get('saq.rabbitmq.connection');
		$cns = $mqConn->getRegistredChannels();

		foreach ($cns as $cn) {
			$service = $cn['service'];
			$limit = $cn['max_running'];

			if (!$oService = $this->getContainer()->has($service)) {
				throw new MqException("класс канала не найден : {$service}");
			}

			// логика по лимитам
			$locked = false;
			for ($i = 1; $i <= $limit; $i++) {
				$lock = new MqLockHandler("{$this->commandName} {$service} $i");
				if ($locked = $lock->lock()) {
					break;
				}
			}

			if (!$locked) {
				$io->error('Достигнут лимит запущенных слушателей service: ' . $cn['service']);
				continue;
			} else {
				$this->getContainer()->get($service)->listen();
				break;
			}

		}

	}

}

