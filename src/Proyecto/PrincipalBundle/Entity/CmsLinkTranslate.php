<?php

namespace Proyecto\PrincipalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsLinkTranslate
 *
 * @ORM\Table(name="cms_link_translate")
 * @ORM\Entity
 */
class CmsLinkTranslate
{
    /**
     * @var integer
     *
     * @ORM\Column(name="link_translate_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $linkTranslateId;

    /**
     * @var integer
     *
     * @ORM\Column(name="link_id", type="integer", nullable=false)
     */
    private $linkId;

    /**
     * @var integer
     *
     * @ORM\Column(name="lang_id", type="integer", nullable=false)
     */
    private $langId;

    /**
     * @var integer
     *
     * @ORM\Column(name="published", type="integer", nullable=true)
     */
    private $published;

    /**
     * @var integer
     *
     * @ORM\Column(name="suspended", type="integer", nullable=true)
     */
    private $suspended;

    /**
     * @var integer
     *
     * @ORM\Column(name="new_window", type="integer", nullable=true)
     */
    private $newWindow;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="www", type="string", length=255, nullable=false)
     */
    private $www;

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
     * Get linkTranslateId
     *
     * @return integer 
     */
    public function getLinkTranslateId()
    {
        return $this->linkTranslateId;
    }

    /**
     * Set linkId
     *
     * @param integer $linkId
     * @return CmsLinkTranslate
     */
    public function setLinkId($linkId)
    {
        $this->linkId = $linkId;
    
        return $this;
    }

    /**
     * Get linkId
     *
     * @return integer 
     */
    public function getLinkId()
    {
        return $this->linkId;
    }

    /**
     * Set langId
     *
     * @param integer $langId
     * @return CmsLinkTranslate
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
     * Set published
     *
     * @param integer $published
     * @return CmsLinkTranslate
     */
    public function setPublished($published)
    {
        $this->published = $published;
    
        return $this;
    }

    /**
     * Get published
     *
     * @return integer 
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set suspended
     *
     * @param integer $suspended
     * @return CmsLinkTranslate
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
     * Set newWindow
     *
     * @param integer $newWindow
     * @return CmsLinkTranslate
     */
    public function setNewWindow($newWindow)
    {
        $this->newWindow = $newWindow;
    
        return $this;
    }

    /**
     * Get newWindow
     *
     * @return integer 
     */
    public function getNewWindow()
    {
        return $this->newWindow;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return CmsLinkTranslate
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
     * Set www
     *
     * @param string $www
     * @return CmsLinkTranslate
     */
    public function setWww($www)
    {
        $this->www = $www;
    
        return $this;
    }

    /**
     * Get www
     *
     * @return string 
     */
    public function getWww()
    {
        return $this->www;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return CmsLinkTranslate
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
     * @return CmsLinkTranslate
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
     * @return CmsLinkTranslate
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
     * @return CmsLinkTranslate
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