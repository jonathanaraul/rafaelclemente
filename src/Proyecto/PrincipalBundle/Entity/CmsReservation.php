<?php

namespace Proyecto\PrincipalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsArticle
 *
 * @ORM\Table(name="cms_reservation")
 * @ORM\Entity
 */
class CmsReservation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Article
     *
     * @ORM\ManyToOne(targetEntity="CmsArticle")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="article", referencedColumnName="id")
     * })
     */
    private $article;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=300, nullable=false)
     */
    private $name;
	
    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=false)
     */
    private $phone;
	
    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;
    /**
     * @var string
     *
     * @ORM\Column(name="rdate", type="string", length=255, nullable=false)
     */
    private $rdate;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;
	
    /**
     * @var boolean
     *
     * @ORM\Column(name="checked", type="integer", nullable=false)
     */
     private $checked;
	 
    /**
     * @var integer
     *
     * @ORM\Column(name="lang", type="integer", nullable=false)
     */
    private $lang;
	
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Reservation
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
     * Set phone
     *
     * @param string $phone
     * @return Reservation
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Reservation
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }
    /**
     * Set rdate
     *
     * @param string $rdate
     * @return Reservation
     */
    public function setRdate($rdate)
    {
        $this->rdate = $rdate;
    
        return $this;
    }

    /**
     * Get rdate
     *
     * @return string 
     */
    public function getRdate()
    {
        return $this->rdate;
    }
    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Reservation
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }


    /**
     * Set checked
     *
     * @param boolean $checked
     * @return Reservation
     */
    public function setChecked($checked)
    {
        $this->checked = $checked;
    
        return $this;
    }

    /**
     * Get checked
     *
     * @return boolean 
     */
    public function getChecked()
    {
        return $this->checked;
    }

    /**
     * Set article
     *
     * @param \Proyecto\PrincipalBundle\Entity\CmsArticle $article
     * @return CmsReservation
     */
    public function setArticle(\Proyecto\PrincipalBundle\Entity\CmsArticle $article = null)
    {
        $this->article = $article;
    
        return $this;
    }

    /**
     * Get article
     *
     * @return \Proyecto\PrincipalBundle\Entity\CmsArticle 
     */
    public function getArticle()
    {
        return $this->article;
    }

	
    /**
     * Set lang
     *
     * @param integer $lang
     * @return CmsReservation
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
    
        return $this;
    }

    /**
     * Get lang
     *
     * @return integer 
     */
    public function getLang()
    {
        return $this->lang;
    }
}