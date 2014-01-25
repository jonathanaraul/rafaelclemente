<?php

namespace Proyecto\PrincipalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsArticle
 *
 * @ORM\Table(name="cms_date")
 * @ORM\Entity
 */
class CmsDate
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
     * @var \CmsArticle
     *
     * @ORM\ManyToOne(targetEntity="CmsArticle")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="article", referencedColumnName="id")
     * })
     */
    private $article;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;


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
     * Set article
     *
     * @param \Proyecto\PrincipalBundle\Entity\CmsArticle $article
     * @return CmsDate
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
     * Set date
     *
     * @param \DateTime $date
     * @return CmsDate
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
}