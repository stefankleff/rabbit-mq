<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\RabbitMq\Model\Manager;

use Generated\Shared\Transfer\RabbitMqOptionTransfer;
use PhpAmqpLib\Channel\AMQPChannel;
use Spryker\Client\RabbitMq\Model\Helper\QueueEstablishmentHelperInterface;

class Manager implements ManagerInterface
{

    /**
     * @var AMQPChannel
     */
    protected $channel;

    /**
     * @var QueueEstablishmentHelperInterface
     */
    protected $queueEstablishmentHelper;

    /**
     * @param AMQPChannel $channel
     * @param QueueEstablishmentHelperInterface $queueEstablishmentHelper
     */
    public function __construct(AMQPChannel $channel, QueueEstablishmentHelperInterface $queueEstablishmentHelper)
    {
        $this->channel = $channel;
        $this->queueEstablishmentHelper = $queueEstablishmentHelper;
    }

    /**
     * @param string $queueName
     * @param array $options
     *
     * @return array
     */
    public function createQueue($queueName, array $options = [])
    {
        /** @var RabbitMqOptionTransfer $rabbitMqOption */
        $rabbitMqOption = $options['rabbitMqConsumerOption'];

        $this->queueEstablishmentHelper->createQueue($this->channel, $rabbitMqOption);
    }

    /**
     * @param string $queueName
     * @param array $options
     *
     * @return bool
     */
    public function deleteQueue($queueName, array $options = [])
    {
        $this->channel->queue_delete($queueName);
    }

    /**
     * @param string $queueName
     * @param array $options
     *
     * @return bool
     */
    public function purgeQueue($queueName, array $options = [])
    {
        $this->channel->queue_purge($queueName);
    }
}
