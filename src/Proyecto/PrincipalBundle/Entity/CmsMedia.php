<?php

namespace Proyecto\PrincipalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsMedia
 *
 * @ORM\Table(name="cms_media")
 * @ORM\Entity
 */
class CmsMedia
{
    /**
     * @var integer
     *
     * @ORM\Column(name="media_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $mediaId;

    /**
     * @var integer
     *
     * @ORM\Column(name="rank", type="integer", nullable=false)
     */
    private $rank;

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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="friendly_name", type="string", length=255, nullable=false)
     */
    private $friendlyName;

    /**
     * @var string
     *
     * @ORM\Column(name="file_name_mini", type="string", length=50, nullable=true)
     */
    private $fileNameMini;

    /**
     * @var string
     *
     * @ORM\Column(name="file_name_small", type="string", length=50, nullable=true)
     */
    private $fileNameSmall;

    /**
     * @var string
     *
     * @ORM\Column(name="file_name_big", type="string", length=50, nullable=true)
     */
    private $fileNameBig;

    /**
     * @var string
     *
     * @ORM\Column(name="file_name", type="string", length=50, nullable=true)
     */
    private $fileName;

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
     * Get mediaId
     *
     * @return integer 
     */
    public function getMediaId()
    {
        return $this->mediaId;
    }

    /**
     * Set rank
     *
     * @param integer $rank
     * @return CmsMedia
     */
    public function setRank($rank)
    {
        $this->rank = $rank;
    
        return $this;
    }

    /**
     * Get rank
     *
     * @return integer 
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Set published
     *
     * @param integer $published
     * @return CmsMedia
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
     * @return CmsMedia
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
     * @return CmsMedia
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
     * Set friendlyName
     *
     * @param string $friendlyName
     * @return CmsMedia
     */
    public function setFriendlyName($friendlyName)
    {
        $this->friendlyName = $friendlyName;
    
        return $this;
    }

    /**
     * Get friendlyName
     *
     * @return string 
     */
    public function getFriendlyName()
    {
        return $this->friendlyName;
    }

    /**
     * Set fileNameMini
     *
     * @param string $fileNameMini
     * @return CmsMedia
     */
    public function setFileNameMini($fileNameMini)
    {
        $this->fileNameMini = $fileNameMini;
    
        return $this;
    }

    /**
     * Get fileNameMini
     *
     * @return string 
     */
    public function getFileNameMini()
    {
        return $this->fileNameMini;
    }

    /**
     * Set fileNameSmall
     *
     * @param string $fileNameSmall
     * @return CmsMedia
     */
    public function setFileNameSmall($fileNameSmall)
    {
        $this->fileNameSmall = $fileNameSmall;
    
        return $this;
    }

    /**
     * Get fileNameSmall
     *
     * @return string 
     */
    public function getFileNameSmall()
    {
        return $this->fileNameSmall;
    }

    /**
     * Set fileNameBig
     *
     * @param string $fileNameBig
     * @return CmsMedia
     */
    public function setFileNameBig($fileNameBig)
    {
        $this->fileNameBig = $fileNameBig;
    
        return $this;
    }

    /**
     * Get fileNameBig
     *
     * @return string 
     */
    public function getFileNameBig()
    {
        return $this->fileNameBig;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     * @return CmsMedia
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    
        return $this;
    }

    /**
     * Get fileName
     *
     * @return string 
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return CmsMedia
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
     * @return CmsMedia
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
     * @return CmsMedia
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
     * @return CmsMedia
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