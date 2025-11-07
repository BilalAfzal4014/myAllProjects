<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ErrorLog
 *
 * @ORM\Table(name="error_log")
 * @ORM\Entity(repositoryClass="App\Repository\ErrorLogRepository")
 */
class ErrorLog extends BaseEntity
{



    /**
     * @var string
     *
     * @ORM\Column(name="error_code", type="string", length=100)
     */
    private $errorCode;

    /**
     * @var string
     *
     * @ORM\Column(name="error_message", type="text")
     */
    private $errorMessage;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="error_date", type="datetime")
     */
    private $errorDate;

    /**
     * @var string
     *
     * @ORM\Column(name="method_name", type="string", length=100)
     */
    private $methodName;



 

    /**
     * Set errorCode
     *
     * @param string $errorCode
     *
     * @return ErrorLog
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    /**
     * Get errorCode
     *
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * Set errorMessage
     *
     * @param string $errorMessage
     *
     * @return ErrorLog
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }

    /**
     * Get errorMessage
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * Set errorDate
     *
     * @param \DateTime $errorDate
     *
     * @return ErrorLog
     */
    public function setErrorDate($errorDate)
    {
        $this->errorDate = $errorDate;

        return $this;
    }

    /**
     * Get errorDate
     *
     * @return \DateTime
     */
    public function getErrorDate()
    {
        return $this->errorDate;
    }

    /**
     * Set methodName
     *
     * @param string $methodName
     *
     * @return ErrorLog
     */
    public function setMethodName($methodName)
    {
        $this->methodName = $methodName;

        return $this;
    }

    /**
     * Get methodName
     *
     * @return string
     */
    public function getMethodName()
    {
        return $this->methodName;
    }
}
