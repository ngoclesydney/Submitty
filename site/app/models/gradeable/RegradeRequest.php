<?php

namespace app\models\gradeable;

use app\libraries\Core;
use app\libraries\DateUtils;
use app\models\AbstractModel;

/**
 * Class RegradeRequest
 * @package app\models\gradeable
 *
 * @method \DateTime getTimestamp()
 * @method int getStatus()
 */
class RegradeRequest extends AbstractModel {

    /** @var int The unique Id of this regrade request */
    private $id = 0;
    /** @property @var \DateTime The timestamp (readonly) of most recent update to $status */
    protected $timestamp = null;
    /** @property @var int The status of the regrade request */
    protected $status = 0;

    const STATUS_RESOLVED = 0;
    const STATUS_ACTIVE = -1;

    public function __construct(Core $core, array $details) {
        parent::__construct($core);

        $this->setId($details['id']);
        $this->setStatus($details['status']);
        $this->timestamp = DateUtils::parseDateTime($details['timestamp'], $this->core->getConfig()->getTimezone());
        $this->modified = false;
    }

    /**
     * Internal method to set and sanity check the regrade request id
     * @param int $id
     */
    private function setId(int $id) {
        if ($id < 1) {
            throw new \InvalidArgumentException('Regrade request ids must be > 0');
        }
        $this->id = $id;
    }

    /**
     * Get the id of this regrade request
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Sets the status of the regrade request
     * @param int $status
     */
    public function setStatus(int $status) {
        if (!in_array($status, [self::STATUS_RESOLVED, self::STATUS_ACTIVE])) {
            throw new \InvalidArgumentException('Invalid regrade request status');
        }
        $this->status = $status;
        $this->modified = true;
    }
}
