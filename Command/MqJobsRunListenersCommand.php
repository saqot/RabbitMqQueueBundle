<?php

namespace Saq\RabbitMqQueueBundle\Command;

use Saq\RabbitMqQueueBundle\Exception\MqException;
use Saq\RabbitMqQueueBundle\Service\MqConsole;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\LockHandler;

/**
 * class:  MqJobsRunListenersCommand
 * -----------------------------------------------------
 * @author  Saqot (Mihail Shirnin) <saqott@gmail.com>
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

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$io = new SymfonyStyle($input, $output);
		$service = $input->getOption('service');
		$limit = $input->getOption('limit');

		if ($service) {
			// логика по лимитам
			$locked = false;

			for ($i = 1; $i <= $limit; $i++) {
				$lock = new LockHandler("{$this->commandName} {$service} $i");
				if ($locked = $lock->lock()) {
					break;
				}
			}

			if (!$locked) {
				echo 'limit';
				return false;
			} else {
				if (!$oService = $this->getContainer()->has($service)) {
					throw new MqException("класс канала не найден : {$service}" );
				}
				$this->getContainer()->get($service)->listen();
				return true;
			}

		} else {
			// если $service не указан, запускаем регистрацию всех каналов
			$this->runRegistredChannels($io);
		}

		return false;
	}


	protected function runRegistredChannels(SymfonyStyle $io)
	{
		$mqConn = $this->getContainer()->get('saq.rabbitmq.connection');

		$cns = $mqConn->getRegistredChannels();
		foreach ($cns as $cn) {
			$console = new MqConsole();
			$console->run("php bin/console {$this->commandName} --service=\"{$cn['service']}\" --limit={$cn['max_running']}");

			// проверка на ошибки при выполнении смой консольной команды
			if ($err = $console->getError()) {
				throw new MqException($err);
			}

			// проверка на limit . Отдается методом execute выше
			if ($console->getResult() == 'limit') {
				$io->error('Достигнут лимит запущенных слушателей service: ' . $cn['service']);
				continue;
			}
		}

		return true;
	}
}

