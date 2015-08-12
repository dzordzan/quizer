<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * QuizApproach
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class QuizApproach
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $created;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="Quiz")
     */
    private $quiz;

    /**
     * @ORM\OneToMany(targetEntity="QuestionApproach", mappedBy="quizApproach", cascade={"persist"})
     **/
    private $questionsApproach;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function __construct()
    {
        $this->questionsApproach = new ArrayCollection();
    }
    /**
     * Set created
     *
     * @param \DateTime $created
     * @return QuizApproach
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return QuizApproach
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set quiz
     *
     * @param \AppBundle\Entity\Quiz $quiz
     * @return QuizApproach
     */
    public function setQuiz(\AppBundle\Entity\Quiz $quiz = null)
    {
        $this->quiz = $quiz;

        return $this;
    }

    /**
     * Get quiz
     *
     * @return \AppBundle\Entity\Quiz
     */
    public function getQuiz()
    {
        return $this->quiz;
    }

    /**
     * Add questionsApproach
     *
     * @param \AppBundle\Entity\QuestionApproach $questionsApproach
     * @return QuizApproach
     */
    public function addQuestionsApproach(\AppBundle\Entity\QuestionApproach $questionsApproach)
    {
        $questionsApproach->setQuizApproach($this);
        $this->questionsApproach[] = $questionsApproach;

        return $this;
    }

    /**
     * Remove questionsApproach
     *
     * @param \AppBundle\Entity\QuestionApproach $questionsApproach
     */
    public function removeQuestionsApproach(\AppBundle\Entity\QuestionApproach $questionsApproach)
    {
        $this->questionsApproach->removeElement($questionsApproach);
    }

    /**
     * Get questionsApproach
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuestionsApproach()
    {
        return $this->questionsApproach;
    }
}
