<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Patient;
use App\Entity\Survey   ;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ExporterController extends AbstractController
{
    /**
     * @Route("/exporter", name="exporter")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $Answers = $this->getDoctrine()
            ->getRepository(Answer::class)
            ->findAll();
        $Surveys = $this->getDoctrine()
            ->getRepository(Survey::class)
            ->findAll();

        return $this->render('exporter/index.html.twig', [
            'answers' => $Answers,
            'Surveys' => $Surveys,
        ]);
    }
    /**
     * @Route("/exporter/survey/{SurveyId}", name="exporterSurveyById")
     */
    public function exporterSurveyById($SurveyId)
    {
        $em = $this->getDoctrine()->getManager();
        $Answers = $this->getDoctrine()
            ->getRepository(Answer::class)
            ->findBy(['Survey' => $SurveyId])

        ;
        $filesystem = new Filesystem();
        $filesystem->touch('data/exported.csv');
        foreach ($Answers as $row) {
            $filesystem->appendToFile('data/exported.csv', $row->getAnswer()."\n");
        }

        return $this->redirect('/data/exported.csv');
    }

    /**
     * @Route("/exporter/answer/{answerId}", name="exporterTheAnswerById")
     */
    public function exporterTheAnswerById($answerId)
    {
        $em = $this->getDoctrine()->getManager();
        $Answers = $this->getDoctrine()
            ->getRepository(Answer::class)
            ->find($answerId)

        ;
        $filesystem = new Filesystem();
        $filesystem->touch('data/exportedS.csv');
        foreach ($Answers as $row) {
            $filesystem->appendToFile('data/exportedS.csv', $row->getAnswer());
        }

        return $this->redirect('/data/exportedS.csv');
    }


}
