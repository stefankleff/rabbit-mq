<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\RabbitMq\Model\Helper;

use Generated\Shared\Transfer\RabbitMqOptionTransfer;
use PhpAmqpLib\Channel\AMQPChannel;

interface QueueEstablishmentHelperInterface
{

    /**
     * @param AMQPChannel $channel
     * @param RabbitMqOptionTransfer $queueOptionTransfer
     *
     * @return RabbitMqOptionTransfer
     */
    public function createQueue(AMQPChannel $channel, RabbitMqOptionTransfer $queueOptionTransfer);


    /**
     * @param AMQPChannel $channel
     * @param RabbitMqOptionTransfer $queueOptionTransfer
     *
     * @return RabbitMqOptionTransfer
     */
    public function createExchange(AMQPChannel $channel, RabbitMqOptionTransfer $queueOptionTransfer);
}
