<?php

namespace Spryker\Client\RabbitMq;

use Generated\Shared\Transfer\QueueConnectionTransfer;
use Generated\Shared\Transfer\QueueOptionTransfer;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Shared\RabbitMq\RabbitMqConstants;
use Spryker\Client\Kernel\Container;
use Spryker\Shared\Config\Config;

class RabbitMqDependencyProvider extends AbstractDependencyProvider
{

    const QUEUE_CONNECTION = 'queue connection config';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container[static::QUEUE_CONNECTION] = function () {
            return $this->getQueueConnection();
        };

        return $container;
    }

    /**
     * @return \Generated\Shared\Transfer\QueueConnectionTransfer
     */
    public function getQueueConnection()
    {
        $queueConfig = $this->getQueueConnectionConfig();

        $connectionTransfer = new QueueConnectionTransfer();
        $connectionTransfer->setHost($queueConfig['host']);
        $connectionTransfer->setPort($queueConfig['port']);
        $connectionTransfer->setUsername($queueConfig['username']);
        $connectionTransfer->setPassword($queueConfig['password']);
        $connectionTransfer->setVirtualHost($queueConfig['virtualHost']);

        $connectionTransfer->setQueueOptionCollection($this->getQueueOptions());

        return $connectionTransfer;
    }

    /**
     * @return \ArrayObject
     */
    protected function getQueueOptions()
    {
        $queueOptionCollection = new \ArrayObject();
        $queueOptionCollection->append(new QueueOptionTransfer());

        return $queueOptionCollection;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function getConfig($key)
    {
        return Config::get($key);
    }

    /**
     * @return array
     */
    protected function getQueueConnectionConfig()
    {
        return [
          'host' => $this->getConfig(RabbitMqConstants::QUEUE_HOST),
          'port' => $this->getConfig(RabbitMqConstants::QUEUE_PORT),
          'username' => $this->getConfig(RabbitMqConstants::QUEUE_USERNAME),
          'password' => $this->getConfig(RabbitMqConstants::QUEUE_PASSWORD),
          'virtualHost' => $this->getConfig(RabbitMqConstants::QUEUE_VIRTUAL_HOST),
        ];
    }

}
