<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QuestionApproach
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class QuestionApproach
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
     * @ORM\ManyToOne(targetEntity="QuizApproach", inversedBy="questionsApproach")
     */
    private $quizApproach;

    /**
     * @ORM\ManyToOne(targetEntity="Answer")
     **/
    private $answer;

    /**
     * @ORM\ManyToOne(targetEntity="Question")
     */
    private $question;


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
     * Set quizApproach
     *
     * @param \AppBundle\Entity\QuizApproach $quizApproach
     * @return QuestionApproach
     */
    public function setQuizApproach(\AppBundle\Entity\QuizApproach $quizApproach = null)
    {
        $this->quizApproach = $quizApproach;

        return $this;
    }

    /**
     * Get quizApproach
     *
     * @return \AppBundle\Entity\QuizApproach
     */
    public function getQuizApproach()
    {
        return $this->quizApproach;
    }

    /**
     * Set answer
     *
     * @param \AppBundle\Entity\Answer $answer
     * @return QuestionApproach
     */
    public function setAnswer(\AppBundle\Entity\Answer $answer = null)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return \AppBundle\Entity\Answer
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Get the value of Qestion
     *
     * @return mixed
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set the value of Qestion
     *
     * @param mixed qestion
     *
     * @return self
     */
    public function setQuestion($qestion)
    {
        $this->question = $qestion;

        return $this;
    }

}
