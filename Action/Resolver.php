<?php
declare(strict_types=1);

namespace Niktar\OrderAutomation\Action;

use Psr\Log\LoggerInterface;

class Resolver
{
    /**
     * @param Pool $actionPool
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly Pool $actionPool,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @param string $actionType
     * @return ActionInterface|null
     */
    public function resolve(string $actionType): ?ActionInterface
    {
        try {
            return $this->actionPool->getAction($actionType);
        } catch (\InvalidArgumentException $e) {
            $this->logger->error('Cannot resolve action: ' . $e->getMessage());
            return null;
        }
    }
}
