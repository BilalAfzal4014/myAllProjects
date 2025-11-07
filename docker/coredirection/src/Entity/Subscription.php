<?php
/**
 * Created by PhpStorm.
 * User: omair
 * Date: 17/8/18
 * Time: 1:34 PM
 */

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="subscription")
 */
class Subscription
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $firstName;
    /**
     * @ORM\Column(type="string")
     */
    private $lastName;
    /**
     * @ORM\Column(type="string")
     */
    private $email;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $phone;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CorporateKey")
     */
    private $key;
    /**
     * @ORM\Column(type="integer",nullable=true, name="product_id")
     */
    private $productId;
    /**
     * @ORM\Column(name="product_type", type="string", columnDefinition="enum( 'package', 'challenge')")
     */
    private $type;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @return mixed
     */
    public function getProductIdForAdmin()
    {
        global $kernel;
        $em = $kernel->getContainer()->get('doctrine')->getManager();

        if($this->getType() == 'challenge'){
            $product = $em->getRepository('GamificationBundle:Challenge')->find($this->productId);
        } else {
            $product = $em->getRepository('CoredirectionBundle:Package')->find($this->productId);
        }
        return $product;
    }

    /**
     * @param mixed $product
     */
    public function setProductId($product)
    {
        $this->productId = $product;
    }

}