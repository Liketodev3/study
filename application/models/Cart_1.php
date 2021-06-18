<?php

/**
 * Sample cart_details object
 * 
 */
class Cart extends FatModel
{

    const DB_TBL = 'tbl_user_cart';

    /* Cart Types */
    const TYPE_LESSON = 1;
    const TYPE_COURSE = 2;

    private $userId;
    private $cartType;
    private $coupon;
    private $details;

    /**
     * Initialize Cart
     * 
     * @param int $userId
     * @param int $type
     */
    public function __construct(int $userId, int $type = 1)
    {
        parent::__construct();
        $this->coupon = null;
        $this->details = [];
        $this->userId = $userId;
        $this->cartType = $type;
    }

    /**
     * Add Lesson
     * 
     * @param int $teacherId
     * @param int $languageId
     * @param int $duration
     * @param int $quantity
     * @return bool
     */
    public function addLesson(int $teacherId, int $languageId, int $duration, int $quantity): bool
    {
        $this->details[uniqid('lesson', true)] = [
            'duration' => $duration, 'quantity' => $quantity,
            'teacherId' => $teacherId, 'languageId' => $languageId,
        ];
        return $this->refresh();
    }

    /**
     * Update Lesson
     * 
     * @param string $key
     * @param int $quantity
     * @return bool
     */
    public function updateLesson(string $key, int $quantity): bool
    {
        if (!isset($this->details[$key])) {
            $this->error = Label::getLabel('LBL_INVALID_REQUEST');
            return false;
        }
        $this->details[$key] = $quantity;
        return $this->refresh();
    }

    /**
     * Remove Lesson
     * 
     * @param string $key
     * @return bool
     */
    public function removeLesson(string $key): bool
    {
        if (!isset($this->details[$key])) {
            $this->error = Label::getLabel('LBL_ALREADY_REMOVED');
            return false;
        }
        unset($this->details[$key]);
        return $this->refresh();
    }

    /**
     * Clear Cart
     * 
     * @return bool
     */
    public function clear(): bool
    {
        $this->coupon = null;
        $this->details = [];
        return $this->refresh();
    }

    /**
     * Apply Coupon
     * 
     * @param type $code
     * @return bool
     */
    public function applyCoupon($code): bool
    {
        $coupon = Coupon::getByCode($code);
        if (empty($coupon)) {
            $this->error = Label::getLabel('LBL_INVALID_COUPON');
            return false;
        }
        $this->coupon = $coupon;
        return $this->refresh();
    }

    /**
     * Remove Coupon
     * 
     * @param type $code
     * @return bool
     */
    public function removeCoupon($code): bool
    {
        $this->coupon = null;
        return $this->refresh();
    }

    /**
     * Refresh Cart
     * 
     * @return bool
     */
    private function refresh(): bool
    {
        $cartData = [
            'cart_type' => $this->cartType,
            'cart_user_id' => $this->userId,
            'cart_coupon' => json_encode($this->coupon),
            'cart_details' => json_encode($this->details),
            'cart_updated' => date('Y-m-d H:i:s')
        ];
        $record = new TableRecord(static::DB_TBL);
        $record->assignValues($cartData);
        if (!$record->addNew([], $cartData)) {
            $this->error = $record->getError();
            return false;
        }
        return true;
    }

    /**
     * Get Cart Data
     * 
     * @return array
     */
    public function getData(): array
    {
        return [];
    }

}
