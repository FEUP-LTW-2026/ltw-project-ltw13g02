<?php 
  declare(strict_types = 1);

  class MembershipPayment {
    private int $id;
    private int $memberId;
    private string $paymentDueTimestamp;
    private float $amount;
    private string $status;

    public function __construct(int $id, int $memberId, string $paymentDueTimestamp, float $amount, string $status) {
      $this->id = $id;
      $this->memberId = $memberId;
      $this->paymentDueTimestamp = $paymentDueTimestamp;
      $this->amount = $amount;
      $this->status = $status;
    }

    public function getId() : int {
      return $this->id;
    }

    public function getMemberId() : int {
      return $this->memberId;
    }

    public function getPaymentDueTimestamp() : string {
      return $this->paymentDueTimestamp;
    }

    public function getAmount() : float {
      return $this->amount;
    }

    public function getStatus() : string {
      return $this->status;
    }
  }
?>