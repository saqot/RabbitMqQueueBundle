<?php

namespace Saq\RabbitMqQueueBundle\Command;

use Saq\RabbitMqQueueBundle\Exception\MqException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * class:  MqJobsCheckCommand
 * -----------------------------------------------------
 * @author   Saqot (Mihail Shirnin) <saqott@gmail.com>
 * @package  Saq\RabbitMqQueueBundle\Command
 * -----------------------------------------------------
 * 10.09.2017
 */
class MqJobsCheckCommand extends MqJobsListener
{
	/**
	 * php bin/console mq.jobs:check
	 */
	protected $commandName = 'mq.jobs:check';

	/**
	 * @var string
	 */
	protected $commandDescription = 'Проверка работоспособности бандла';

	/**
	 * Количество запущенных копий демона
	 * @var int
	 */
	protected $countProcesses = 1;

    /**
     * MqJobsListener constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

	protected function runCommand()
	{
		$this->io->title('Запускаем проверку работоспособности бандла');
		$mqConn = $this->getContainer()->get('saq.rabbitmq.connection');

        try {
            if (!$mqConn->getConnection()->isConnected()) {
                $this->io->error('Ошибка подключения к RabbitMq. Проверьте наличие запушенной службы');

                return false;
            }
        } catch (MqException $e) {
            $this->io->error('Ошибка подключения к RabbitMq. Проверьте наличие запушенной службы');

            return false;
        }

        echo "\n [OK] RabbitMq запущен";

		$channelCheck = $this->getContainer()->get('saq.rabbitmq.channel.check');
		$channelCheck
			->addJob('test')
			->addJob('jobdelete')
			->addJob('jobskip')
			->addJob('jobcancel');

		echo "\n [OK] Задание в очередь успешно добавлено";


		$channelCheck->listen();

		$channelCheck->delete();
		echo "\n [OK] Очередь успешно удалена";

		echo "\n\n";
		$this->io->success('Проверка пройдена');

		return true;
	}
}

