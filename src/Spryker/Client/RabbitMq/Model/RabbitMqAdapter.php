<?php

namespace Spryker\Client\RabbitMq\Model;

use Generated\Shared\Transfer\QueueReceiveMessageTransfer;
use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Spryker\Client\RabbitMq\Model\Consumer\ConsumerInterface;
use Spryker\Client\RabbitMq\Model\Manager\ManagerInterface;
use Spryker\Client\RabbitMq\Model\Publisher\PublisherInterface;
use Spryker\Client\Queue\Model\Adapter\AdapterInterface;

class RabbitMqAdapter implements AdapterInterface
{

    /**
     * @var \Spryker\Client\RabbitMq\Model\Manager\ManagerInterface
     */
    protected $manager;

    /**
     * @var \Spryker\Client\RabbitMq\Model\Publisher\PublisherInterface
     */
    protected $publisher;

    /**
     * @var \Spryker\Client\RabbitMq\Model\Consumer\ConsumerInterface
     */
    protected $consumer;

    /**
     * @param ManagerInterface $manager
     * @param PublisherInterface $publisher
     * @param ConsumerInterface $consumer
     */
    public function __construct(
        ManagerInterface $manager,
        PublisherInterface $publisher,
        ConsumerInterface $consumer
    ) {
        $this->manager = $manager;
        $this->publisher = $publisher;
        $this->consumer = $consumer;
    }

    /**
     * @param string $queueName
     * @param array $options
     *
     * @return array
     */
    public function createQueue($queueName, array $options = [])
    {
        return $this->manager->createQueue($queueName, $options);
    }

    /**
     * @param string $queueName
     * @param array $options
     *
     * @return bool
     */
    public function purgeQueue($queueName, array $options = [])
    {
        return $this->manager->purgeQueue($queueName, $options);
    }

    /**
     * @param string $queueName
     * @param array $options
     *
     * @return bool
     */
    public function deleteQueue($queueName, array $options = [])
    {
        return $this->manager->deleteQueue($queueName, $options);
    }

    /**
     * @param string $queueName
     * @param int $chunkSize
     * @param array $options
     *
     * @return QueueReceiveMessageTransfer[]
     */
    public function receiveMessages($queueName, $chunkSize = 100, array $options = [])
    {
        return $this->consumer->receiveMessages($queueName, $chunkSize, $options);
    }

    /**
     * @param string $queueName
     * @param array $options
     *
     * @return QueueReceiveMessageTransfer
     */
    public function receiveMessage($queueName, array $options = [])
    {
        return $this->consumer->receiveMessage($queueName, $options);
    }

    /**
     * @param QueueReceiveMessageTransfer $queueReceiveMessageTransfer
     *
     * @return bool
     */
    public function acknowledge(QueueReceiveMessageTransfer $queueReceiveMessageTransfer)
    {
        return $this->consumer->acknowledge($queueReceiveMessageTransfer);
    }

    /**
     * @param QueueReceiveMessageTransfer $queueReceiveMessageTransfer
     *
     * @return bool
     */
    public function reject(QueueReceiveMessageTransfer $queueReceiveMessageTransfer)
    {
        return $this->consumer->acknowledge($queueReceiveMessageTransfer);
    }

    /**
     * @param QueueReceiveMessageTransfer $queueReceiveMessageTransfer
     *
     * @return bool
     */
    public function handleError(QueueReceiveMessageTransfer $queueReceiveMessageTransfer)
    {
        return $this->consumer->handleError($queueReceiveMessageTransfer);
    }

    /**
     * @param string $queueName
     * @param QueueSendMessageTransfer $queueSendMessageTransfer
     *
     * @return void
     */
    public function sendMessage($queueName, QueueSendMessageTransfer $queueSendMessageTransfer)
    {
        $this->publisher->sendMessage($queueName, $queueSendMessageTransfer);
    }

    /**
     * @param string $queueName
     * @param \Generated\Shared\Transfer\QueueSendMessageTransfer[] $queueSendMessageTransfers
     *
     * @return void
     */
    public function sendMessages($queueName, array $queueSendMessageTransfers)
    {
        $this->publisher->sendMessages($queueName, $queueSendMessageTransfers);
    }
}
