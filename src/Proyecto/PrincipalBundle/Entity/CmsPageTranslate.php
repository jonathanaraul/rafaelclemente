<?php

namespace Proyecto\PrincipalBundle\Entity;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CmsPageTranslate
 *
 * @ORM\Table(name="cms_page_translate")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class CmsPageTranslate
{
    /**
     * @var integer
     *
     * @ORM\Column(name="page_translate_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $pageTranslateId;

    /**
     * @var integer
     *
     * @ORM\Column(name="page_id", type="integer", nullable=false)
     */
    private $pageId;

    /**
     * @var integer
     *
     * @ORM\Column(name="lang_id", type="integer", nullable=false)
     */
    private $langId;

    /**
     * @var integer
     *
     * @ORM\Column(name="media_id", type="integer", nullable=false)
     */
    private $mediaId;

    /**
     * @var integer
     *
     * @ORM\Column(name="background_id", type="integer", nullable=false)
     */
    private $backgroundId;

    /**
     * @var integer
     *
     * @ORM\Column(name="theme_id", type="integer", nullable=false)
     */
    private $themeId;

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
     * @var integer
     *
     * @ORM\Column(name="spacer", type="integer", nullable=true)
     */
    private $spacer;

    /**
     * @var string
     *
     * @ORM\Column(name="keywords", type="string", length=255, nullable=false)
     */
    private $keywords;

    /**
     * @var string
     *
     * @ORM\Column(name="description_meta", type="string", length=255, nullable=false)
     */
    private $descriptionMeta;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="upper_text", type="string", length=255, nullable=false)
     */
    private $upperText;

    /**
     * @var string
     *
     * @ORM\Column(name="lower_text", type="string", length=255, nullable=false)
     */
    private $lowerText;

    /**
     * @var string
     *
     * @ORM\Column(name="pdf", type="string", length=255, nullable=true)
     */
    private $pdf;

    /**
     * @var string
     *
     * @ORM\Column(name="friendly_name", type="string", length=255, nullable=false)
     */
    private $friendlyName;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=false)
     */
    private $content;

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
         * @Assert\File(maxSize="6000000")
         */
        private $file;
        private $temp;


    /**
     * Get pageTranslateId
     *
     * @return integer 
     */
    public function getPageTranslateId()
    {
        return $this->pageTranslateId;
    }

    /**
     * Set pageId
     *
     * @param integer $pageId
     * @return CmsPageTranslate
     */
    public function setPageId($pageId)
    {
        $this->pageId = $pageId;
    
        return $this;
    }

    /**
     * Get pageId
     *
     * @return integer 
     */
    public function getPageId()
    {
        return $this->pageId;
    }

    /**
     * Set langId
     *
     * @param integer $langId
     * @return CmsPageTranslate
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
     * Set mediaId
     *
     * @param integer $mediaId
     * @return CmsPageTranslate
     */
    public function setMediaId($mediaId)
    {
        $this->mediaId = $mediaId;
    
        return $this;
    }

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
     * Set backgroundId
     *
     * @param integer $backgroundId
     * @return CmsPageTranslate
     */
    public function setBackgroundId($backgroundId)
    {
        $this->backgroundId = $backgroundId;
    
        return $this;
    }

    /**
     * Get backgroundId
     *
     * @return integer 
     */
    public function getBackgroundId()
    {
        return $this->backgroundId;
    }

    /**
     * Set themeId
     *
     * @param integer $themeId
     * @return CmsPageTranslate
     */
    public function setThemeId($themeId)
    {
        $this->themeId = $themeId;
    
        return $this;
    }

    /**
     * Get themeId
     *
     * @return integer 
     */
    public function getThemeId()
    {
        return $this->themeId;
    }

    /**
     * Set rank
     *
     * @param integer $rank
     * @return CmsPageTranslate
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
     * @return CmsPageTranslate
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
     * @return CmsPageTranslate
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
     * Set spacer
     *
     * @param integer $spacer
     * @return CmsPageTranslate
     */
    public function setSpacer($spacer)
    {
        $this->spacer = $spacer;
    
        return $this;
    }

    /**
     * Get spacer
     *
     * @return integer 
     */
    public function getSpacer()
    {
        return $this->spacer;
    }

    /**
     * Set keywords
     *
     * @param string $keywords
     * @return CmsPageTranslate
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    
        return $this;
    }

    /**
     * Get keywords
     *
     * @return string 
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set descriptionMeta
     *
     * @param string $descriptionMeta
     * @return CmsPageTranslate
     */
    public function setDescriptionMeta($descriptionMeta)
    {
        $this->descriptionMeta = $descriptionMeta;
    
        return $this;
    }

    /**
     * Get descriptionMeta
     *
     * @return string 
     */
    public function getDescriptionMeta()
    {
        return $this->descriptionMeta;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return CmsPageTranslate
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return CmsPageTranslate
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
     * Set upperText
     *
     * @param string $upperText
     * @return CmsPageTranslate
     */
    public function setUpperText($upperText)
    {
        $this->upperText = $upperText;
    
        return $this;
    }

    /**
     * Get upperText
     *
     * @return string 
     */
    public function getUpperText()
    {
        return $this->upperText;
    }

    /**
     * Set lowerText
     *
     * @param string $lowerText
     * @return CmsPageTranslate
     */
    public function setLowerText($lowerText)
    {
        $this->lowerText = $lowerText;
    
        return $this;
    }

    /**
     * Get lowerText
     *
     * @return string 
     */
    public function getLowerText()
    {
        return $this->lowerText;
    }

    /**
     * Set pdf
     *
     * @param string $pdf
     * @return CmsPageTranslate
     */
    public function setPdf($pdf)
    {
        $this->pdf = $pdf;
    
        return $this;
    }

    /**
     * Get pdf
     *
     * @return string 
     */
    public function getPdf()
    {
        return $this->pdf;
    }

    /**
     * Set friendlyName
     *
     * @param string $friendlyName
     * @return CmsPageTranslate
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
     * Set description
     *
     * @param string $description
     * @return CmsPageTranslate
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return CmsPageTranslate
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return CmsPageTranslate
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
     * @return CmsPageTranslate
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
     * @return CmsPageTranslate
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
     * @return CmsPageTranslate
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
        
        /**
         * Sets file.
         *
         * @param UploadedFile $file
         */
        public function setFile(UploadedFile $file = null) {
                $this -> file = $file;
                // check if we have an old image path
                if (isset($this -> pdf)) {
                        // store the old name to delete after the update
                        $this -> temp = $this -> pdf;
                        $this -> pdf = null;
                } else {
                        $this -> pdf = 'inicial';
                }
        }

        /**
         * @ORM\PrePersist()
         * @ORM\PreUpdate()
         */
        public function preUpload() {
                if (null !== $this -> getFile()) {
                        // do whatever you want to generate a unique name
                        $filename = sha1(uniqid(mt_rand(), true));
                        $this -> pdf = $filename . '.' . $this -> getFile() -> guessExtension();
                }
        }

        /**
         * @ORM\PostPersist()
         * @ORM\PostUpdate()
         */
        public function upload() {
                if (null === $this -> getFile()) {
                        return;
                }

                // if there is an error when moving the file, an exception will
                // be automatically thrown by move(). This will properly prevent
                // the entity from being persisted to the database on error
                $this -> getFile() -> move($this -> getUploadRootDir(), $this -> pdf);

                // check if we have an old image
                if (isset($this -> temp)) {
                        // delete the old image
                        unlink($this -> getUploadRootDir() . '/' . $this -> temp);
                        // clear the temp image path
                        $this -> temp = null;
                }
                $this -> file = null;
        }

        /**
         * @ORM\PostRemove()
         */
        public function removeUpload() {
                if ($file = $this -> getAbsolutePath()) {
                        unlink($file);
                }
        }
                /**
         * Get file.
         *
         * @return UploadedFile
         */
        public function getFile() {
                return $this -> file;
        }

        public function getAbsolutePath() {
                return null === $this -> pdf ? null : $this -> getUploadRootDir() . '/' . $this -> pdf;
        }

        public function getWebPath() {
                return null === $this -> pdf ? null : $this -> getUploadDir() . '/' . $this -> pdf;
        }

        protected function getUploadRootDir() {
                // the absolute directory path where uploaded
                // documents should be saved
                return __DIR__ . '/../../../../web/' . $this -> getUploadDir();
        }

        protected function getUploadDir() {
                $directorio = 'pdf';
                return 'uploads/' . $directorio;
        }
}