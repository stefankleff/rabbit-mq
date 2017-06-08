<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\RabbitMq;

use ArrayObject;
use Generated\Shared\Transfer\QueueConnectionTransfer;
use Generated\Shared\Transfer\RabbitMqOptionTransfer;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Shared\Config\Config;
use Spryker\Shared\RabbitMq\RabbitMqConstants;

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
        $queueOptionCollection = new ArrayObject();
        $queueOptionCollection->append(new RabbitMqOptionTransfer());

        return $queueOptionCollection;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function getConfigByKey($key)
    {
        return Config::get($key);
    }

    /**
     * @return array
     */
    protected function getQueueConnectionConfig()
    {
        return [
          'host' => $this->getConfigByKey(RabbitMqConstants::RABBITMQ_HOST),
          'port' => $this->getConfigByKey(RabbitMqConstants::RABBITMQ_PORT),
          'username' => $this->getConfigByKey(RabbitMqConstants::RABBITMQ_USERNAME),
          'password' => $this->getConfigByKey(RabbitMqConstants::RABBITMQ_PASSWORD),
          'virtualHost' => $this->getConfigByKey(RabbitMqConstants::RABBITMQ_VIRTUAL_HOST),
        ];
    }

}
