<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Model\Action;

use Psr\Log\LoggerInterface;

class Resolver
{
    /**
     * @param Pool $actionPool
     * @param LoggerInterface $logger
     */
    public function __construct(
        private Pool $actionPool,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @param int $actionType
     * @return ActionInterface|null
     */
    public function resolve(int $actionType): ?ActionInterface
    {
        try {
            return $this->actionPool->getAction($actionType);
        } catch (\InvalidArgumentException $e) {
            $this->logger->error('Cannot resolve action: ' . $e->getMessage());
            return null;
        }
    }
}
