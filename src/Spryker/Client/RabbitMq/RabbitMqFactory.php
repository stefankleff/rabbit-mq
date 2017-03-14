<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\RabbitMq;

use Generated\Shared\Transfer\QueueConnectionTransfer;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Spryker\Client\RabbitMq\Model\Adapter;
use Spryker\Client\RabbitMq\Model\Connection\Connection;
use Spryker\Client\RabbitMq\Model\Connection\ConnectionInterface;
use Spryker\Client\RabbitMq\Model\Consumer\Consumer;
use Spryker\Client\RabbitMq\Model\Helper\QueueEstablishmentHelper;
use Spryker\Client\RabbitMq\Model\Helper\QueueEstablishmentHelperInterface;
use Spryker\Client\RabbitMq\Model\Manager\Manager;
use Spryker\Client\RabbitMq\Model\Publisher\Publisher;
use Spryker\Client\Kernel\AbstractFactory;

class RabbitMqFactory extends AbstractFactory
{

    /**
     * @var ConnectionInterface
     */
    protected static $connection;

    /**
     * @return \Spryker\Client\Queue\Model\Adapter\AdapterInterface
     */
    public function createQueueAdapter()
    {
        return new Adapter(
            $this->createManager(),
            $this->createPublisher(),
            $this->createConsumer()
        );
    }

    /**
     * @return \Spryker\Client\RabbitMq\Model\Connection\Connection
     */
    public function createConnection()
    {
        return new Connection(
            $this->createAMQPStreamConnection(),
            $this->createQueueEstablishmentHelper(),
            $this->getQueueConnectionConfig()->getQueueOptionCollection()
        );
    }

    /**.
     * @return ConnectionInterface
     */
    public function createStaticConnection()
    {
        if (static::$connection === null) {
            static::$connection = $this->createConnection();
        }

        return static::$connection;
    }

    /**
     * @return Manager
     */
    protected function createManager()
    {
        return new Manager(
            $this->createStaticConnection()->getChannel(),
            $this->createQueueEstablishmentHelper()
        );
    }

    /**
     * @return \Spryker\Client\RabbitMq\Model\Publisher\Publisher
     */
    protected function createPublisher()
    {
        return new Publisher(
            $this->createStaticConnection()->getChannel()
        );
    }

    /**
     * @return \Spryker\Client\RabbitMq\Model\Consumer\Consumer
     */
    protected function createConsumer()
    {
        return new Consumer(
            $this->createStaticConnection()->getChannel()
        );
    }

    /**
     * @return QueueEstablishmentHelperInterface
     */
    protected function createQueueEstablishmentHelper()
    {
        return new QueueEstablishmentHelper();
    }

    /**
     * @return QueueConnectionTransfer
     */
    protected function getQueueConnectionConfig()
    {
        return $this->getProvidedDependency(RabbitMqDependencyProvider::QUEUE_CONNECTION);
    }

    /**
     * @return AMQPStreamConnection
     */
    protected function createAMQPStreamConnection()
    {
        $queueConnectionConfig = $this->getQueueConnectionConfig();

        return new AMQPStreamConnection(
            $queueConnectionConfig->getHost(),
            $queueConnectionConfig->getPort(),
            $queueConnectionConfig->getUsername(),
            $queueConnectionConfig->getPassword(),
            $queueConnectionConfig->getVirtualHost()
        );
    }
}
