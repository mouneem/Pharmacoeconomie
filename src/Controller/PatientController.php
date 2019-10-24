<?php

namespace App\Controller;

use App\Entity\Patient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PatientController extends AbstractController
{
    /**
     * @Route("/patient", name="patient")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $patients = $this->getDoctrine()
            ->getRepository(Patient::class)
            ->findAll();
        return $this->render('patient/index.html.twig', [
            'controller_name' => 'PatientController',
            'patients' => $patients,
        ]);
    }

    /**
     * @Route("/patient/ajouter", name="addpatient")
     */
    public function ajouter_patient()
    {
        return $this->render('patient/ajouter.html.twig', [
            'controller_name' => 'PatientController',
        ]);
    }


    /**
     * @Route("/patient/ajouterAction", name="ajouterPatientAction")
     */
    public function ajouterPatientAction(Request $request)
    {
        $patient = new Patient();

        $patient->setNom($_POST['Nom']);
        $patient->setNumerotel($_POST['Numerotel']);
        $patient->setNatureMaladie($_POST['Nature_Maladie']);
        $patient->setNaissanceJour($_POST['naissance_jour']);
        $patient->setNaissanceMois($_POST['naissance_mois']);
        $patient->setNaissanceAnnee($_POST['naissance_annee']);
        $patient->setBiotherapieActuelle($_POST['biotherapie_actuelle']);
        $patient->setNumEntre($_POST['num_entre']);
        $patient->setNumInclu($_POST['num_inclu']);
        $patient->setSexe($_POST['sexe']);
        $patient->setDateInclusion($_POST['date_inclusion']);
        $patient->setPoids($_POST['poids']);
        $patient->setTaille($_POST['taille']);
        $patient->setNivEtude($_POST['niv_etude']);
        $patient->setSituationMat($_POST['situation_mat']);
        $patient->setNbEnf($_POST['nb_enf']);
        $patient->setType($_POST['type']);
        $patient->setProfession($_POST['profession']);
        $patient->setVilee($_POST['Vilee']);
        $patient->setRuralUrbain($_POST['rural_urbain']);
        $patient->setSalarie($_POST['salarie']);
        $patient->setRevenueDesMenages($_POST['revenue_des_menages']);


        $em = $this->getDoctrine()->getManager();
        $em->persist($patient);
        $em->flush();

        return $this->redirectToRoute('patient');
    }
}
