<?php

namespace Saq\RabbitMqQueueBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Saq\RabbitMqQueueBundle\Handler\MqLockHandler;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Используем для наследования под консольные комманды
 * -----------------------------------------------------
 * class:  MqJobsListener
 * -----------------------------------------------------
 * @author   Saqot (Mihail Shirnin) <saqott@gmail.com>
 * @package  Saq\RabbitMqQueueBundle\Command
 * -----------------------------------------------------
 * 05.09.2017
 */
class MqJobsListener extends Command
{
	use LockableTrait;

	/**
	 * имя выполняемой команды
	 * @var string
	 */
	protected $commandName = 'mq.jobs:listener';

	/**
	 * описание для листенера
	 * @var string
	 */
	protected $commandDescription = 'Консольный листенер очередей для запуска через CRON';

	/**
	 * Количество запущенных копий слушателей
	 * @var int
	 */
	protected $countProcesses = 1;

	/** @var InputInterface $input */
	protected $input;

	/** @var OutputInterface $output */
	protected $output;

	/** @var QuestionHelper $qHelper */
	protected $qHelper;

	/** @var SymfonyStyle $io */
	protected $io;

    /**
     * @var ContainerInterface|null
     */
    private $container;

    /**
     * MqJobsListener constructor.
     * @param ContainerInterface $container
     */
	public function __construct(ContainerInterface $container)
	{
        $this->container = $container;
		parent::__construct();
	}

    /**
     * @return ContainerInterface|null
     */
    public function getContainer(): ?ContainerInterface
    {
        return $this->container;
    }


	protected function configure()
	{
		// задаем имя командной строки
		$this->setName($this->commandName);

		// описание для листенера
		$this->setDescription($this->commandDescription);

		// пример указания опции
		$this->setDefinition([
			new InputOption('type', 't', InputOption::VALUE_OPTIONAL, '', ''),
		]);

	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->input = $input;
		$this->output = $output;
		$this->qHelper = $this->getHelper('question');
		$this->io = new SymfonyStyle($input, $output);

		$locked = false;

		for ($i = 1; $i <= $this->countProcesses; $i++) {
			$lock = new MqLockHandler($this->commandName . $i);
			if ($locked = $lock->lock()) {
				break;
			}
		}

		if (!$locked) {
			$this->io->error('Достигнут лимит запущенных слушателей');

			return false;
		} else {
			$this->runCommand();

			return true;
		}

	}

	protected function runCommand()
	{

	}

}