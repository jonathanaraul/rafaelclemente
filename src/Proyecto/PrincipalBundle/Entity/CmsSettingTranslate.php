<?php

namespace Proyecto\PrincipalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsSettingTranslate
 *
 * @ORM\Table(name="cms_setting_translate")
 * @ORM\Entity
 */
class CmsSettingTranslate
{
    /**
     * @var integer
     *
     * @ORM\Column(name="setting_translate_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $settingTranslateId;

    /**
     * @var integer
     *
     * @ORM\Column(name="setting_id", type="integer", nullable=false)
     */
    private $settingId;

    /**
     * @var integer
     *
     * @ORM\Column(name="lang_id", type="integer", nullable=false)
     */
    private $langId;

    /**
     * @var integer
     *
     * @ORM\Column(name="suspended", type="integer", nullable=true)
     */
    private $suspended;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="text", nullable=false)
     */
    private $value;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=15, nullable=false)
     */
    private $ip;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime", nullable=true)
     */
    private $dateCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_updated", type="datetime", nullable=true)
     */
    private $dateUpdated;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;



    /**
     * Get settingTranslateId
     *
     * @return integer 
     */
    public function getSettingTranslateId()
    {
        return $this->settingTranslateId;
    }

    /**
     * Set settingId
     *
     * @param integer $settingId
     * @return CmsSettingTranslate
     */
    public function setSettingId($settingId)
    {
        $this->settingId = $settingId;
    
        return $this;
    }

    /**
     * Get settingId
     *
     * @return integer 
     */
    public function getSettingId()
    {
        return $this->settingId;
    }

    /**
     * Set langId
     *
     * @param integer $langId
     * @return CmsSettingTranslate
     */
    public function setLangId($langId)
    {
        $this->langId = $langId;
    
        return $this;
    }

    /**
     * Get langId
     *
     * @return integer 
     */
    public function getLangId()
    {
        return $this->langId;
    }

    /**
     * Set suspended
     *
     * @param integer $suspended
     * @return CmsSettingTranslate
     */
    public function setSuspended($suspended)
    {
        $this->suspended = $suspended;
    
        return $this;
    }

    /**
     * Get suspended
     *
     * @return integer 
     */
    public function getSuspended()
    {
        return $this->suspended;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return CmsSettingTranslate
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return CmsSettingTranslate
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return CmsSettingTranslate
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    
        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return CmsSettingTranslate
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    
        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime 
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set dateUpdated
     *
     * @param \DateTime $dateUpdated
     * @return CmsSettingTranslate
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;
    
        return $this;
    }

    /**
     * Get dateUpdated
     *
     * @return \DateTime 
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return CmsSettingTranslate
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    
        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }
}