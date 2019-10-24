<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Patient;
use App\Entity\Survey;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



class SurveyController extends AbstractController
{
    /**
     * @Route("/survey", name="survey")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $Surveys = $this->getDoctrine()
            ->getRepository(Survey::class)
            ->findAll();
        return $this->render('survey/index.html.twig', [
            'Surveys' => $Surveys,
        ]);
    }

    /**
     * @Route("/survey/ajouter", name="ajouter")
     */
    public function ajouter()
    {
        return $this->render('survey/ajouter.html.twig', [
            'controller_name' => 'PatientController',
        ]);
    }


    /**
     * @Route("/survey/ajouterAction", name="ajouterAction")
     */
    public function ajouterAction(Request $request)
    {
        $survey = new Survey();
        $survey->setTitre($_POST['Titre']);
        $survey->setLink($_POST['Lien']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($survey);
        $em->flush();

        return $this->redirectToRoute('survey');
    }


    /**
     * @Route("/survey/coutdirect", name="coutdirect")
     */
    public function Survey_coutdirect()
    {
        $em = $this->getDoctrine()->getManager();
        $Patients = $this->getDoctrine()
            ->getRepository(Patient::class)
            ->findAll();
        return $this->render('survey/Questions/coutdirect.html.twig', [
            'patients' => $Patients,
        ]);

    }

    /**
     * @Route("/survey/coutindirect", name="coutindirect")
     */
    public function Survey_coutindirect()
    {
        $em = $this->getDoctrine()->getManager();
        $Patients = $this->getDoctrine()
            ->getRepository(Patient::class)
            ->findAll();
        return $this->render('survey/Questions/coutindirect.html.twig', [
            'patients' => $Patients,
        ]);
    }




    /**
     * @Route("/survey/coutdirect/calcul", name="coutdirectCalcul")
     */
    public function Survey_coutdirect_Calcul()
    {
        $Answer = new Answer();

        $Patient = new Patient();
        $em = $this->getDoctrine()->getManager();
        $Patient = $this->getDoctrine()
            ->getRepository(Patient::class)
            ->find($_POST["patient_id"]);
        $Answer->setPatient($Patient);


        $Survey = new Survey();
        $em = $this->getDoctrine()->getManager();
        $Survey = $this->getDoctrine()
            ->getRepository(Survey::class)
            ->find(1);
        $Answer->setSurvey($Survey);

        $Answer->setDate(date("D M d, Y G:i"));

        $line = $Patient->getNom().';'.$Patient->getNumerotel().';'.$Patient->getNatureMaladie().';'.$Patient->getNaissanceJour().';'.$Patient->getNaissanceMois().';'.$Patient->getNaissanceAnnee().';'.$Patient->getBiotherapieActuelle().';'.$Patient->getNumEntre().';'.$Patient->getNumInclu().';'.$Patient->getSexe().';'.$Patient->getDateInclusion().';'.$Patient->getPoids().';'.$Patient->getTaille().';'.$Patient->getNivEtude().';'.$Patient->getSituationMat().';'.$Patient->getNbEnf().';'.$Patient->getType().';'.$Patient->getProfession().';'.$Patient->getVilee().';'.$Patient->getRuralUrbain().';'.$Patient->getSalarie().';'.$Patient->getRevenueDesMenages().';'.



            $_POST["Privees_RhumatologueConsultationNombre"] .';'.
        $_POST["Privees_RhumatologueConsultationPrix"] .';'.
            (string)((int)$_POST["Privees_RhumatologueConsultationNombre"] * (int)$_POST["Privees_RhumatologueConsultationPrix"]) .';'.

        $_POST["Privees_InternistConsultationNombre"] .';'.
        $_POST["Privees_InternistConsultationPrix"] .';'.
            (string)((int)$_POST["Privees_InternistConsultationNombre"] * (int)$_POST["Privees_InternistConsultationPrix"]) .';'.

        $_POST["Privees_GeneralisteConsultationNombre"] .';'.
        $_POST["Privees_GeneralisteConsultationPrix"] .';'.
            (string)((int)$_POST["Privees_GeneralisteConsultationNombre"] * (int)$_POST["Privees_GeneralisteConsultationPrix"]) .';'.

        $_POST["Privees_AutresConsultationNombre"] .';'.
        $_POST["Privees_AutresConsultationPrix"] .';'.
            (string)((int)$_POST["Privees_AutresConsultationNombre"] * (int)$_POST["Privees_AutresConsultationPrix"]).";" ;

         #Coûtsdes Consultations en privé
            $cnst_prv = ((int)$_POST["Privees_AutresConsultationNombre"] * (int)$_POST["Privees_AutresConsultationPrix"]+(int)$_POST["Privees_GeneralisteConsultationNombre"] * (int)$_POST["Privees_GeneralisteConsultationPrix"]+(int)$_POST["Privees_InternistConsultationNombre"] * (int)$_POST["Privees_InternistConsultationPrix"]+(int)$_POST["Privees_RhumatologueConsultationNombre"] * (int)$_POST["Privees_RhumatologueConsultationPrix"]);


        $line = $line .(string)$cnst_prv.";";
        $line = $line . $_POST["Privees_rembourse_consultation"] .';'.
        $_POST["Privees_rembourse_consultation_cout"] .';';

        #Coût des consultations en privé payé pst og
        if ( $_POST["Privees_rembourse_consultation"] == 'Oui'){
            $line = $line.(string)((int)$cnst_prv - (int)$_POST["Privees_rembourse_consultation_cout"]).';';

        }else
            {$line = $line.(string)$cnst_prv ; }

        $line = $line.$_POST["Consultation_Publics_Mut_Rhumato_consultation"] .';'.
        $_POST["Consultation_Publics_Mut_Rhumato_prix"] .';'.
            (string)((int)$_POST["Consultation_Publics_Mut_Rhumato_consultation"] * (int)$_POST["Consultation_Publics_Mut_Rhumato_prix"]) .';'.

        $_POST["Consultation_Publics_Mut_Professeur_consultation"] .';'.
        $_POST["Consultation_Publics_Mut_Professeur_prix"] .';'.
            (string)((int)$_POST["Consultation_Publics_Mut_Professeur_consultation"] * (int)$_POST["Consultation_Publics_Mut_Professeur_prix"]) .';'.

        $_POST["Consultation_Publics_Mut_Generaliste_consultation"] .';'.
        $_POST["Consultation_Publics_Mut_Generaliste_prix"] .';'.
            (string)((int)$_POST["Consultation_Publics_Mut_Generaliste_consultation"] * (int)$_POST["Consultation_Publics_Mut_Generaliste_prix"]) .';'.

        $_POST["Consultation_Publics_Mut_Psychiatre_consultation"] .';'.
        $_POST["Consultation_Publics_Mut_Psychiatre_prix"] .';'.
            (string)((int)$_POST["Consultation_Publics_Mut_Psychiatre_consultation"] * (int)$_POST["Consultation_Publics_Mut_Psychiatre_prix"]) .';'.

        $_POST["Consultation_Publics_Pay_Rhumato_consultation"] .';'.
        $_POST["Consultation_Publics_Pay_Rhumato_prix"] .';'.
            (string)((int)$_POST["Consultation_Publics_Pay_Rhumato_consultation"] * (int)$_POST["Consultation_Publics_Pay_Rhumato_prix"]) .';'.


        $_POST["Consultation_Publics_Pay_Professeur_consultation"] .';'.
        $_POST["Consultation_Publics_Pay_Professeur_prix"] .';'.
            (string)((int)$_POST["Consultation_Publics_Pay_Professeur_consultation"] * (int)$_POST["Consultation_Publics_Pay_Professeur_prix"]) .';'.

        $_POST["Consultation_Publics_Pay_Generaliste_consultation"] .';'.
        $_POST["Consultation_Publics_Pay_Generaliste_prix"] .';'.
            (string)((int)$_POST["Consultation_Publics_Pay_Generaliste_consultation"] * (int)$_POST["Consultation_Publics_Pay_Generaliste_prix"]) .';'.

        $_POST["Consultation_Publics_Pay_Psychiatre_consultation"] .';'.
        $_POST["Consultation_Publics_Pay_Psychiatre_prix"].';'.
            (string)((int)$_POST["Consultation_Publics_Pay_Psychiatre_consultation"] * (int)$_POST["Consultation_Publics_Pay_Psychiatre_prix"]) .';';


        #Coûtdes Consultations en public:
            $const_pub = (int)$_POST["Consultation_Publics_Mut_Rhumato_consultation"] * (int)$_POST["Consultation_Publics_Mut_Rhumato_prix"]+(int)$_POST["Consultation_Publics_Mut_Professeur_consultation"] * (int)$_POST["Consultation_Publics_Mut_Professeur_prix"]+(int)$_POST["Consultation_Publics_Mut_Generaliste_consultation"] * (int)$_POST["Consultation_Publics_Mut_Generaliste_prix"]+$_POST["Consultation_Publics_Mut_Psychiatre_consultation"] * (int)$_POST["Consultation_Publics_Mut_Psychiatre_prix"]+(int)$_POST["Consultation_Publics_Pay_Rhumato_consultation"] * (int)$_POST["Consultation_Publics_Pay_Rhumato_prix"]+(int)$_POST["Consultation_Publics_Pay_Professeur_consultation"] * (int)$_POST["Consultation_Publics_Pay_Professeur_prix"]+(int)$_POST["Consultation_Publics_Pay_Generaliste_consultation"] * (int)$_POST["Consultation_Publics_Pay_Generaliste_prix"]+$_POST["Consultation_Publics_Pay_Psychiatre_consultation"] * (int)$_POST["Consultation_Publics_Pay_Psychiatre_prix"];
        $line = $line.$const_pub.";".
            #Coûtdes consultations en Pc payé par l’O.G:
            (string)($const_pub - (int)$_POST["Consultation_Publics_Pay_par_patient"]).';';

        $line = $line.
            $_POST["hospitalisation_motif_pub"] .';'.
            $_POST["hospitalisation_public_jour_nb"] .';'.
            $_POST["hospitalisation_public_jour_prix"] .';'.
            $_POST["hospitalisation_public_nuit_nb"] .';'.
            $_POST["hospitalisation_public_nuit_prix"] .';';
        #Coûtdes hospitalisationsen public
            $hosp_pub = (int)$_POST["hospitalisation_public_jour_nb"]*(int)$_POST["hospitalisation_public_jour_prix"]+(int)$_POST["hospitalisation_public_nuit_nb"]*(int)$_POST["hospitalisation_public_nuit_prix"];
        $line = $line.
            (string)$hosp_pub.";".
            $_POST["hospitalisation_public_cout_paye_par_pat"].";".
            #Coûtdes hospitalisationsen Pc payépar l’O.G
            (string)($hosp_pub-(int)$_POST["hospitalisation_public_cout_paye_par_pat"]).";".

//            prv
            $_POST["hospitalisation_motif_prv"] .';'.
            $_POST["hospitalisation_prv_jour_nb"] .';'.
            $_POST["hospitalisation_prv_jour_prix"] .';'.
            $_POST["hospitalisation_prv_nuit_nb"] .';'.
            $_POST["hospitalisation_prv_nuit_prix"] .';';
            $hosp_prv = (int)$_POST["hospitalisation_prv_jour_nb"]*(int)$_POST["hospitalisation_prv_jour_prix"]+(int)$_POST["hospitalisation_prv_nuit_nb"]*(int)$_POST["hospitalisation_prv_nuit_prix"];
        $line = $line.
            (string)$hosp_prv.";".
            $_POST["hospitalsation_prv_rembourse"] .';'.
            $_POST["hospitalsation_prv_rembourse_prix_paye_par_patient"] .';'.
            (string)($hosp_prv-(int)$_POST["hospitalsation_prv_rembourse_prix_paye_par_patient"]).";";

        $line = $line.
            $_POST["bilan_pub_mut_main_nb"] .';'.
            $_POST["bilan_pub_mut_main_prix"] .';'.
            (string)((int)$_POST["bilan_pub_mut_main_nb"]*(int)$_POST["bilan_pub_mut_main_prix"]) .';'.

            $_POST["bilan_pub_mut_Poignet_nb"] .';'.
            $_POST["bilan_pub_mut_Poignet_prix"] .';'.
            (string)((int)$_POST["bilan_pub_mut_Poignet_nb"]*(int)$_POST["bilan_pub_mut_Poignet_prix"]) .';'.

            $_POST["bilan_pub_mut_Coude_nb"] .';'.
            $_POST["bilan_pub_mut_Coude_prix"] .';'.
            (string)((int)$_POST["bilan_pub_mut_Coude_nb"]*(int)$_POST["bilan_pub_mut_Coude_prix"]) .';'.

            $_POST["bilan_pub_mut_Cheville_nb"] .';'.
            $_POST["bilan_pub_mut_Cheville_prix"] .';'.
            (string)((int)$_POST["bilan_pub_mut_Cheville_nb"]*(int)$_POST["bilan_pub_mut_Cheville_prix"]) .';'.

            $_POST["bilan_pub_mut_Jambe_nb"] .';'.
            $_POST["bilan_pub_mut_Jambe_prix"] .';'.
            (string)((int)$_POST["bilan_pub_mut_Cheville_nb"]*(int)$_POST["bilan_pub_mut_Cheville_prix"]) .';'.

            $_POST["bilan_pub_mut_Genou_nb"] .';'.
            $_POST["bilan_pub_mut_Genou_prix"] .';'.
            (string)((int)$_POST["bilan_pub_mut_Genou_nb"]*(int)$_POST["bilan_pub_mut_Genou_prix"]) .';'.

            $_POST["bilan_pub_mut_Cuisse_nb"] .';'.
            $_POST["bilan_pub_mut_Cuisse_prix"] .';'.
            (string)((int)$_POST["bilan_pub_mut_Cuisse_nb"]*(int)$_POST["bilan_pub_mut_Cuisse_prix"]) .';'.

            $_POST["bilan_pub_mut_Pied_nb"] .';'.
            $_POST["bilan_pub_mut_Pied_prix"] .';'.
            (string)((int)$_POST["bilan_pub_mut_Pied_nb"]*(int)$_POST["bilan_pub_mut_Pied_prix"]) .';'.

            $_POST["bilan_pub_mut_Hanche_nb"] .';'.
            $_POST["bilan_pub_mut_Hanche_prix"] .';'.
            (string)((int)$_POST["bilan_pub_mut_Hanche_nb"]*(int)$_POST["bilan_pub_mut_Hanche_prix"]) .';'.

            $_POST["bilan_pub_mut_Bassin_nb"] .';'.
            $_POST["bilan_pub_mut_Bassin_prix"] .';'.
            (string)((int)$_POST["bilan_pub_mut_Bassin_nb"]*(int)$_POST["bilan_pub_mut_Bassin_prix"]) .';'.

            $_POST["bilan_pub_mut_Rachis_D_nb"] .';'.
            $_POST["bilan_pub_mut_Rachis_D_prix"] .';'.
            (string)((int)$_POST["bilan_pub_mut_Rachis_D_nb"]*(int)$_POST["bilan_pub_mut_Rachis_D_prix"]) .';'.

            $_POST["bilan_pub_mut_Rachis_L_nb"] .';'.
            $_POST["bilan_pub_mut_Rachis_L_prix"] .';'.
            (string)((int)$_POST["bilan_pub_mut_Rachis_L_nb"]*(int)$_POST["bilan_pub_mut_Rachis_L_prix"]) .';'.

            $_POST["bilan_pub_mut_Epaule_nb"] .';'.
            $_POST["bilan_pub_mut_Epaule_prix"] .';'.
            (string)((int)$_POST["bilan_pub_mut_Epaule_nb"]*(int)$_POST["bilan_pub_mut_Epaule_prix"]) .';'.

            $_POST["bilan_pub_mut_Poumon_nb"] .';'.
            $_POST["bilan_pub_mut_Poumon_prix"] .';'.
            (string)((int)$_POST["bilan_pub_mut_Poumon_nb"]*(int)$_POST["bilan_pub_mut_Poumon_prix"]) .';'.

            $_POST["bilan_pub_mut_DMO_Non_Remboursalbe_nb"] .';'.
            $_POST["bilan_pub_mut_DMO_Non_Remboursalbe_prix"] .';'.
            (string)((int)$_POST["bilan_pub_mut_DMO_Non_Remboursalbe_nb"]*(int)$_POST["bilan_pub_mut_DMO_Non_Remboursalbe_prix"]) .';'.

            $_POST["bilan_pub_mut_Echographie_Main_nb"] .';'.
            $_POST["bilan_pub_mut_Echographie_Main_prix"] .';'.
            (string)((int)$_POST["bilan_pub_mut_Echographie_Main_nb"]*(int)$_POST["bilan_pub_mut_Echographie_Main_prix"]) .';'.

            $_POST["bilan_pub_mut_Echographie_Peid_nb"] .';'.
            $_POST["bilan_pub_mut_Echographie_Peid_prix"] .';'.
            (string)((int)$_POST["bilan_pub_mut_Echographie_Peid_nb"]*(int)$_POST["bilan_pub_mut_Echographie_Peid_prix"]) .';'.

            $_POST["bilan_pub_mut_Echographie_enthese_nb"] .';'.
            $_POST["bilan_pub_mut_Echographie_enthese_prix"] .';'.
            (string)((int)$_POST["bilan_pub_mut_Echographie_enthese_nb"]*(int)$_POST["bilan_pub_mut_Echographie_enthese_prix"]) .';'.

            $_POST["bilan_pub_mut_Echographie_Hanche_nb"] .';'.
            $_POST["bilan_pub_mut_Echographie_Hanche_prix"] .';'.
            (string)((int)$_POST["bilan_pub_mut_Echographie_Hanche_nb"]*(int)$_POST["bilan_pub_mut_Echographie_Hanche_prix"]) .';'.

            $_POST["bilan_pub_mut_IRM_nb"] .';'.
            $_POST["bilan_pub_mut_IRM_prix"] .';'.
            (string)((int)$_POST["bilan_pub_mut_IRM_nb"]*(int)$_POST["bilan_pub_mut_IRM_prix"]) .';'.

            $_POST["bilan_pub_mut_Scanner_nb"] .';'.
            $_POST["bilan_pub_mut_Scanner_prix"] .';'.
            (string)((int)$_POST["bilan_pub_mut_Scanner_nb"]*(int)$_POST["bilan_pub_mut_Scanner_prix"]) .';'.


            $_POST["bilan_pub_pay_main_nb"] .';'.
            $_POST["bilan_pub_pay_main_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_main_nb"]*(int)$_POST["bilan_pub_pay_main_prix"]) .';'.

            $_POST["bilan_pub_pay_Poignet_nb"] .';'.
            $_POST["bilan_pub_pay_Poignet_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Poignet_nb"]*(int)$_POST["bilan_pub_pay_Poignet_prix"]) .';'.

            $_POST["bilan_pub_pay_Coude_nb"] .';'.
            $_POST["bilan_pub_pay_Coude_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Coude_nb"]*(int)$_POST["bilan_pub_pay_Coude_prix"]) .';'.

            $_POST["bilan_pub_pay_Cheville_nb"] .';'.
            $_POST["bilan_pub_pay_Cheville_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Cheville_nb"]*(int)$_POST["bilan_pub_pay_Cheville_prix"]) .';'.

            $_POST["bilan_pub_pay_Jambe_nb"] .';'.
            $_POST["bilan_pub_pay_Jambe_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Cheville_nb"]*(int)$_POST["bilan_pub_pay_Cheville_prix"]) .';'.

            $_POST["bilan_pub_pay_Genou_nb"] .';'.
            $_POST["bilan_pub_pay_Genou_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Genou_nb"]*(int)$_POST["bilan_pub_pay_Genou_prix"]) .';'.

            $_POST["bilan_pub_pay_Cuisse_nb"] .';'.
            $_POST["bilan_pub_pay_Cuisse_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Cuisse_nb"]*(int)$_POST["bilan_pub_pay_Cuisse_prix"]) .';'.

            $_POST["bilan_pub_pay_Pied_nb"] .';'.
            $_POST["bilan_pub_pay_Pied_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Pied_nb"]*(int)$_POST["bilan_pub_pay_Pied_prix"]) .';'.

            $_POST["bilan_pub_pay_Hanche_nb"] .';'.
            $_POST["bilan_pub_pay_Hanche_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Hanche_nb"]*(int)$_POST["bilan_pub_pay_Hanche_prix"]) .';'.

            $_POST["bilan_pub_pay_Bassin_nb"] .';'.
            $_POST["bilan_pub_pay_Bassin_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Bassin_nb"]*(int)$_POST["bilan_pub_pay_Bassin_prix"]) .';'.

            $_POST["bilan_pub_pay_Rachis_D_nb"] .';'.
            $_POST["bilan_pub_pay_Rachis_D_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Rachis_D_nb"]*(int)$_POST["bilan_pub_pay_Rachis_D_prix"]) .';'.

            $_POST["bilan_pub_pay_Rachis_L_nb"] .';'.
            $_POST["bilan_pub_pay_Rachis_L_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Rachis_L_nb"]*(int)$_POST["bilan_pub_pay_Rachis_L_prix"]) .';'.

            $_POST["bilan_pub_pay_Epaule_nb"] .';'.
            $_POST["bilan_pub_pay_Epaule_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Epaule_nb"]*(int)$_POST["bilan_pub_pay_Epaule_prix"]) .';'.

            $_POST["bilan_pub_pay_Poumon_nb"] .';'.
            $_POST["bilan_pub_pay_Poumon_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Poumon_nb"]*(int)$_POST["bilan_pub_pay_Poumon_prix"]) .';'.

            $_POST["bilan_pub_pay_DMO_Non_Remboursalbe_nb"] .';'.
            $_POST["bilan_pub_pay_DMO_Non_Remboursalbe_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_DMO_Non_Remboursalbe_nb"]*(int)$_POST["bilan_pub_pay_DMO_Non_Remboursalbe_prix"]) .';'.

            $_POST["bilan_pub_pay_Echographie_Main_nb"] .';'.
            $_POST["bilan_pub_pay_Echographie_Main_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Echographie_Main_nb"]*(int)$_POST["bilan_pub_pay_Echographie_Main_prix"]) .';'.

            $_POST["bilan_pub_pay_Echographie_Peid_nb"] .';'.
            $_POST["bilan_pub_pay_Echographie_Peid_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Echographie_Peid_nb"]*(int)$_POST["bilan_pub_pay_Echographie_Peid_prix"]) .';'.

            $_POST["bilan_pub_pay_Echographie_enthese_nb"] .';'.
            $_POST["bilan_pub_pay_Echographie_enthese_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Echographie_enthese_nb"]*(int)$_POST["bilan_pub_pay_Echographie_enthese_prix"]) .';'.

            $_POST["bilan_pub_pay_Echographie_Hanche_nb"] .';'.
            $_POST["bilan_pub_pay_Echographie_Hanche_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Echographie_Hanche_nb"]*(int)$_POST["bilan_pub_pay_Echographie_Hanche_prix"]) .';'.

            $_POST["bilan_pub_pay_IRM_nb"] .';'.
            $_POST["bilan_pub_pay_IRM_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_IRM_nb"]*(int)$_POST["bilan_pub_pay_IRM_prix"]) .';'.

            $_POST["bilan_pub_pay_Scanner_nb"] .';'.
            $_POST["bilan_pub_pay_Scanner_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Scanner_nb"]*(int)$_POST["bilan_pub_pay_Scanner_prix"]) .';'.


        $_POST["bilan_pub_mut_main_nb"] .';'.
        $_POST["bilan_pub_mut_main_prix"] .';'.
        (string)((int)$_POST["bilan_pub_mut_main_nb"]*(int)$_POST["bilan_pub_mut_main_prix"]) .';'.

        $_POST["bilan_pub_mut_Poignet_nb"] .';'.
        $_POST["bilan_pub_mut_Poignet_prix"] .';'.
        (string)((int)$_POST["bilan_pub_mut_Poignet_nb"]*(int)$_POST["bilan_pub_mut_Poignet_prix"]) .';'.

        $_POST["bilan_pub_mut_Coude_nb"] .';'.
        $_POST["bilan_pub_mut_Coude_prix"] .';'.
        (string)((int)$_POST["bilan_pub_mut_Coude_nb"]*(int)$_POST["bilan_pub_mut_Coude_prix"]) .';'.

        $_POST["bilan_pub_mut_Cheville_nb"] .';'.
        $_POST["bilan_pub_mut_Cheville_prix"] .';'.
        (string)((int)$_POST["bilan_pub_mut_Cheville_nb"]*(int)$_POST["bilan_pub_mut_Cheville_prix"]) .';'.

        $_POST["bilan_pub_mut_Jambe_nb"] .';'.
        $_POST["bilan_pub_mut_Jambe_prix"] .';'.
        (string)((int)$_POST["bilan_pub_mut_Cheville_nb"]*(int)$_POST["bilan_pub_mut_Cheville_prix"]) .';'.

        $_POST["bilan_pub_mut_Genou_nb"] .';'.
        $_POST["bilan_pub_mut_Genou_prix"] .';'.
        (string)((int)$_POST["bilan_pub_mut_Genou_nb"]*(int)$_POST["bilan_pub_mut_Genou_prix"]) .';'.

        $_POST["bilan_pub_mut_Cuisse_nb"] .';'.
        $_POST["bilan_pub_mut_Cuisse_prix"] .';'.
        (string)((int)$_POST["bilan_pub_mut_Cuisse_nb"]*(int)$_POST["bilan_pub_mut_Cuisse_prix"]) .';'.

        $_POST["bilan_pub_mut_Pied_nb"] .';'.
        $_POST["bilan_pub_mut_Pied_prix"] .';'.
        (string)((int)$_POST["bilan_pub_mut_Pied_nb"]*(int)$_POST["bilan_pub_mut_Pied_prix"]) .';'.

        $_POST["bilan_pub_mut_Hanche_nb"] .';'.
        $_POST["bilan_pub_mut_Hanche_prix"] .';'.
        (string)((int)$_POST["bilan_pub_mut_Hanche_nb"]*(int)$_POST["bilan_pub_mut_Hanche_prix"]) .';'.

        $_POST["bilan_pub_mut_Bassin_nb"] .';'.
        $_POST["bilan_pub_mut_Bassin_prix"] .';'.
        (string)((int)$_POST["bilan_pub_mut_Bassin_nb"]*(int)$_POST["bilan_pub_mut_Bassin_prix"]) .';'.

        $_POST["bilan_pub_mut_Rachis_D_nb"] .';'.
        $_POST["bilan_pub_mut_Rachis_D_prix"] .';'.
        (string)((int)$_POST["bilan_pub_mut_Rachis_D_nb"]*(int)$_POST["bilan_pub_mut_Rachis_D_prix"]) .';'.

        $_POST["bilan_pub_mut_Rachis_L_nb"] .';'.
        $_POST["bilan_pub_mut_Rachis_L_prix"] .';'.
        (string)((int)$_POST["bilan_pub_mut_Rachis_L_nb"]*(int)$_POST["bilan_pub_mut_Rachis_L_prix"]) .';'.

        $_POST["bilan_pub_mut_Epaule_nb"] .';'.
        $_POST["bilan_pub_mut_Epaule_prix"] .';'.
        (string)((int)$_POST["bilan_pub_mut_Epaule_nb"]*(int)$_POST["bilan_pub_mut_Epaule_prix"]) .';'.

        $_POST["bilan_pub_mut_Poumon_nb"] .';'.
        $_POST["bilan_pub_mut_Poumon_prix"] .';'.
        (string)((int)$_POST["bilan_pub_mut_Poumon_nb"]*(int)$_POST["bilan_pub_mut_Poumon_prix"]) .';'.

        $_POST["bilan_pub_mut_DMO_Non_Remboursalbe_nb"] .';'.
        $_POST["bilan_pub_mut_DMO_Non_Remboursalbe_prix"] .';'.
        (string)((int)$_POST["bilan_pub_mut_DMO_Non_Remboursalbe_nb"]*(int)$_POST["bilan_pub_mut_DMO_Non_Remboursalbe_prix"]) .';'.

        $_POST["bilan_pub_mut_Echographie_Main_nb"] .';'.
        $_POST["bilan_pub_mut_Echographie_Main_prix"] .';'.
        (string)((int)$_POST["bilan_pub_mut_Echographie_Main_nb"]*(int)$_POST["bilan_pub_mut_Echographie_Main_prix"]) .';'.

        $_POST["bilan_pub_mut_Echographie_Peid_nb"] .';'.
        $_POST["bilan_pub_mut_Echographie_Peid_prix"] .';'.
        (string)((int)$_POST["bilan_pub_mut_Echographie_Peid_nb"]*(int)$_POST["bilan_pub_mut_Echographie_Peid_prix"]) .';'.

        $_POST["bilan_pub_mut_Echographie_enthese_nb"] .';'.
        $_POST["bilan_pub_mut_Echographie_enthese_prix"] .';'.
        (string)((int)$_POST["bilan_pub_mut_Echographie_enthese_nb"]*(int)$_POST["bilan_pub_mut_Echographie_enthese_prix"]) .';'.

        $_POST["bilan_pub_mut_Echographie_Hanche_nb"] .';'.
        $_POST["bilan_pub_mut_Echographie_Hanche_prix"] .';'.
        (string)((int)$_POST["bilan_pub_mut_Echographie_Hanche_nb"]*(int)$_POST["bilan_pub_mut_Echographie_Hanche_prix"]) .';'.

        $_POST["bilan_pub_mut_IRM_nb"] .';'.
        $_POST["bilan_pub_mut_IRM_prix"] .';'.
        (string)((int)$_POST["bilan_pub_mut_IRM_nb"]*(int)$_POST["bilan_pub_mut_IRM_prix"]) .';'.

        $_POST["bilan_pub_mut_Scanner_nb"] .';'.
        $_POST["bilan_pub_mut_Scanner_prix"] .';'.
        (string)((int)$_POST["bilan_pub_mut_Scanner_nb"]*(int)$_POST["bilan_pub_mut_Scanner_prix"]) .';'.


        $_POST["bilan_pub_pay_main_nb"] .';'.
        $_POST["bilan_pub_pay_main_prix"] .';'.
        (string)((int)$_POST["bilan_pub_pay_main_nb"]*(int)$_POST["bilan_pub_pay_main_prix"]) .';'.

        $_POST["bilan_pub_pay_Poignet_nb"] .';'.
        $_POST["bilan_pub_pay_Poignet_prix"] .';'.
        (string)((int)$_POST["bilan_pub_pay_Poignet_nb"]*(int)$_POST["bilan_pub_pay_Poignet_prix"]) .';'.

        $_POST["bilan_pub_pay_Coude_nb"] .';'.
        $_POST["bilan_pub_pay_Coude_prix"] .';'.
        (string)((int)$_POST["bilan_pub_pay_Coude_nb"]*(int)$_POST["bilan_pub_pay_Coude_prix"]) .';'.

        $_POST["bilan_pub_pay_Cheville_nb"] .';'.
        $_POST["bilan_pub_pay_Cheville_prix"] .';'.
        (string)((int)$_POST["bilan_pub_pay_Cheville_nb"]*(int)$_POST["bilan_pub_pay_Cheville_prix"]) .';'.

        $_POST["bilan_pub_pay_Jambe_nb"] .';'.
        $_POST["bilan_pub_pay_Jambe_prix"] .';'.
        (string)((int)$_POST["bilan_pub_pay_Cheville_nb"]*(int)$_POST["bilan_pub_pay_Cheville_prix"]) .';'.

        $_POST["bilan_pub_pay_Genou_nb"] .';'.
        $_POST["bilan_pub_pay_Genou_prix"] .';'.
        (string)((int)$_POST["bilan_pub_pay_Genou_nb"]*(int)$_POST["bilan_pub_pay_Genou_prix"]) .';'.

        $_POST["bilan_pub_pay_Cuisse_nb"] .';'.
        $_POST["bilan_pub_pay_Cuisse_prix"] .';'.
        (string)((int)$_POST["bilan_pub_pay_Cuisse_nb"]*(int)$_POST["bilan_pub_pay_Cuisse_prix"]) .';'.

        $_POST["bilan_pub_pay_Pied_nb"] .';'.
        $_POST["bilan_pub_pay_Pied_prix"] .';'.
        (string)((int)$_POST["bilan_pub_pay_Pied_nb"]*(int)$_POST["bilan_pub_pay_Pied_prix"]) .';'.

        $_POST["bilan_pub_pay_Hanche_nb"] .';'.
        $_POST["bilan_pub_pay_Hanche_prix"] .';'.
        (string)((int)$_POST["bilan_pub_pay_Hanche_nb"]*(int)$_POST["bilan_pub_pay_Hanche_prix"]) .';'.

        $_POST["bilan_pub_pay_Bassin_nb"] .';'.
        $_POST["bilan_pub_pay_Bassin_prix"] .';'.
        (string)((int)$_POST["bilan_pub_pay_Bassin_nb"]*(int)$_POST["bilan_pub_pay_Bassin_prix"]) .';'.

        $_POST["bilan_pub_pay_Rachis_D_nb"] .';'.
        $_POST["bilan_pub_pay_Rachis_D_prix"] .';'.
        (string)((int)$_POST["bilan_pub_pay_Rachis_D_nb"]*(int)$_POST["bilan_pub_pay_Rachis_D_prix"]) .';'.

        $_POST["bilan_pub_pay_Rachis_L_nb"] .';'.
        $_POST["bilan_pub_pay_Rachis_L_prix"] .';'.
        (string)((int)$_POST["bilan_pub_pay_Rachis_L_nb"]*(int)$_POST["bilan_pub_pay_Rachis_L_prix"]) .';'.

        $_POST["bilan_pub_pay_Epaule_nb"] .';'.
        $_POST["bilan_pub_pay_Epaule_prix"] .';'.
        (string)((int)$_POST["bilan_pub_pay_Epaule_nb"]*(int)$_POST["bilan_pub_pay_Epaule_prix"]) .';'.

        $_POST["bilan_pub_pay_Poumon_nb"] .';'.
        $_POST["bilan_pub_pay_Poumon_prix"] .';'.
        (string)((int)$_POST["bilan_pub_pay_Poumon_nb"]*(int)$_POST["bilan_pub_pay_Poumon_prix"]) .';'.

        $_POST["bilan_pub_pay_DMO_Non_Remboursalbe_nb"] .';'.
        $_POST["bilan_pub_pay_DMO_Non_Remboursalbe_prix"] .';'.
        (string)((int)$_POST["bilan_pub_pay_DMO_Non_Remboursalbe_nb"]*(int)$_POST["bilan_pub_pay_DMO_Non_Remboursalbe_prix"]) .';'.

        $_POST["bilan_pub_pay_Echographie_Main_nb"] .';'.
        $_POST["bilan_pub_pay_Echographie_Main_prix"] .';'.
        (string)((int)$_POST["bilan_pub_pay_Echographie_Main_nb"]*(int)$_POST["bilan_pub_pay_Echographie_Main_prix"]) .';'.

        $_POST["bilan_pub_pay_Echographie_Peid_nb"] .';'.
        $_POST["bilan_pub_pay_Echographie_Peid_prix"] .';'.
        (string)((int)$_POST["bilan_pub_pay_Echographie_Peid_nb"]*(int)$_POST["bilan_pub_pay_Echographie_Peid_prix"]) .';'.

        $_POST["bilan_pub_pay_Echographie_enthese_nb"] .';'.
        $_POST["bilan_pub_pay_Echographie_enthese_prix"] .';'.
        (string)((int)$_POST["bilan_pub_pay_Echographie_enthese_nb"]*(int)$_POST["bilan_pub_pay_Echographie_enthese_prix"]) .';'.

        $_POST["bilan_pub_pay_Echographie_Hanche_nb"] .';'.
        $_POST["bilan_pub_pay_Echographie_Hanche_prix"] .';'.
        (string)((int)$_POST["bilan_pub_pay_Echographie_Hanche_nb"]*(int)$_POST["bilan_pub_pay_Echographie_Hanche_prix"]) .';'.

        $_POST["bilan_pub_pay_IRM_nb"] .';'.
        $_POST["bilan_pub_pay_IRM_prix"] .';'.
        (string)((int)$_POST["bilan_pub_pay_IRM_nb"]*(int)$_POST["bilan_pub_pay_IRM_prix"]) .';'.

        $_POST["bilan_pub_pay_Scanner_nb"] .';'.
        $_POST["bilan_pub_pay_Scanner_prix"] .';'.
        (string)((int)$_POST["bilan_pub_pay_Scanner_nb"]*(int)$_POST["bilan_pub_pay_Scanner_prix"]) .';';
        #Coûtdes Rx réalisées en Pc:
        $tlt_bilan_pub = (int)$_POST["bilan_pub_mut_main_nb"]*(int)$_POST["bilan_pub_mut_main_prix"]+(int)$_POST["bilan_pub_mut_Poignet_nb"]*(int)$_POST["bilan_pub_mut_Poignet_prix"]+(int)$_POST["bilan_pub_mut_Coude_nb"]*(int)$_POST["bilan_pub_mut_Coude_prix"]+(int)$_POST["bilan_pub_mut_Cheville_nb"]*(int)$_POST["bilan_pub_mut_Cheville_prix"]+(int)$_POST["bilan_pub_mut_Cheville_nb"]*(int)$_POST["bilan_pub_mut_Cheville_prix"]+(int)$_POST["bilan_pub_mut_Genou_nb"]*(int)$_POST["bilan_pub_mut_Genou_prix"]+(int)$_POST["bilan_pub_mut_Cuisse_nb"]*(int)$_POST["bilan_pub_mut_Cuisse_prix"]+(int)$_POST["bilan_pub_mut_Pied_nb"]*(int)$_POST["bilan_pub_mut_Pied_prix"]+(int)$_POST["bilan_pub_mut_Hanche_nb"]*(int)$_POST["bilan_pub_mut_Hanche_prix"]+(int)$_POST["bilan_pub_mut_Bassin_nb"]*(int)$_POST["bilan_pub_mut_Bassin_prix"]+(int)$_POST["bilan_pub_mut_Rachis_D_nb"]*(int)$_POST["bilan_pub_mut_Rachis_D_prix"]+(int)$_POST["bilan_pub_mut_Rachis_L_nb"]*(int)$_POST["bilan_pub_mut_Rachis_L_prix"]+(int)$_POST["bilan_pub_mut_Epaule_nb"]*(int)$_POST["bilan_pub_mut_Epaule_prix"]+(int)$_POST["bilan_pub_mut_Poumon_nb"]*(int)$_POST["bilan_pub_mut_Poumon_prix"]+(int)$_POST["bilan_pub_mut_DMO_Non_Remboursalbe_nb"]*(int)$_POST["bilan_pub_mut_DMO_Non_Remboursalbe_prix"]+(int)$_POST["bilan_pub_mut_Echographie_Main_nb"]*(int)$_POST["bilan_pub_mut_Echographie_Main_prix"]+(int)$_POST["bilan_pub_mut_Echographie_Peid_nb"]*(int)$_POST["bilan_pub_mut_Echographie_Peid_prix"]+(int)$_POST["bilan_pub_mut_Echographie_enthese_nb"]*(int)$_POST["bilan_pub_mut_Echographie_enthese_prix"]+(int)$_POST["bilan_pub_mut_Echographie_Hanche_nb"]*(int)$_POST["bilan_pub_mut_Echographie_Hanche_prix"]+(int)$_POST["bilan_pub_mut_IRM_nb"]*(int)$_POST["bilan_pub_mut_IRM_prix"]+(int)$_POST["bilan_pub_mut_Scanner_nb"]*(int)$_POST["bilan_pub_mut_Scanner_prix"]+(int)$_POST["bilan_pub_pay_main_nb"]*(int)$_POST["bilan_pub_pay_main_prix"]+(int)$_POST["bilan_pub_pay_Poignet_nb"]*(int)$_POST["bilan_pub_pay_Poignet_prix"]+(int)$_POST["bilan_pub_pay_Coude_nb"]*(int)$_POST["bilan_pub_pay_Coude_prix"]+(int)$_POST["bilan_pub_pay_Cheville_nb"]*(int)$_POST["bilan_pub_pay_Cheville_prix"]+(int)$_POST["bilan_pub_pay_Cheville_nb"]*(int)$_POST["bilan_pub_pay_Cheville_prix"]+(int)$_POST["bilan_pub_pay_Genou_nb"]*(int)$_POST["bilan_pub_pay_Genou_prix"]+(int)$_POST["bilan_pub_pay_Cuisse_nb"]*(int)$_POST["bilan_pub_pay_Cuisse_prix"]+(int)$_POST["bilan_pub_pay_Pied_nb"]*(int)$_POST["bilan_pub_pay_Pied_prix"]+(int)$_POST["bilan_pub_pay_Hanche_nb"]*(int)$_POST["bilan_pub_pay_Hanche_prix"]+(int)$_POST["bilan_pub_pay_Bassin_nb"]*(int)$_POST["bilan_pub_pay_Bassin_prix"]+(int)$_POST["bilan_pub_pay_Rachis_D_nb"]*(int)$_POST["bilan_pub_pay_Rachis_D_prix"]+(int)$_POST["bilan_pub_pay_Rachis_L_nb"]*(int)$_POST["bilan_pub_pay_Rachis_L_prix"]+(int)$_POST["bilan_pub_pay_Epaule_nb"]*(int)$_POST["bilan_pub_pay_Epaule_prix"]+(int)$_POST["bilan_pub_pay_Poumon_nb"]*(int)$_POST["bilan_pub_pay_Poumon_prix"]+(int)$_POST["bilan_pub_pay_DMO_Non_Remboursalbe_nb"]*(int)$_POST["bilan_pub_pay_DMO_Non_Remboursalbe_prix"]+(int)$_POST["bilan_pub_pay_Echographie_Main_nb"]*(int)$_POST["bilan_pub_pay_Echographie_Main_prix"]+(int)$_POST["bilan_pub_pay_Echographie_Peid_nb"]*(int)$_POST["bilan_pub_pay_Echographie_Peid_prix"]+(int)$_POST["bilan_pub_pay_Echographie_enthese_nb"]*(int)$_POST["bilan_pub_pay_Echographie_enthese_prix"]+(int)$_POST["bilan_pub_pay_Echographie_Hanche_nb"]*(int)$_POST["bilan_pub_pay_Echographie_Hanche_prix"]+(int)$_POST["bilan_pub_pay_IRM_nb"]*(int)$_POST["bilan_pub_pay_IRM_prix"]+(int)$_POST["bilan_pub_pay_Scanner_nb"]*(int)$_POST["bilan_pub_pay_Scanner_prix"]+(int)$_POST["bilan_pub_mut_main_nb"]*(int)$_POST["bilan_pub_mut_main_prix"]+(int)$_POST["bilan_pub_mut_Poignet_nb"]*(int)$_POST["bilan_pub_mut_Poignet_prix"]+(int)$_POST["bilan_pub_mut_Coude_nb"]*(int)$_POST["bilan_pub_mut_Coude_prix"]+(int)$_POST["bilan_pub_mut_Cheville_nb"]*(int)$_POST["bilan_pub_mut_Cheville_prix"]+(int)$_POST["bilan_pub_mut_Cheville_nb"]*(int)$_POST["bilan_pub_mut_Cheville_prix"]+(int)$_POST["bilan_pub_mut_Genou_nb"]*(int)$_POST["bilan_pub_mut_Genou_prix"]+(int)$_POST["bilan_pub_mut_Cuisse_nb"]*(int)$_POST["bilan_pub_mut_Cuisse_prix"]+(int)$_POST["bilan_pub_mut_Pied_nb"]*(int)$_POST["bilan_pub_mut_Pied_prix"]+(int)$_POST["bilan_pub_mut_Hanche_nb"]*(int)$_POST["bilan_pub_mut_Hanche_prix"]+(int)$_POST["bilan_pub_mut_Bassin_nb"]*(int)$_POST["bilan_pub_mut_Bassin_prix"]+(int)$_POST["bilan_pub_mut_Rachis_D_nb"]*(int)$_POST["bilan_pub_mut_Rachis_D_prix"]+(int)$_POST["bilan_pub_mut_Rachis_L_nb"]*(int)$_POST["bilan_pub_mut_Rachis_L_prix"]+(int)$_POST["bilan_pub_mut_Epaule_nb"]*(int)$_POST["bilan_pub_mut_Epaule_prix"]+(int)$_POST["bilan_pub_mut_Poumon_nb"]*(int)$_POST["bilan_pub_mut_Poumon_prix"]+(int)$_POST["bilan_pub_mut_DMO_Non_Remboursalbe_nb"]*(int)$_POST["bilan_pub_mut_DMO_Non_Remboursalbe_prix"]+(int)$_POST["bilan_pub_mut_Echographie_Main_nb"]*(int)$_POST["bilan_pub_mut_Echographie_Main_prix"]+(int)$_POST["bilan_pub_mut_Echographie_Peid_nb"]*(int)$_POST["bilan_pub_mut_Echographie_Peid_prix"]+(int)$_POST["bilan_pub_mut_Echographie_enthese_nb"]*(int)$_POST["bilan_pub_mut_Echographie_enthese_prix"]+(int)$_POST["bilan_pub_mut_Echographie_Hanche_nb"]*(int)$_POST["bilan_pub_mut_Echographie_Hanche_prix"]+(int)$_POST["bilan_pub_mut_IRM_nb"]*(int)$_POST["bilan_pub_mut_IRM_prix"]+(int)$_POST["bilan_pub_mut_Scanner_nb"]*(int)$_POST["bilan_pub_mut_Scanner_prix"]+(int)$_POST["bilan_pub_pay_main_nb"]*(int)$_POST["bilan_pub_pay_main_prix"]+(int)$_POST["bilan_pub_pay_Poignet_nb"]*(int)$_POST["bilan_pub_pay_Poignet_prix"]+(int)$_POST["bilan_pub_pay_Coude_nb"]*(int)$_POST["bilan_pub_pay_Coude_prix"]+(int)$_POST["bilan_pub_pay_Cheville_nb"]*(int)$_POST["bilan_pub_pay_Cheville_prix"]+(int)$_POST["bilan_pub_pay_Cheville_nb"]*(int)$_POST["bilan_pub_pay_Cheville_prix"]+(int)$_POST["bilan_pub_pay_Genou_nb"]*(int)$_POST["bilan_pub_pay_Genou_prix"]+(int)$_POST["bilan_pub_pay_Cuisse_nb"]*(int)$_POST["bilan_pub_pay_Cuisse_prix"]+(int)$_POST["bilan_pub_pay_Pied_nb"]*(int)$_POST["bilan_pub_pay_Pied_prix"]+(int)$_POST["bilan_pub_pay_Hanche_nb"]*(int)$_POST["bilan_pub_pay_Hanche_prix"]+(int)$_POST["bilan_pub_pay_Bassin_nb"]*(int)$_POST["bilan_pub_pay_Bassin_prix"]+(int)$_POST["bilan_pub_pay_Rachis_D_nb"]*(int)$_POST["bilan_pub_pay_Rachis_D_prix"]+(int)$_POST["bilan_pub_pay_Rachis_L_nb"]*(int)$_POST["bilan_pub_pay_Rachis_L_prix"]+(int)$_POST["bilan_pub_pay_Epaule_nb"]*(int)$_POST["bilan_pub_pay_Epaule_prix"]+(int)$_POST["bilan_pub_pay_Poumon_nb"]*(int)$_POST["bilan_pub_pay_Poumon_prix"]+(int)$_POST["bilan_pub_pay_DMO_Non_Remboursalbe_nb"]*(int)$_POST["bilan_pub_pay_DMO_Non_Remboursalbe_prix"]+(int)$_POST["bilan_pub_pay_Echographie_Main_nb"]*(int)$_POST["bilan_pub_pay_Echographie_Main_prix"]+(int)$_POST["bilan_pub_pay_Echographie_Peid_nb"]*(int)$_POST["bilan_pub_pay_Echographie_Peid_prix"]+(int)$_POST["bilan_pub_pay_Echographie_enthese_nb"]*(int)$_POST["bilan_pub_pay_Echographie_enthese_prix"]+(int)$_POST["bilan_pub_pay_Echographie_Hanche_nb"]*(int)$_POST["bilan_pub_pay_Echographie_Hanche_prix"]+(int)$_POST["bilan_pub_pay_IRM_nb"]*(int)$_POST["bilan_pub_pay_IRM_prix"]+(int)$_POST["bilan_pub_pay_Scanner_nb"]*(int)$_POST["bilan_pub_pay_Scanner_prix"];
        $line = $line.$tlt_bilan_pub.


            $_POST["bilan_prv_main_nb"] .';'.
            $_POST["bilan_prv_main_prix"] .';'.
            (string)((int)$_POST["bilan_prv_main_nb"]*(int)$_POST["bilan_prv_main_prix"]) .';'.

            $_POST["bilan_prv_Poignet_nb"] .';'.
            $_POST["bilan_prv_Poignet_prix"] .';'.
            (string)((int)$_POST["bilan_prv_Poignet_nb"]*(int)$_POST["bilan_prv_Poignet_prix"]) .';'.

            $_POST["bilan_prv_Coude_nb"] .';'.
            $_POST["bilan_prv_Coude_prix"] .';'.
            (string)((int)$_POST["bilan_prv_Coude_nb"]*(int)$_POST["bilan_prv_Coude_prix"]) .';'.

            $_POST["bilan_prv_Cheville_nb"] .';'.
            $_POST["bilan_prv_Cheville_prix"] .';'.
            (string)((int)$_POST["bilan_prv_Cheville_nb"]*(int)$_POST["bilan_prv_Cheville_prix"]) .';'.

            $_POST["bilan_prv_Jambe_nb"] .';'.
            $_POST["bilan_prv_Jambe_prix"] .';'.
            (string)((int)$_POST["bilan_prv_Cheville_nb"]*(int)$_POST["bilan_prv_Cheville_prix"]) .';'.

            $_POST["bilan_prv_Genou_nb"] .';'.
            $_POST["bilan_prv_Genou_prix"] .';'.
            (string)((int)$_POST["bilan_prv_Genou_nb"]*(int)$_POST["bilan_prv_Genou_prix"]) .';'.

            $_POST["bilan_prv_Cuisse_nb"] .';'.
            $_POST["bilan_prv_Cuisse_prix"] .';'.
            (string)((int)$_POST["bilan_prv_Cuisse_nb"]*(int)$_POST["bilan_prv_Cuisse_prix"]) .';'.

            $_POST["bilan_prv_Pied_nb"] .';'.
            $_POST["bilan_prv_Pied_prix"] .';'.
            (string)((int)$_POST["bilan_prv_Pied_nb"]*(int)$_POST["bilan_prv_Pied_prix"]) .';'.

            $_POST["bilan_prv_Hanche_nb"] .';'.
            $_POST["bilan_prv_Hanche_prix"] .';'.
            (string)((int)$_POST["bilan_prv_Hanche_nb"]*(int)$_POST["bilan_prv_Hanche_prix"]) .';'.

            $_POST["bilan_prv_Bassin_nb"] .';'.
            $_POST["bilan_prv_Bassin_prix"] .';'.
            (string)((int)$_POST["bilan_prv_Bassin_nb"]*(int)$_POST["bilan_prv_Bassin_prix"]) .';'.

            $_POST["bilan_prv_Rachis_D_nb"] .';'.
            $_POST["bilan_prv_Rachis_D_prix"] .';'.
            (string)((int)$_POST["bilan_prv_Rachis_D_nb"]*(int)$_POST["bilan_prv_Rachis_D_prix"]) .';'.

            $_POST["bilan_prv_Rachis_L_nb"] .';'.
            $_POST["bilan_prv_Rachis_L_prix"] .';'.
            (string)((int)$_POST["bilan_prv_Rachis_L_nb"]*(int)$_POST["bilan_prv_Rachis_L_prix"]) .';'.

            $_POST["bilan_prv_Epaule_nb"] .';'.
            $_POST["bilan_prv_Epaule_prix"] .';'.
            (string)((int)$_POST["bilan_prv_Epaule_nb"]*(int)$_POST["bilan_prv_Epaule_prix"]) .';'.

            $_POST["bilan_prv_Poumon_nb"] .';'.
            $_POST["bilan_prv_Poumon_prix"] .';'.
            (string)((int)$_POST["bilan_prv_Poumon_nb"]*(int)$_POST["bilan_prv_Poumon_prix"]) .';'.

            $_POST["bilan_prv_DMO_Non_Remboursalbe_nb"] .';'.
            $_POST["bilan_prv_DMO_Non_Remboursalbe_prix"] .';'.
            (string)((int)$_POST["bilan_prv_DMO_Non_Remboursalbe_nb"]*(int)$_POST["bilan_prv_DMO_Non_Remboursalbe_prix"]) .';'.

            $_POST["bilan_prv_Echographie_Main_nb"] .';'.
            $_POST["bilan_prv_Echographie_Main_prix"] .';'.
            (string)((int)$_POST["bilan_prv_Echographie_Main_nb"]*(int)$_POST["bilan_prv_Echographie_Main_prix"]) .';'.

            $_POST["bilan_prv_Echographie_Peid_nb"] .';'.
            $_POST["bilan_prv_Echographie_Peid_prix"] .';'.
            (string)((int)$_POST["bilan_prv_Echographie_Peid_nb"]*(int)$_POST["bilan_prv_Echographie_Peid_prix"]) .';'.

            $_POST["bilan_prv_Echographie_enthese_nb"] .';'.
            $_POST["bilan_prv_Echographie_enthese_prix"] .';'.
            (string)((int)$_POST["bilan_prv_Echographie_enthese_nb"]*(int)$_POST["bilan_prv_Echographie_enthese_prix"]) .';'.

            $_POST["bilan_prv_Echographie_Hanche_nb"] .';'.
            $_POST["bilan_prv_Echographie_Hanche_prix"] .';'.
            (string)((int)$_POST["bilan_prv_Echographie_Hanche_nb"]*(int)$_POST["bilan_prv_Echographie_Hanche_prix"]) .';'.

            $_POST["bilan_prv_IRM_nb"] .';'.
            $_POST["bilan_prv_IRM_prix"] .';'.
            (string)((int)$_POST["bilan_prv_IRM_nb"]*(int)$_POST["bilan_prv_IRM_prix"]) .';'.

            $_POST["bilan_prv_Scanner_nb"] .';'.
            $_POST["bilan_prv_Scanner_prix"] .';'.
            (string)((int)$_POST["bilan_prv_Scanner_nb"]*(int)$_POST["bilan_prv_Scanner_prix"]) .';'.


            $_POST["bilan_pub_pay_main_nb"] .';'.
            $_POST["bilan_pub_pay_main_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_main_nb"]*(int)$_POST["bilan_pub_pay_main_prix"]) .';'.

            $_POST["bilan_pub_pay_Poignet_nb"] .';'.
            $_POST["bilan_pub_pay_Poignet_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Poignet_nb"]*(int)$_POST["bilan_pub_pay_Poignet_prix"]) .';'.

            $_POST["bilan_pub_pay_Coude_nb"] .';'.
            $_POST["bilan_pub_pay_Coude_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Coude_nb"]*(int)$_POST["bilan_pub_pay_Coude_prix"]) .';'.

            $_POST["bilan_pub_pay_Cheville_nb"] .';'.
            $_POST["bilan_pub_pay_Cheville_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Cheville_nb"]*(int)$_POST["bilan_pub_pay_Cheville_prix"]) .';'.

            $_POST["bilan_pub_pay_Jambe_nb"] .';'.
            $_POST["bilan_pub_pay_Jambe_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Cheville_nb"]*(int)$_POST["bilan_pub_pay_Cheville_prix"]) .';'.

            $_POST["bilan_pub_pay_Genou_nb"] .';'.
            $_POST["bilan_pub_pay_Genou_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Genou_nb"]*(int)$_POST["bilan_pub_pay_Genou_prix"]) .';'.

            $_POST["bilan_pub_pay_Cuisse_nb"] .';'.
            $_POST["bilan_pub_pay_Cuisse_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Cuisse_nb"]*(int)$_POST["bilan_pub_pay_Cuisse_prix"]) .';'.

            $_POST["bilan_pub_pay_Pied_nb"] .';'.
            $_POST["bilan_pub_pay_Pied_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Pied_nb"]*(int)$_POST["bilan_pub_pay_Pied_prix"]) .';'.

            $_POST["bilan_pub_pay_Hanche_nb"] .';'.
            $_POST["bilan_pub_pay_Hanche_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Hanche_nb"]*(int)$_POST["bilan_pub_pay_Hanche_prix"]) .';'.

            $_POST["bilan_pub_pay_Bassin_nb"] .';'.
            $_POST["bilan_pub_pay_Bassin_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Bassin_nb"]*(int)$_POST["bilan_pub_pay_Bassin_prix"]) .';'.

            $_POST["bilan_pub_pay_Rachis_D_nb"] .';'.
            $_POST["bilan_pub_pay_Rachis_D_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Rachis_D_nb"]*(int)$_POST["bilan_pub_pay_Rachis_D_prix"]) .';'.

            $_POST["bilan_pub_pay_Rachis_L_nb"] .';'.
            $_POST["bilan_pub_pay_Rachis_L_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Rachis_L_nb"]*(int)$_POST["bilan_pub_pay_Rachis_L_prix"]) .';'.

            $_POST["bilan_pub_pay_Epaule_nb"] .';'.
            $_POST["bilan_pub_pay_Epaule_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Epaule_nb"]*(int)$_POST["bilan_pub_pay_Epaule_prix"]) .';'.

            $_POST["bilan_pub_pay_Poumon_nb"] .';'.
            $_POST["bilan_pub_pay_Poumon_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Poumon_nb"]*(int)$_POST["bilan_pub_pay_Poumon_prix"]) .';'.

            $_POST["bilan_pub_pay_DMO_Non_Remboursalbe_nb"] .';'.
            $_POST["bilan_pub_pay_DMO_Non_Remboursalbe_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_DMO_Non_Remboursalbe_nb"]*(int)$_POST["bilan_pub_pay_DMO_Non_Remboursalbe_prix"]) .';'.

            $_POST["bilan_pub_pay_Echographie_Main_nb"] .';'.
            $_POST["bilan_pub_pay_Echographie_Main_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Echographie_Main_nb"]*(int)$_POST["bilan_pub_pay_Echographie_Main_prix"]) .';'.

            $_POST["bilan_pub_pay_Echographie_Peid_nb"] .';'.
            $_POST["bilan_pub_pay_Echographie_Peid_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Echographie_Peid_nb"]*(int)$_POST["bilan_pub_pay_Echographie_Peid_prix"]) .';'.

            $_POST["bilan_pub_pay_Echographie_enthese_nb"] .';'.
            $_POST["bilan_pub_pay_Echographie_enthese_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Echographie_enthese_nb"]*(int)$_POST["bilan_pub_pay_Echographie_enthese_prix"]) .';'.

            $_POST["bilan_pub_pay_Echographie_Hanche_nb"] .';'.
            $_POST["bilan_pub_pay_Echographie_Hanche_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Echographie_Hanche_nb"]*(int)$_POST["bilan_pub_pay_Echographie_Hanche_prix"]) .';'.

            $_POST["bilan_pub_pay_IRM_nb"] .';'.
            $_POST["bilan_pub_pay_IRM_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_IRM_nb"]*(int)$_POST["bilan_pub_pay_IRM_prix"]) .';'.

            $_POST["bilan_pub_pay_Scanner_nb"] .';'.
            $_POST["bilan_pub_pay_Scanner_prix"] .';'.
            (string)((int)$_POST["bilan_pub_pay_Scanner_nb"]*(int)$_POST["bilan_pub_pay_Scanner_prix"]) .';';
        #Coûtdes Rx réalisées en Pc:
        $tlt_bilan_pub = (int)$_POST["bilan_prv_main_nb"]*(int)$_POST["bilan_prv_main_prix"]+(int)$_POST["bilan_prv_Poignet_nb"]*(int)$_POST["bilan_prv_Poignet_prix"]+(int)$_POST["bilan_prv_Coude_nb"]*(int)$_POST["bilan_prv_Coude_prix"]+(int)$_POST["bilan_prv_Cheville_nb"]*(int)$_POST["bilan_prv_Cheville_prix"]+(int)$_POST["bilan_prv_Cheville_nb"]*(int)$_POST["bilan_prv_Cheville_prix"]+(int)$_POST["bilan_prv_Genou_nb"]*(int)$_POST["bilan_prv_Genou_prix"]+(int)$_POST["bilan_prv_Cuisse_nb"]*(int)$_POST["bilan_prv_Cuisse_prix"]+(int)$_POST["bilan_prv_Pied_nb"]*(int)$_POST["bilan_prv_Pied_prix"]+(int)$_POST["bilan_prv_Hanche_nb"]*(int)$_POST["bilan_prv_Hanche_prix"]+(int)$_POST["bilan_prv_Bassin_nb"]*(int)$_POST["bilan_prv_Bassin_prix"]+(int)$_POST["bilan_prv_Rachis_D_nb"]*(int)$_POST["bilan_prv_Rachis_D_prix"]+(int)$_POST["bilan_prv_Rachis_L_nb"]*(int)$_POST["bilan_prv_Rachis_L_prix"]+(int)$_POST["bilan_prv_Epaule_nb"]*(int)$_POST["bilan_prv_Epaule_prix"]+(int)$_POST["bilan_prv_Poumon_nb"]*(int)$_POST["bilan_prv_Poumon_prix"]+(int)$_POST["bilan_prv_DMO_Non_Remboursalbe_nb"]*(int)$_POST["bilan_prv_DMO_Non_Remboursalbe_prix"]+(int)$_POST["bilan_prv_Echographie_Main_nb"]*(int)$_POST["bilan_prv_Echographie_Main_prix"]+(int)$_POST["bilan_prv_Echographie_Peid_nb"]*(int)$_POST["bilan_prv_Echographie_Peid_prix"]+(int)$_POST["bilan_prv_Echographie_enthese_nb"]*(int)$_POST["bilan_prv_Echographie_enthese_prix"]+(int)$_POST["bilan_prv_Echographie_Hanche_nb"]*(int)$_POST["bilan_prv_Echographie_Hanche_prix"]+(int)$_POST["bilan_prv_IRM_nb"]*(int)$_POST["bilan_prv_IRM_prix"]+(int)$_POST["bilan_prv_Scanner_nb"]*(int)$_POST["bilan_prv_Scanner_prix"]+(int)$_POST["bilan_pub_pay_main_nb"]*(int)$_POST["bilan_pub_pay_main_prix"]+(int)$_POST["bilan_pub_pay_Poignet_nb"]*(int)$_POST["bilan_pub_pay_Poignet_prix"]+(int)$_POST["bilan_pub_pay_Coude_nb"]*(int)$_POST["bilan_pub_pay_Coude_prix"]+(int)$_POST["bilan_pub_pay_Cheville_nb"]*(int)$_POST["bilan_pub_pay_Cheville_prix"]+(int)$_POST["bilan_pub_pay_Cheville_nb"]*(int)$_POST["bilan_pub_pay_Cheville_prix"]+(int)$_POST["bilan_pub_pay_Genou_nb"]*(int)$_POST["bilan_pub_pay_Genou_prix"]+(int)$_POST["bilan_pub_pay_Cuisse_nb"]*(int)$_POST["bilan_pub_pay_Cuisse_prix"]+(int)$_POST["bilan_pub_pay_Pied_nb"]*(int)$_POST["bilan_pub_pay_Pied_prix"]+(int)$_POST["bilan_pub_pay_Hanche_nb"]*(int)$_POST["bilan_pub_pay_Hanche_prix"]+(int)$_POST["bilan_pub_pay_Bassin_nb"]*(int)$_POST["bilan_pub_pay_Bassin_prix"]+(int)$_POST["bilan_pub_pay_Rachis_D_nb"]*(int)$_POST["bilan_pub_pay_Rachis_D_prix"]+(int)$_POST["bilan_pub_pay_Rachis_L_nb"]*(int)$_POST["bilan_pub_pay_Rachis_L_prix"]+(int)$_POST["bilan_pub_pay_Epaule_nb"]*(int)$_POST["bilan_pub_pay_Epaule_prix"]+(int)$_POST["bilan_pub_pay_Poumon_nb"]*(int)$_POST["bilan_pub_pay_Poumon_prix"]+(int)$_POST["bilan_pub_pay_DMO_Non_Remboursalbe_nb"]*(int)$_POST["bilan_pub_pay_DMO_Non_Remboursalbe_prix"]+(int)$_POST["bilan_pub_pay_Echographie_Main_nb"]*(int)$_POST["bilan_pub_pay_Echographie_Main_prix"]+(int)$_POST["bilan_pub_pay_Echographie_Peid_nb"]*(int)$_POST["bilan_pub_pay_Echographie_Peid_prix"]+(int)$_POST["bilan_pub_pay_Echographie_enthese_nb"]*(int)$_POST["bilan_pub_pay_Echographie_enthese_prix"]+(int)$_POST["bilan_pub_pay_Echographie_Hanche_nb"]*(int)$_POST["bilan_pub_pay_Echographie_Hanche_prix"]+(int)$_POST["bilan_pub_pay_IRM_nb"]*(int)$_POST["bilan_pub_pay_IRM_prix"]+(int)$_POST["bilan_pub_pay_Scanner_nb"]*(int)$_POST["bilan_pub_pay_Scanner_prix"]+(int)$_POST["bilan_prv_main_nb"]*(int)$_POST["bilan_prv_main_prix"]+(int)$_POST["bilan_prv_Poignet_nb"]*(int)$_POST["bilan_prv_Poignet_prix"]+(int)$_POST["bilan_prv_Coude_nb"]*(int)$_POST["bilan_prv_Coude_prix"]+(int)$_POST["bilan_prv_Cheville_nb"]*(int)$_POST["bilan_prv_Cheville_prix"]+(int)$_POST["bilan_prv_Cheville_nb"]*(int)$_POST["bilan_prv_Cheville_prix"]+(int)$_POST["bilan_prv_Genou_nb"]*(int)$_POST["bilan_prv_Genou_prix"]+(int)$_POST["bilan_prv_Cuisse_nb"]*(int)$_POST["bilan_prv_Cuisse_prix"]+(int)$_POST["bilan_prv_Pied_nb"]*(int)$_POST["bilan_prv_Pied_prix"]+(int)$_POST["bilan_prv_Hanche_nb"]*(int)$_POST["bilan_prv_Hanche_prix"]+(int)$_POST["bilan_prv_Bassin_nb"]*(int)$_POST["bilan_prv_Bassin_prix"]+(int)$_POST["bilan_prv_Rachis_D_nb"]*(int)$_POST["bilan_prv_Rachis_D_prix"]+(int)$_POST["bilan_prv_Rachis_L_nb"]*(int)$_POST["bilan_prv_Rachis_L_prix"]+(int)$_POST["bilan_prv_Epaule_nb"]*(int)$_POST["bilan_prv_Epaule_prix"]+(int)$_POST["bilan_prv_Poumon_nb"]*(int)$_POST["bilan_prv_Poumon_prix"]+(int)$_POST["bilan_prv_DMO_Non_Remboursalbe_nb"]*(int)$_POST["bilan_prv_DMO_Non_Remboursalbe_prix"]+(int)$_POST["bilan_prv_Echographie_Main_nb"]*(int)$_POST["bilan_prv_Echographie_Main_prix"]+(int)$_POST["bilan_prv_Echographie_Peid_nb"]*(int)$_POST["bilan_prv_Echographie_Peid_prix"]+(int)$_POST["bilan_prv_Echographie_enthese_nb"]*(int)$_POST["bilan_prv_Echographie_enthese_prix"]+(int)$_POST["bilan_prv_Echographie_Hanche_nb"]*(int)$_POST["bilan_prv_Echographie_Hanche_prix"]+(int)$_POST["bilan_prv_IRM_nb"]*(int)$_POST["bilan_prv_IRM_prix"]+(int)$_POST["bilan_prv_Scanner_nb"]*(int)$_POST["bilan_prv_Scanner_prix"];
        $line = (string)$line.$tlt_bilan_pub.";".
            $_POST["bilan_prv_Scanner_rembourse"] .';'.
        $_POST["bilan_prv_Scanner_rembourse_prix"] .';'.
            (string)(  $tlt_bilan_pub -  (int)$_POST["bilan_prv_Scanner_rembourse_prix"] ).';'.

$_POST['bilan_mut_ASAT_nb'].';'.
$_POST['bilan_mut_ASAT_prix'].';'.
                        (string)((int)$_POST['bilan_mut_ASAT_nb']*(int)$_POST['bilan_mut_ASAT_prix']).';'.
$_POST['bilan_mut_ALAT_nb'].';'.
$_POST['bilan_mut_ALAT_prix'].';'.
                        (string)((int)$_POST['bilan_mut_ALAT_nb']*(int)$_POST['bilan_mut_ALAT_prix']).';'.
$_POST['bilan_mut_Albumine_nb'].';'.
$_POST['bilan_mut_Albumine_prix'].';'.
                        (string)((int)$_POST['bilan_mut_Albumine_nb']*(int)$_POST['bilan_mut_Albumine_prix']).';'.
$_POST['bilan_mut_AntiCCP_nb'].';'.
$_POST['bilan_mut_AntiCCP_prix'].';'.
                        (string)((int)$_POST['bilan_mut_AntiCCP_nb']*(int)$_POST['bilan_mut_AntiCCP_prix']).';'.
$_POST['bilan_mut_AAN_nb'].';'.
$_POST['bilan_mut_AAN_prix'].';'.
                        (string)((int)$_POST['bilan_mut_AAN_nb']*(int)$_POST['bilan_mut_AAN_prix']).';'.
$_POST['bilan_mut_AntiECT_nb'].';'.
$_POST['bilan_mut_AntiECT_prix'].';'.
                        (string)((int)$_POST['bilan_mut_AntiECT_nb']*(int)$_POST['bilan_mut_AntiECT_prix']).';'.
$_POST['bilan_mut_ACPA_nb'].';'.
$_POST['bilan_mut_ACPA_prix'].';'.
                        (string)((int)$_POST['bilan_mut_ACPA_nb']*(int)$_POST['bilan_mut_ACPA_prix']).';'.
$_POST['bilan_mut_CholesterolTotal_nb'].';'.
$_POST['bilan_mut_CholesterolTotal_prix'].';'.
                        (string)((int)$_POST['bilan_mut_CholesterolTotal_nb']*(int)$_POST['bilan_mut_CholesterolTotal_prix']).';'.
$_POST['bilan_mut_ECBU_nb'].';'.
$_POST['bilan_mut_ECBU_prix'].';'.
                        (string)((int)$_POST['bilan_mut_ECBU_nb']*(int)$_POST['bilan_mut_ECBU_prix']).';'.
$_POST['bilan_mut_FR_nb'].';'.
$_POST['bilan_mut_FR_prix'].';'.
                        (string)((int)$_POST['bilan_mut_FR_nb']*(int)$_POST['bilan_mut_FR_prix']).';'.
$_POST['bilan_mut_HDL_nb'].';'.
$_POST['bilan_mut_HDL_prix'].';'.
                        (string)((int)$_POST['bilan_mut_HDL_nb']*(int)$_POST['bilan_mut_HDL_prix']).';'.
$_POST['bilan_mut_LDL_nb'].';'.
$_POST['bilan_mut_LDL_prix'].';'.
                        (string)((int)$_POST['bilan_mut_LDL_nb']*(int)$_POST['bilan_mut_LDL_prix']).';'.
$_POST['bilan_mut_Triglycerides_nb'].';'.
$_POST['bilan_mut_Triglycerides_prix'].';'.
                        (string)((int)$_POST['bilan_mut_Triglycerides_nb']*(int)$_POST['bilan_mut_Triglycerides_prix']).';'.
$_POST['bilan_mut_Creatinine_nb'].';'.
$_POST['bilan_mut_Creatinine_prix'].';'.
                        (string)((int)$_POST['bilan_mut_Creatinine_nb']*(int)$_POST['bilan_mut_Creatinine_prix']).';'.
$_POST['bilan_mut_Dosage_des_IgA_IgGIgM_nb'].';'.
$_POST['bilan_mut_Dosage_des_IgA_IgGIgM_prix'].';'.
                        (string)((int)$_POST['bilan_mut_Dosage_des_IgA_IgGIgM_nb']*(int)$_POST['bilan_mut_Dosage_des_IgA_IgGIgM_prix']).';'.
$_POST['bilan_mut_EPP_nb'].';'.
$_POST['bilan_mut_EPP_prix'].';'.
                        (string)((int)$_POST['bilan_mut_EPP_nb']*(int)$_POST['bilan_mut_EPP_prix']).';'.
$_POST['bilan_mut_GGT_nb'].';'.
$_POST['bilan_mut_GGT_prix'].';'.
                        (string)((int)$_POST['bilan_mut_GGT_nb']*(int)$_POST['bilan_mut_GGT_prix']).';'.
$_POST['bilan_mut_Glycemie_nb'].';'.
$_POST['bilan_mut_Glycemie_prix'].';'.
                        (string)((int)$_POST['bilan_mut_Glycemie_nb']*(int)$_POST['bilan_mut_Glycemie_prix']).';'.
$_POST['bilan_mut_HbA1C_nb'].';'.
$_POST['bilan_mut_HbA1C_prix'].';'.
                        (string)((int)$_POST['bilan_mut_HbA1C_nb']*(int)$_POST['bilan_mut_HbA1C_prix']).';'.
$_POST['bilan_mut_HVA_nb'].';'.
$_POST['bilan_mut_HVA_prix'].';'.
                        (string)((int)$_POST['bilan_mut_HVA_nb']*(int)$_POST['bilan_mut_HVA_prix']).';'.
$_POST['bilan_mut_HVBAgHBS_nb'].';'.
$_POST['bilan_mut_HVBAgHBS_prix'].';'.
                        (string)((int)$_POST['bilan_mut_HVBAgHBS_nb']*(int)$_POST['bilan_mut_HVBAgHBS_prix']).';'.
$_POST['bilan_mut_HV_AcHBC_nb'].';'.
$_POST['bilan_mut_HV_AcHBC_prix'].';'.
                        (string)((int)$_POST['bilan_mut_HV_AcHBC_nb']*(int)$_POST['bilan_mut_HV_AcHBC_prix']).';'.
$_POST['bilan_mut_HVC_nb'].';'.
$_POST['bilan_mut_HVC_prix'].';'.
                        (string)((int)$_POST['bilan_mut_HVC_nb']*(int)$_POST['bilan_mut_HVC_prix']).';'.
$_POST['bilan_mut_HIV_nb'].';'.
$_POST['bilan_mut_HIV_prix'].';'.
                        (string)((int)$_POST['bilan_mut_HIV_nb']*(int)$_POST['bilan_mut_HIV_prix']).';'.
$_POST['bilan_mut_HLA_B27_nb'].';'.
$_POST['bilan_mut_HLA_B27_prix'].';'.
                        (string)((int)$_POST['bilan_mut_HLA_B27_nb']*(int)$_POST['bilan_mut_HLA_B27_prix']).';'.
$_POST['bilan_mut_Ionogramme_nb'].';'.
$_POST['bilan_mut_Ionogramme_prix'].';'.
                        (string)((int)$_POST['bilan_mut_Ionogramme_nb']*(int)$_POST['bilan_mut_Ionogramme_prix']).';'.
$_POST['bilan_mut_IDR_nb'].';'.
$_POST['bilan_mut_IDR_prix'].';'.
                        (string)((int)$_POST['bilan_mut_IDR_nb']*(int)$_POST['bilan_mut_IDR_prix']).';'.
$_POST['bilan_mut_PAL_nb'].';'.
$_POST['bilan_mut_PAL_prix'].';'.
                        (string)((int)$_POST['bilan_mut_PAL_nb']*(int)$_POST['bilan_mut_PAL_prix']).';'.
$_POST['bilan_mut_VS_nb'].';'.
$_POST['bilan_mut_VS_prix'].';'.
                        (string)((int)$_POST['bilan_mut_VS_nb']*(int)$_POST['bilan_mut_VS_prix']).';'.
$_POST['bilan_mut_CRP_nb'].';'.
$_POST['bilan_mut_CRP_prix'].';'.
                        (string)((int)$_POST['bilan_mut_CRP_nb']*(int)$_POST['bilan_mut_CRP_prix']).';'.
$_POST['bilan_mut_NFS_nb'].';'.
$_POST['bilan_mut_NFS_prix'].';'.
                        (string)((int)$_POST['bilan_mut_NFS_nb']*(int)$_POST['bilan_mut_NFS_prix']).';'.
$_POST['bilan_mut_Quantiferon_nb'].';'.
$_POST['bilan_mut_Quantiferon_prix'].';'.
        (string)((int)$_POST['bilan_mut_Quantiferon_nb']*(int)$_POST['bilan_mut_Quantiferon_prix']).';'.

                $_POST['bilan_Pay_ASAT_nb'].';'.
                $_POST['bilan_Pay_ASAT_prix'].';'.
                            (string)((int)$_POST['bilan_Pay_ASAT_nb']*(int)$_POST['bilan_Pay_ASAT_prix']).';'.
                $_POST['bilan_Pay_ALAT_nb'].';'.
                $_POST['bilan_Pay_ALAT_prix'].';'.
                                        (string)((int)$_POST['bilan_Pay_ALAT_nb']*(int)$_POST['bilan_Pay_ALAT_prix']).';'.
                $_POST['bilan_Pay_Albumine_nb'].';'.
                $_POST['bilan_Pay_Albumine_prix'].';'.
                                        (string)((int)$_POST['bilan_Pay_Albumine_nb']*(int)$_POST['bilan_Pay_Albumine_prix']).';'.
                $_POST['bilan_Pay_AntiCCP_nb'].';'.
                $_POST['bilan_Pay_AntiCCP_prix'].';'.
                                        (string)((int)$_POST['bilan_Pay_AntiCCP_nb']*(int)$_POST['bilan_Pay_AntiCCP_prix']).';'.
                $_POST['bilan_Pay_AAN_nb'].';'.
                $_POST['bilan_Pay_AAN_prix'].';'.
                                        (string)((int)$_POST['bilan_Pay_AAN_nb']*(int)$_POST['bilan_Pay_AAN_prix']).';'.
                $_POST['bilan_Pay_AntiECT_nb'].';'.
                $_POST['bilan_Pay_AntiECT_prix'].';'.
                                        (string)((int)$_POST['bilan_Pay_AntiECT_nb']*(int)$_POST['bilan_Pay_AntiECT_prix']).';'.
                $_POST['bilan_Pay_ACPA_nb'].';'.
                $_POST['bilan_Pay_ACPA_prix'].';'.
                                        (string)((int)$_POST['bilan_Pay_ACPA_nb']*(int)$_POST['bilan_Pay_ACPA_prix']).';'.
                $_POST['bilan_Pay_CholesterolTotal_nb'].';'.
                $_POST['bilan_Pay_CholesterolTotal_prix'].';'.
                                        (string)((int)$_POST['bilan_Pay_CholesterolTotal_nb']*(int)$_POST['bilan_Pay_CholesterolTotal_prix']).';'.
                $_POST['bilan_Pay_ECBU_nb'].';'.
                $_POST['bilan_Pay_ECBU_prix'].';'.
                                        (string)((int)$_POST['bilan_Pay_ECBU_nb']*(int)$_POST['bilan_Pay_ECBU_prix']).';'.
                $_POST['bilan_Pay_FR_nb'].';'.
                $_POST['bilan_Pay_FR_prix'].';'.
                                        (string)((int)$_POST['bilan_Pay_FR_nb']*(int)$_POST['bilan_Pay_FR_prix']).';'.
                $_POST['bilan_Pay_HDL_nb'].';'.
                $_POST['bilan_Pay_HDL_prix'].';'.
                                        (string)((int)$_POST['bilan_Pay_HDL_nb']*(int)$_POST['bilan_Pay_HDL_prix']).';'.
                $_POST['bilan_Pay_LDL_nb'].';'.
                $_POST['bilan_Pay_LDL_prix'].';'.
                                        (string)((int)$_POST['bilan_Pay_LDL_nb']*(int)$_POST['bilan_Pay_LDL_prix']).';'.
                $_POST['bilan_Pay_Triglycerides_nb'].';'.
                $_POST['bilan_Pay_Triglycerides_prix'].';'.
                                        (string)((int)$_POST['bilan_Pay_Triglycerides_nb']*(int)$_POST['bilan_Pay_Triglycerides_prix']).';'.
                $_POST['bilan_Pay_Creatinine_nb'].';'.
                $_POST['bilan_Pay_Creatinine_prix'].';'.
                                        (string)((int)$_POST['bilan_Pay_Creatinine_nb']*(int)$_POST['bilan_Pay_Creatinine_prix']).';'.
                $_POST['bilan_Pay_Dosage_des_IgA_IgGIgM_nb'].';'.
                $_POST['bilan_Pay_Dosage_des_IgA_IgGIgM_prix'].';'.
                                        (string)((int)$_POST['bilan_Pay_Dosage_des_IgA_IgGIgM_nb']*(int)$_POST['bilan_Pay_Dosage_des_IgA_IgGIgM_prix']).';'.
                $_POST['bilan_Pay_EPP_nb'].';'.
                $_POST['bilan_Pay_EPP_prix'].';'.
                                        (string)((int)$_POST['bilan_Pay_EPP_nb']*(int)$_POST['bilan_Pay_EPP_prix']).';'.
                $_POST['bilan_Pay_GGT_nb'].';'.
                $_POST['bilan_Pay_GGT_prix'].';'.
                                        (string)((int)$_POST['bilan_Pay_GGT_nb']*(int)$_POST['bilan_Pay_GGT_prix']).';'.
                $_POST['bilan_Pay_Glycemie_nb'].';'.
                $_POST['bilan_Pay_Glycemie_prix'].';'.
                                        (string)((int)$_POST['bilan_Pay_Glycemie_nb']*(int)$_POST['bilan_Pay_Glycemie_prix']).';'.
                $_POST['bilan_Pay_HbA1C_nb'].';'.
                $_POST['bilan_Pay_HbA1C_prix'].';'.
                                        (string)((int)$_POST['bilan_Pay_HbA1C_nb']*(int)$_POST['bilan_Pay_HbA1C_prix']).';'.
                $_POST['bilan_Pay_HVA_nb'].';'.
                $_POST['bilan_Pay_HVA_prix'].';'.
                                        (string)((int)$_POST['bilan_Pay_HVA_nb']*(int)$_POST['bilan_Pay_HVA_prix']).';'.
                $_POST['bilan_Pay_HVBAgHBS_nb'].';'.
                $_POST['bilan_Pay_HVBAgHBS_prix'].';'.
                                        (string)((int)$_POST['bilan_Pay_HVBAgHBS_nb']*(int)$_POST['bilan_Pay_HVBAgHBS_prix']).';'.
                $_POST['bilan_Pay_HV_AcHBC_nb'].';'.
                $_POST['bilan_Pay_HV_AcHBC_prix'].';'.
                                        (string)((int)$_POST['bilan_Pay_HV_AcHBC_nb']*(int)$_POST['bilan_Pay_HV_AcHBC_prix']).';'.
                $_POST['bilan_Pay_HVC_nb'].';'.
                $_POST['bilan_Pay_HVC_prix'].';'.
                                        (string)((int)$_POST['bilan_Pay_HVC_nb']*(int)$_POST['bilan_Pay_HVC_prix']).';'.
                $_POST['bilan_Pay_HIV_nb'].';'.
                $_POST['bilan_Pay_HIV_prix'].';'.
                                        (string)((int)$_POST['bilan_Pay_HIV_nb']*(int)$_POST['bilan_Pay_HIV_prix']).';'.
                $_POST['bilan_Pay_HLA_B27_nb'].';'.
                $_POST['bilan_Pay_HLA_B27_prix'].';'.
                                        (string)((int)$_POST['bilan_Pay_HLA_B27_nb']*(int)$_POST['bilan_Pay_HLA_B27_prix']).';'.
                $_POST['bilan_Pay_Ionogramme_nb'].';'.
                $_POST['bilan_Pay_Ionogramme_prix'].';'.
                                        (string)((int)$_POST['bilan_Pay_Ionogramme_nb']*(int)$_POST['bilan_Pay_Ionogramme_prix']).';'.
                $_POST['bilan_Pay_IDR_nb'].';'.
                $_POST['bilan_Pay_IDR_prix'].';'.
                                        (string)((int)$_POST['bilan_Pay_IDR_nb']*(int)$_POST['bilan_Pay_IDR_prix']).';'.
                $_POST['bilan_Pay_PAL_nb'].';'.
                $_POST['bilan_Pay_PAL_prix'].';'.
                                        (string)((int)$_POST['bilan_Pay_PAL_nb']*(int)$_POST['bilan_Pay_PAL_prix']).';'.
                $_POST['bilan_Pay_VS_nb'].';'.
                $_POST['bilan_Pay_VS_prix'].';'.
                                        (string)((int)$_POST['bilan_Pay_VS_nb']*(int)$_POST['bilan_Pay_VS_prix']).';'.
                $_POST['bilan_Pay_CRP_nb'].';'.
                $_POST['bilan_Pay_CRP_prix'].';'.
                                        (string)((int)$_POST['bilan_Pay_CRP_nb']*(int)$_POST['bilan_Pay_CRP_prix']).';'.
                $_POST['bilan_Pay_NFS_nb'].';'.
                $_POST['bilan_Pay_NFS_prix'].';'.
                                        (string)((int)$_POST['bilan_Pay_NFS_nb']*(int)$_POST['bilan_Pay_NFS_prix']).';'.
                $_POST['bilan_Pay_Quantiferon_nb'].';'.
                $_POST['bilan_Pay_Quantiferon_prix'].';'.
                        (string)((int)$_POST['bilan_Pay_Quantiferon_nb']*(int)$_POST['bilan_Pay_Quantiferon_prix']).';';

            $bio_public = (int)$_POST['bilan_mut_ASAT_nb']*(int)$_POST['bilan_mut_ASAT_prix']+(int)$_POST['bilan_mut_ALAT_nb']*(int)$_POST['bilan_mut_ALAT_prix']+(int)$_POST['bilan_mut_Albumine_nb']*(int)$_POST['bilan_mut_Albumine_prix']+(int)$_POST['bilan_mut_AntiCCP_nb']*(int)$_POST['bilan_mut_AntiCCP_prix']+(int)$_POST['bilan_mut_AAN_nb']*(int)$_POST['bilan_mut_AAN_prix']+(int)$_POST['bilan_mut_AntiECT_nb']*(int)$_POST['bilan_mut_AntiECT_prix']+(int)$_POST['bilan_mut_ACPA_nb']*(int)$_POST['bilan_mut_ACPA_prix']+(int)$_POST['bilan_mut_CholesterolTotal_nb']*(int)$_POST['bilan_mut_CholesterolTotal_prix']+(int)$_POST['bilan_mut_ECBU_nb']*(int)$_POST['bilan_mut_ECBU_prix']+(int)$_POST['bilan_mut_FR_nb']*(int)$_POST['bilan_mut_FR_prix']+(int)$_POST['bilan_mut_HDL_nb']*(int)$_POST['bilan_mut_HDL_prix']+(int)$_POST['bilan_mut_LDL_nb']*(int)$_POST['bilan_mut_LDL_prix']+(int)$_POST['bilan_mut_Triglycerides_nb']*(int)$_POST['bilan_mut_Triglycerides_prix']+(int)$_POST['bilan_mut_Creatinine_nb']*(int)$_POST['bilan_mut_Creatinine_prix']+(int)$_POST['bilan_mut_Dosage_des_IgA_IgGIgM_nb']*(int)$_POST['bilan_mut_Dosage_des_IgA_IgGIgM_prix']+(int)$_POST['bilan_mut_EPP_nb']*(int)$_POST['bilan_mut_EPP_prix']+(int)$_POST['bilan_mut_GGT_nb']*(int)$_POST['bilan_mut_GGT_prix']+(int)$_POST['bilan_mut_Glycemie_nb']*(int)$_POST['bilan_mut_Glycemie_prix']+(int)$_POST['bilan_mut_HbA1C_nb']*(int)$_POST['bilan_mut_HbA1C_prix']+(int)$_POST['bilan_mut_HVA_nb']*(int)$_POST['bilan_mut_HVA_prix']+(int)$_POST['bilan_mut_HVBAgHBS_nb']*(int)$_POST['bilan_mut_HVBAgHBS_prix']+(int)$_POST['bilan_mut_HV_AcHBC_nb']*(int)$_POST['bilan_mut_HV_AcHBC_prix']+(int)$_POST['bilan_mut_HVC_nb']*(int)$_POST['bilan_mut_HVC_prix']+(int)$_POST['bilan_mut_HIV_nb']*(int)$_POST['bilan_mut_HIV_prix']+(int)$_POST['bilan_mut_HLA_B27_nb']*(int)$_POST['bilan_mut_HLA_B27_prix']+(int)$_POST['bilan_mut_Ionogramme_nb']*(int)$_POST['bilan_mut_Ionogramme_prix']+(int)$_POST['bilan_mut_IDR_nb']*(int)$_POST['bilan_mut_IDR_prix']+(int)$_POST['bilan_mut_PAL_nb']*(int)$_POST['bilan_mut_PAL_prix']+(int)$_POST['bilan_mut_VS_nb']*(int)$_POST['bilan_mut_VS_prix']+(int)$_POST['bilan_mut_CRP_nb']*(int)$_POST['bilan_mut_CRP_prix']+(int)$_POST['bilan_mut_NFS_nb']*(int)$_POST['bilan_mut_NFS_prix']+(int)$_POST['bilan_mut_Quantiferon_nb']*(int)$_POST['bilan_mut_Quantiferon_prix']+(int)$_POST['bilan_Pay_ASAT_nb']*(int)$_POST['bilan_Pay_ASAT_prix']+(int)$_POST['bilan_Pay_ALAT_nb']*(int)$_POST['bilan_Pay_ALAT_prix']+(int)$_POST['bilan_Pay_Albumine_nb']*(int)$_POST['bilan_Pay_Albumine_prix']+(int)$_POST['bilan_Pay_AntiCCP_nb']*(int)$_POST['bilan_Pay_AntiCCP_prix']+(int)$_POST['bilan_Pay_AAN_nb']*(int)$_POST['bilan_Pay_AAN_prix']+(int)$_POST['bilan_Pay_AntiECT_nb']*(int)$_POST['bilan_Pay_AntiECT_prix']+(int)$_POST['bilan_Pay_ACPA_nb']*(int)$_POST['bilan_Pay_ACPA_prix']+(int)$_POST['bilan_Pay_CholesterolTotal_nb']*(int)$_POST['bilan_Pay_CholesterolTotal_prix']+(int)$_POST['bilan_Pay_ECBU_nb']*(int)$_POST['bilan_Pay_ECBU_prix']+(int)$_POST['bilan_Pay_FR_nb']*(int)$_POST['bilan_Pay_FR_prix']+(int)$_POST['bilan_Pay_HDL_nb']*(int)$_POST['bilan_Pay_HDL_prix']+(int)$_POST['bilan_Pay_LDL_nb']*(int)$_POST['bilan_Pay_LDL_prix']+(int)$_POST['bilan_Pay_Triglycerides_nb']*(int)$_POST['bilan_Pay_Triglycerides_prix']+(int)$_POST['bilan_Pay_Creatinine_nb']*(int)$_POST['bilan_Pay_Creatinine_prix']+(int)$_POST['bilan_Pay_Dosage_des_IgA_IgGIgM_nb']*(int)$_POST['bilan_Pay_Dosage_des_IgA_IgGIgM_prix']+(int)$_POST['bilan_Pay_EPP_nb']*(int)$_POST['bilan_Pay_EPP_prix']+(int)$_POST['bilan_Pay_GGT_nb']*(int)$_POST['bilan_Pay_GGT_prix']+(int)$_POST['bilan_Pay_Glycemie_nb']*(int)$_POST['bilan_Pay_Glycemie_prix']+(int)$_POST['bilan_Pay_HbA1C_nb']*(int)$_POST['bilan_Pay_HbA1C_prix']+(int)$_POST['bilan_Pay_HVA_nb']*(int)$_POST['bilan_Pay_HVA_prix']+(int)$_POST['bilan_Pay_HVBAgHBS_nb']*(int)$_POST['bilan_Pay_HVBAgHBS_prix']+(int)$_POST['bilan_Pay_HV_AcHBC_nb']*(int)$_POST['bilan_Pay_HV_AcHBC_prix']+(int)$_POST['bilan_Pay_HVC_nb']*(int)$_POST['bilan_Pay_HVC_prix']+(int)$_POST['bilan_Pay_HIV_nb']*(int)$_POST['bilan_Pay_HIV_prix']+(int)$_POST['bilan_Pay_HLA_B27_nb']*(int)$_POST['bilan_Pay_HLA_B27_prix']+(int)$_POST['bilan_Pay_Ionogramme_nb']*(int)$_POST['bilan_Pay_Ionogramme_prix']+(int)$_POST['bilan_Pay_IDR_nb']*(int)$_POST['bilan_Pay_IDR_prix']+(int)$_POST['bilan_Pay_PAL_nb']*(int)$_POST['bilan_Pay_PAL_prix']+(int)$_POST['bilan_Pay_VS_nb']*(int)$_POST['bilan_Pay_VS_prix']+(int)$_POST['bilan_Pay_CRP_nb']*(int)$_POST['bilan_Pay_CRP_prix']+(int)$_POST['bilan_Pay_NFS_nb']*(int)$_POST['bilan_Pay_NFS_prix']+(int)$_POST['bilan_Pay_Quantiferon_nb']*(int)$_POST['bilan_Pay_Quantiferon_prix'];

            $line = $line.
                #Coûtdes bilans réalisés en public payé par l’O.G:
                (string)$bio_public.';'.
    $_POST['bilan_bio_public_prix'].';'.
                #Coûtdes bilans réalisés en public payé par l’O.G:
                (string)($bio_public-(int)$_POST['bilan_bio_public_prix']).

    $_POST['bilan_prv_ASAT_nb'].';'.
    $_POST['bilan_prv_ASAT_prix'].';'.
                (string)((int)$_POST['bilan_prv_ASAT_nb']*(int)$_POST['bilan_prv_ASAT_prix']).';'.
    $_POST['bilan_prv_ALAT_nb'].';'.
    $_POST['bilan_prv_ALAT_prix'].';'.
                            (string)((int)$_POST['bilan_prv_ALAT_nb']*(int)$_POST['bilan_prv_ALAT_prix']).';'.
    $_POST['bilan_prv_Albumine_nb'].';'.
    $_POST['bilan_prv_Albumine_prix'].';'.
                            (string)((int)$_POST['bilan_prv_Albumine_nb']*(int)$_POST['bilan_prv_Albumine_prix']).';'.
    $_POST['bilan_prv_AntiCCP_nb'].';'.
    $_POST['bilan_prv_AntiCCP_prix'].';'.
                            (string)((int)$_POST['bilan_prv_AntiCCP_nb']*(int)$_POST['bilan_prv_AntiCCP_prix']).';'.
    $_POST['bilan_prv_AAN_nb'].';'.
    $_POST['bilan_prv_AAN_prix'].';'.
                            (string)((int)$_POST['bilan_prv_AAN_nb']*(int)$_POST['bilan_prv_AAN_prix']).';'.
    $_POST['bilan_prv_AntiECT_nb'].';'.
    $_POST['bilan_prv_AntiECT_prix'].';'.
                            (string)((int)$_POST['bilan_prv_AntiECT_nb']*(int)$_POST['bilan_prv_AntiECT_prix']).';'.
    $_POST['bilan_prv_ACPA_nb'].';'.
    $_POST['bilan_prv_ACPA_prix'].';'.
                            (string)((int)$_POST['bilan_prv_ACPA_nb']*(int)$_POST['bilan_prv_ACPA_prix']).';'.
    $_POST['bilan_prv_CholesterolTotal_nb'].';'.
    $_POST['bilan_prv_CholesterolTotal_prix'].';'.
                            (string)((int)$_POST['bilan_prv_CholesterolTotal_nb']*(int)$_POST['bilan_prv_CholesterolTotal_prix']).';'.
    $_POST['bilan_prv_ECBU_nb'].';'.
    $_POST['bilan_prv_ECBU_prix'].';'.
                            (string)((int)$_POST['bilan_prv_ECBU_nb']*(int)$_POST['bilan_prv_ECBU_prix']).';'.
    $_POST['bilan_prv_FR_nb'].';'.
    $_POST['bilan_prv_FR_prix'].';'.
                            (string)((int)$_POST['bilan_prv_FR_nb']*(int)$_POST['bilan_prv_FR_prix']).';'.
    $_POST['bilan_prv_HDL_nb'].';'.
    $_POST['bilan_prv_HDL_prix'].';'.
                            (string)((int)$_POST['bilan_prv_HDL_nb']*(int)$_POST['bilan_prv_HDL_prix']).';'.
    $_POST['bilan_prv_LDL_nb'].';'.
    $_POST['bilan_prv_LDL_prix'].';'.
                            (string)((int)$_POST['bilan_prv_LDL_nb']*(int)$_POST['bilan_prv_LDL_prix']).';'.
    $_POST['bilan_prv_Triglycerides_nb'].';'.
    $_POST['bilan_prv_Triglycerides_prix'].';'.
                            (string)((int)$_POST['bilan_prv_Triglycerides_nb']*(int)$_POST['bilan_prv_Triglycerides_prix']).';'.
    $_POST['bilan_prv_Creatinine_nb'].';'.
    $_POST['bilan_prv_Creatinine_prix'].';'.
                            (string)((int)$_POST['bilan_prv_Creatinine_nb']*(int)$_POST['bilan_prv_Creatinine_prix']).';'.
    $_POST['bilan_prv_Dosage_des_IgA_IgGIgM_nb'].';'.
    $_POST['bilan_prv_Dosage_des_IgA_IgGIgM_prix'].';'.
                            (string)((int)$_POST['bilan_prv_Dosage_des_IgA_IgGIgM_nb']*(int)$_POST['bilan_prv_Dosage_des_IgA_IgGIgM_prix']).';'.
    $_POST['bilan_prv_EPP_nb'].';'.
    $_POST['bilan_prv_EPP_prix'].';'.
                            (string)((int)$_POST['bilan_prv_EPP_nb']*(int)$_POST['bilan_prv_EPP_prix']).';'.
    $_POST['bilan_prv_GGT_nb'].';'.
    $_POST['bilan_prv_GGT_prix'].';'.
                            (string)((int)$_POST['bilan_prv_GGT_nb']*(int)$_POST['bilan_prv_GGT_prix']).';'.
    $_POST['bilan_prv_Glycemie_nb'].';'.
    $_POST['bilan_prv_Glycemie_prix'].';'.
                            (string)((int)$_POST['bilan_prv_Glycemie_nb']*(int)$_POST['bilan_prv_Glycemie_prix']).';'.
    $_POST['bilan_prv_HbA1C_nb'].';'.
    $_POST['bilan_prv_HbA1C_prix'].';'.
                            (string)((int)$_POST['bilan_prv_HbA1C_nb']*(int)$_POST['bilan_prv_HbA1C_prix']).';'.
    $_POST['bilan_prv_HVA_nb'].';'.
    $_POST['bilan_prv_HVA_prix'].';'.
                            (string)((int)$_POST['bilan_prv_HVA_nb']*(int)$_POST['bilan_prv_HVA_prix']).';'.
    $_POST['bilan_prv_HVBAgHBS_nb'].';'.
    $_POST['bilan_prv_HVBAgHBS_prix'].';'.
                            (string)((int)$_POST['bilan_prv_HVBAgHBS_nb']*(int)$_POST['bilan_prv_HVBAgHBS_prix']).';'.
    $_POST['bilan_prv_HV_AcHBC_nb'].';'.
    $_POST['bilan_prv_HV_AcHBC_prix'].';'.
                            (string)((int)$_POST['bilan_prv_HV_AcHBC_nb']*(int)$_POST['bilan_prv_HV_AcHBC_prix']).';'.
    $_POST['bilan_prv_HVC_nb'].';'.
    $_POST['bilan_prv_HVC_prix'].';'.
                            (string)((int)$_POST['bilan_prv_HVC_nb']*(int)$_POST['bilan_prv_HVC_prix']).';'.
    $_POST['bilan_prv_HIV_nb'].';'.
    $_POST['bilan_prv_HIV_prix'].';'.
                            (string)((int)$_POST['bilan_prv_HIV_nb']*(int)$_POST['bilan_prv_HIV_prix']).';'.
    $_POST['bilan_prv_HLA_B27_nb'].';'.
    $_POST['bilan_prv_HLA_B27_prix'].';'.
                            (string)((int)$_POST['bilan_prv_HLA_B27_nb']*(int)$_POST['bilan_prv_HLA_B27_prix']).';'.
    $_POST['bilan_prv_Ionogramme_nb'].';'.
    $_POST['bilan_prv_Ionogramme_prix'].';'.
                            (string)((int)$_POST['bilan_prv_Ionogramme_nb']*(int)$_POST['bilan_prv_Ionogramme_prix']).';'.
    $_POST['bilan_prv_IDR_nb'].';'.
    $_POST['bilan_prv_IDR_prix'].';'.
                            (string)((int)$_POST['bilan_prv_IDR_nb']*(int)$_POST['bilan_prv_IDR_prix']).';'.
    $_POST['bilan_prv_PAL_nb'].';'.
    $_POST['bilan_prv_PAL_prix'].';'.
                            (string)((int)$_POST['bilan_prv_PAL_nb']*(int)$_POST['bilan_prv_PAL_prix']).';'.
    $_POST['bilan_prv_VS_nb'].';'.
    $_POST['bilan_prv_VS_prix'].';'.
                            (string)((int)$_POST['bilan_prv_VS_nb']*(int)$_POST['bilan_prv_VS_prix']).';'.
    $_POST['bilan_prv_CRP_nb'].';'.
    $_POST['bilan_prv_CRP_prix'].';'.
                            (string)((int)$_POST['bilan_prv_CRP_nb']*(int)$_POST['bilan_prv_CRP_prix']).';'.
    $_POST['bilan_prv_NFS_nb'].';'.
    $_POST['bilan_prv_NFS_prix'].';'.
                            (string)((int)$_POST['bilan_prv_NFS_nb']*(int)$_POST['bilan_prv_NFS_prix']).';'.
    $_POST['bilan_prv_Quantiferon_nb'].';'.
    $_POST['bilan_prv_Quantiferon_prix'].';'.
            (string)((int)$_POST['bilan_prv_Quantiferon_nb']*(int)$_POST['bilan_prv_Quantiferon_prix']).';';

            $bio_prv =(int)$_POST['bilan_prv_ASAT_nb']*(int)$_POST['bilan_prv_ASAT_prix']+(int)$_POST['bilan_prv_ALAT_nb']*(int)$_POST['bilan_prv_ALAT_prix']+(int)$_POST['bilan_prv_Albumine_nb']*(int)$_POST['bilan_prv_Albumine_prix']+(int)$_POST['bilan_prv_AntiCCP_nb']*(int)$_POST['bilan_prv_AntiCCP_prix']+(int)$_POST['bilan_prv_AAN_nb']*(int)$_POST['bilan_prv_AAN_prix']+(int)$_POST['bilan_prv_AntiECT_nb']*(int)$_POST['bilan_prv_AntiECT_prix']+(int)$_POST['bilan_prv_ACPA_nb']*(int)$_POST['bilan_prv_ACPA_prix']+(int)$_POST['bilan_prv_CholesterolTotal_nb']*(int)$_POST['bilan_prv_CholesterolTotal_prix']+(int)$_POST['bilan_prv_ECBU_nb']*(int)$_POST['bilan_prv_ECBU_prix']+(int)$_POST['bilan_prv_FR_nb']*(int)$_POST['bilan_prv_FR_prix']+(int)$_POST['bilan_prv_HDL_nb']*(int)$_POST['bilan_prv_HDL_prix']+(int)$_POST['bilan_prv_LDL_nb']*(int)$_POST['bilan_prv_LDL_prix']+(int)$_POST['bilan_prv_Triglycerides_nb']*(int)$_POST['bilan_prv_Triglycerides_prix']+(int)$_POST['bilan_prv_Creatinine_nb']*(int)$_POST['bilan_prv_Creatinine_prix']+(int)$_POST['bilan_prv_Dosage_des_IgA_IgGIgM_nb']*(int)$_POST['bilan_prv_Dosage_des_IgA_IgGIgM_prix']+(int)$_POST['bilan_prv_EPP_nb']*(int)$_POST['bilan_prv_EPP_prix']+(int)$_POST['bilan_prv_GGT_nb']*(int)$_POST['bilan_prv_GGT_prix']+(int)$_POST['bilan_prv_Glycemie_nb']*(int)$_POST['bilan_prv_Glycemie_prix']+(int)$_POST['bilan_prv_HbA1C_nb']*(int)$_POST['bilan_prv_HbA1C_prix']+(int)$_POST['bilan_prv_HVA_nb']*(int)$_POST['bilan_prv_HVA_prix']+(int)$_POST['bilan_prv_HVBAgHBS_nb']*(int)$_POST['bilan_prv_HVBAgHBS_prix']+(int)$_POST['bilan_prv_HV_AcHBC_nb']*(int)$_POST['bilan_prv_HV_AcHBC_prix']+(int)$_POST['bilan_prv_HVC_nb']*(int)$_POST['bilan_prv_HVC_prix']+(int)$_POST['bilan_prv_HIV_nb']*(int)$_POST['bilan_prv_HIV_prix']+(int)$_POST['bilan_prv_HLA_B27_nb']*(int)$_POST['bilan_prv_HLA_B27_prix']+(int)$_POST['bilan_prv_Ionogramme_nb']*(int)$_POST['bilan_prv_Ionogramme_prix']+(int)$_POST['bilan_prv_IDR_nb']*(int)$_POST['bilan_prv_IDR_prix']+(int)$_POST['bilan_prv_PAL_nb']*(int)$_POST['bilan_prv_PAL_prix']+(int)$_POST['bilan_prv_VS_nb']*(int)$_POST['bilan_prv_VS_prix']+(int)$_POST['bilan_prv_CRP_nb']*(int)$_POST['bilan_prv_CRP_prix']+(int)$_POST['bilan_prv_NFS_nb']*(int)$_POST['bilan_prv_NFS_prix']+(int)$_POST['bilan_prv_Quantiferon_nb']*(int)$_POST['bilan_prv_Quantiferon_prix'];
    $line = $line.(string)$bio_prv.
        $_POST['Bio_Privees_rembourse'].';'.
        $_POST['Bio_Privees_rembourse_prix'].';'.
        (string)($bio_prv-(int)$_POST['Bio_Privees_rembourse_prix']).

        $_POST['trt_mdc_dmdrs'].';'.
        $_POST['trt_mdc_dmdrs_si_oui'].';'.
        $_POST['trt_mdc_dmdrs_cout_dune_prise'].';'.
        $_POST['trt_mdc_dmdrs_cout_mensuel'].';'.
        (string)((int)$_POST['trt_mdc_dmdrs_cout_mensuel']*6).';'.
        $_POST['trt_mdc_dmdrs_rembourse'].';'.
        $_POST['trt_mdc_dmdrs_rembourse_cout_paye'].';'.
        (string)((int)$_POST['trt_mdc_dmdrs_cout_mensuel']*6-(int)$_POST['trt_mdc_dmdrs_rembourse_cout_paye']).


        $_POST['trt_mdc_bdmdrs'].';'.
        $_POST['trt_mdc_bdmdrs_si_oui'].';'.
        $_POST['trt_mdc_bdmdrs_cout_dune_prise'].';'.
        $_POST['trt_mdc_bdmdrs_cout_mensuel'].';'.
        (string)((int)$_POST['trt_mdc_bdmdrs_cout_mensuel']*6).';'.
        $_POST['trt_mdc_bdmdrs_rembourse'].';'.
        $_POST['trt_mdc_bdmdrs_rembourse_cout_paye'].';'.
        (string)((int)$_POST['trt_mdc_bdmdrs_cout_mensuel']*6-(int)$_POST['trt_mdc_bdmdrs_rembourse_cout_paye']).

        $_POST['trt_mdc_ains_nom_1'].';'.
        $_POST['trt_mdc_ains_frq_1'].';'.
        $_POST['trt_mdc_ains_cout_1'].';'.
        $_POST['trt_mdc_ains_cout_mns_1'].';'.
        (string)((int)$_POST['trt_mdc_ains_cout_mns_1']*6).';'.

        $_POST['trt_mdc_ains_nom_2'].';'.
        $_POST['trt_mdc_ains_frq_2'].';'.
        $_POST['trt_mdc_ains_cout_2'].';'.
        $_POST['trt_mdc_ains_cout_mns_2'].';'.
        (string)((int)$_POST['trt_mdc_ains_cout_mns_2']*6).';'.

        $_POST['trt_mdc_ains_nom_3'].';'.
        $_POST['trt_mdc_ains_frq_3'].';'.
        $_POST['trt_mdc_ains_cout_3'].';'.
        $_POST['trt_mdc_ains_cout_mns_3'].';'.
        (string)((int)$_POST['trt_mdc_ains_cout_mns_3']*6).';'.

        $_POST['trt_mdc_ains_nom_4'].';'.
        $_POST['trt_mdc_ains_frq_4'].';'.
        $_POST['trt_mdc_ains_cout_4'].';'.
        $_POST['trt_mdc_ains_cout_mns_4'].';'.
        (string)((int)$_POST['trt_mdc_ains_cout_mns_4']*6).';'.

        $_POST['trt_mdc_ains_nom_5'].';'.
        $_POST['trt_mdc_ains_frq_5'].';'.
        $_POST['trt_mdc_ains_cout_5'].';'.
        $_POST['trt_mdc_ains_cout_mns_5'].';'.
        (string)((int)$_POST['trt_mdc_ains_cout_mns_5']*6).';'.
        (string)(((int)$_POST['trt_mdc_ains_cout_mns_1']+(int)$_POST['trt_mdc_ains_cout_mns_2']+(int)$_POST['trt_mdc_ains_cout_mns_3']+(int)$_POST['trt_mdc_ains_cout_mns_4']+(int)$_POST['trt_mdc_ains_cout_mns_5'])*6).';'.

        $_POST['trt_mdc_ains_rembourse'].';'.
        $_POST['trt_mdc_ains_rembourse_cout_paye'].';'.
        (string)(((int)$_POST['trt_mdc_ains_cout_mns_1']+(int)$_POST['trt_mdc_ains_cout_mns_2']+(int)$_POST['trt_mdc_ains_cout_mns_3']+(int)$_POST['trt_mdc_ains_cout_mns_4']+(int)$_POST['trt_mdc_ains_cout_mns_5'])*6-(int)$_POST['trt_mdc_ains_rembourse_cout_paye']).

#churg

        $_POST['churg_Nature_1'].';'.
        $_POST['churg_Lieux_1'].';'.
        $_POST['churg_cout_1'].';'.
        $_POST['churg_date_1'].';'.

        $_POST['churg_Nature_2'].';'.
        $_POST['churg_Lieux_2'].';'.
        $_POST['churg_cout_2'].';'.
        $_POST['churg_date_2'].';'.

        $_POST['churg_Nature_3'].';'.
        $_POST['churg_Lieux_3'].';'.
        $_POST['churg_cout_3'].';'.
        $_POST['churg_date_3'].';'.

        $_POST['churg_Nature_4'].';'.
        $_POST['churg_Lieux_4'].';'.
        $_POST['churg_cout_4'].';'.
        $_POST['churg_date_4'].';'.

        $_POST['churg_Nature_5'].';'.
        $_POST['churg_Lieux_5'].';'.
        $_POST['churg_cout_5'].';'.
        $_POST['churg_date_5'].';';

        $chirurgies_pc = 0;
        $chirurgies_prv = 0;
        if ($_POST['type_chrg_1']=='Publique'){$chirurgies_pc = $chirurgies_pc + (int)$_POST['churg_cout_1'];} else{$chirurgies_prv = $chirurgies_prv + (int)$_POST['churg_cout_1'];}
        if ($_POST['type_chrg_2']=='Publique'){$chirurgies_pc = $chirurgies_pc + (int)$_POST['churg_cout_2'];} else{$chirurgies_prv = $chirurgies_prv + (int)$_POST['churg_cout_2'];}
        if ($_POST['type_chrg_3']=='Publique'){$chirurgies_pc = $chirurgies_pc + (int)$_POST['churg_cout_3'];} else{$chirurgies_prv = $chirurgies_prv + (int)$_POST['churg_cout_3'];}
        if ($_POST['type_chrg_4']=='Publique'){$chirurgies_pc = $chirurgies_pc + (int)$_POST['churg_cout_4'];} else{$chirurgies_prv = $chirurgies_prv + (int)$_POST['churg_cout_4'];}
        if ($_POST['type_chrg_5']=='Publique'){$chirurgies_pc = $chirurgies_pc + (int)$_POST['churg_cout_5'];} else{$chirurgies_prv = $chirurgies_prv + (int)$_POST['churg_cout_5'];}
        $line = $line.
            (string)$chirurgies_pc.";".
            $_POST['churg_Publique_cout_paye'].';'.
            (string)($chirurgies_pc - (int)$_POST['churg_Publique_cout_paye']).';'.
            (string)$chirurgies_prv.";".

        $_POST['churg_rembourse'].';'.
        $_POST['churg_rembourse_cout_paye'].';'.
            (string)($chirurgies_prv-(int)$_POST['churg_rembourse_cout_paye']).";".


            $_POST['appareil_1'].';'.
            $_POST['appareil_cout_1'].';'.
            $_POST['appareil_date_1'].';'.

            $_POST['appareil_2'].';'.
            $_POST['appareil_cout_2'].';'.
            $_POST['appareil_date_2'].';'.

            $_POST['appareil_3'].';'.
            $_POST['appareil_cout_3'].';'.
            $_POST['appareil_date_3'].';'.

            $_POST['appareil_4'].';'.
            $_POST['appareil_cout_4'].';'.
            $_POST['appareil_date_4'].';'.

            $_POST['appareil_5'].';'.
            $_POST['appareil_cout_5'].';'.
            $_POST['appareil_date_5'].';';
            $ttl_apr = (int)$_POST['appareil_cout_1']+(int)$_POST['appareil_cout_2']+(int)$_POST['appareil_cout_3']+(int)$_POST['appareil_cout_4']+(int)$_POST['appareil_cout_5'];
            $line = $line. (string)$ttl_apr.';'.
            $_POST['appareils_cout_paye'].';'.
                (string)($ttl_apr-(int)$_POST['appareils_cout_paye']).';'.



            $_POST['kine_hey_nb'].';'.
            $_POST['kine_hey_prix'].';'.
                (string)((int)$_POST['kine_hey_prix']*(int)$_POST['kine_hey_nb']).';'.
            $_POST['kine_hey_d_sem'].';'.
            $_POST['kine_hey_pay_pat'].';'.
                (string)((int)$_POST['kine_hey_d_sem']-(int)$_POST['kine_hey_pay_pat']).';'.
            #Au sein d’une structure privée
                $_POST['kine_prv_nb'].';'.
                $_POST['kine_prv_prix'].';'.
                (string)((int)$_POST['kine_prv_nb']*(int)$_POST['kine_prv_prix']).';'.

                $_POST['kine_rembourse'].';'.
                $_POST['Kine_rembourse_cout'].';'.
                    (string)((int)$_POST['kine_prv_nb']*(int)$_POST['kine_prv_prix']-(int)$_POST['Kine_rembourse_cout']).';'.
                $_POST['trs_ville_dahib'].';'.
                $_POST['cout_dep_1'].';'.
                $_POST['cout_dep_2'].';'.
                $_POST['cout_dep_3'].';'.
                (string)((int)$_POST['cout_dep_1']*(int)$_POST['cout_dep_2']*(int)$_POST['cout_dep_3']).';'.
                $_POST['cout_dep_autre_1'].';'.
                $_POST['cout_dep_autre_2'].';'.
                $_POST['cout_dep_autre_3'].';'.
                (string)((int)$_POST['cout_dep_autre_1']*(int)$_POST['cout_dep_autre_2']*(int)$_POST['cout_dep_autre_3']).';'.
                #Coûtdu transport payé par patient :
                (string)((int)$_POST['kine_prv_nb']*(int)$_POST['kine_prv_prix']-(int)$_POST['Kine_rembourse_cout']+(int)$_POST['cout_dep_autre_1']*(int)$_POST['cout_dep_autre_2']*(int)$_POST['cout_dep_autre_3']).';'
            ;

//        Total des coût

        $Answer->setAnswer($line);
        $Answer->setIsActive(1);

        $em->persist($Answer);
        $em->flush();

        return new Response(
            '<html><body>'.$line .'</body></html>'
        );


    }




    /**
     * @Route("/survey/pharmacoepido/calcul", name="pharmacoepidoCalcul")
     */
    public function Survey_pharmacoepido_Calcul()
    {
        $Answer = new Answer();

        $Patient = new Patient();
        $em = $this->getDoctrine()->getManager();
        $Patient = $this->getDoctrine()
            ->getRepository(Patient::class)
            ->find($_POST["patient_id"]);
        $Answer->setPatient($Patient);


        $Survey = new Survey();
        $em = $this->getDoctrine()->getManager();
        $Survey = $this->getDoctrine()
            ->getRepository(Survey::class)
            ->find(3);
        $Answer->setSurvey($Survey);

        $Answer->setDate(date("D M d, Y G:i"));
        $line = $Patient->getNom().';'.$Patient->getNumerotel().';'.$Patient->getNatureMaladie().';'.$Patient->getNaissanceJour().';'.$Patient->getNaissanceMois().';'.$Patient->getNaissanceAnnee().';'.$Patient->getBiotherapieActuelle().';'.$Patient->getNumEntre().';'.$Patient->getNumInclu().';'.$Patient->getSexe().';'.$Patient->getDateInclusion().';'.$Patient->getPoids().';'.$Patient->getTaille().';'.$Patient->getNivEtude().';'.$Patient->getSituationMat().';'.$Patient->getNbEnf().';'.$Patient->getType().';'.$Patient->getProfession().';'.$Patient->getVilee().';'.$Patient->getRuralUrbain().';'.$Patient->getSalarie().';'.$Patient->getRevenueDesMenages().';'.
$_POST['garavite_pr_date_debut'].';'.
$_POST['garavite_pr_date_diagno'].';'.
$_POST['garavite_pr_Séropositif'].';'.
$_POST['garavite_pr_Séronégatif'].';'.
$_POST['garavite_pr_DM'].';'.
$_POST['garavite_pr_Antecedent_de_Chirurgie'].';'.
$_POST['garavite_pr_Nb_Articulaires_1'].';'.
$_POST['garavite_pr_Nb_Articulaires_2'].';'.
$_POST['garavite_pr_lieu_Articulaires'].';'.
$_POST['garavite_pr_date_Articulaires'].';'.
$_POST['garavite_pr_Nb_Articulaires_douleureuse_1'].';'.
$_POST['garavite_pr_Nb_Articulaires_douleureuse_2'].';'.
$_POST['garavite_pr_Nb_Articulaires_gonf_1'].';'.
$_POST['garavite_pr_Nb_Articulaires_gonf_2'].';'.
$_POST['garavite_pr_VS1stH_1'].';'.
$_POST['garavite_pr_VS1stH_2'].';'.
$_POST['garavite_pr_CRP_1'].';'.
$_POST['garavite_pr_CRP_2'].';'.
$_POST['garavite_pr_CRP_3'].';'.
$_POST['garavite_pr_EVA_1'].';'.
$_POST['garavite_pr_EVA_2'].';'.
$_POST['garavite_pr_EVA_3'].';'.
$_POST['garavite_pr_EVA_Fatigue_1'].';'.
$_POST['garavite_pr_EVA_Fatigue_2'].';'.
$_POST['garavite_pr_EVA_Fatigue_3'].';'.
$_POST['garavite_pr_DAS28_1'].';'.
$_POST['garavite_pr_DAS28_2'].';'.
$_POST['garavite_pr_avant_Nb_Date'].';'.
$_POST['garavite_pr_avant_Nb_Articulaires_douleureuse_1'].';'.
$_POST['garavite_pr_avant_Nb_Articulaires_douleureuse_2'].';'.
$_POST['garavite_pr_avant_Nb_Articulaires_gonf_1'].';'.
$_POST['garavite_pr_avant_Nb_Articulaires_gonf_2'].';'.
$_POST['garavite_pr_avant_VS1stH_1'].';'.
$_POST['garavite_pr_avant_VS1stH_2'].';'.
$_POST['garavite_pr_avant_CRP_1'].';'.
$_POST['garavite_pr_avant_CRP_2'].';'.
$_POST['garavite_pr_avant_CRP_3'].';'.
$_POST['garavite_pr_avant_EVA_1'].';'.
$_POST['garavite_pr_avant_EVA_2'].';'.
$_POST['garavite_pr_avant_EVA_3'].';'.
$_POST['garavite_pr_avant_EVA_Fatigue_1'].';'.
$_POST['garavite_pr_avant_EVA_Fatigue_2'].';'.
$_POST['garavite_pr_avant_EVA_Fatigue_3'].';'.
$_POST['garavite_pr_avant_DAS28_1'].';'.
$_POST['garavite_pr_avant_DAS28_2'].';'.
$_POST['SPA_date_debut'].';'.
$_POST['SPA_date_diag'].';'.
$_POST['SPA_Antécédent_chirurgie_PR'].';'.
$_POST['SPA_remplaceemnt_aticulaires_1'].';'.
$_POST['SPA_remplaceemnt_aticulaires_2'].';'.
$_POST['SPA_lieu_Articulaires'].';'.
$_POST['SPA_date_Articulaires'].';'.
$_POST['SPA_Att_Axial'].';'.
$_POST['SPA_Art_per'].';'.
$_POST['SPA_psor_cut'].';'.
$_POST['SPA_att_occ'].';'.
$_POST['SPA_att_int'].';'.
$_POST['SPA_Nb_Articulaires_douleureuse_1'].';'.
$_POST['SPA_Nb_Articulaires_douleureuse_2'].';'.
$_POST['SPA_Nb_Articulaires_gonf_1'].';'.
$_POST['SPA_Nb_Articulaires_gonf_2'].';'.
$_POST['SPA_VS1stH_1'].';'.
$_POST['SPA_VS1stH_2'].';'.
$_POST['SPA_CRP_1'].';'.
$_POST['SPA_CRP_2'].';'.
$_POST['SPA_CRP_3'].';'.
$_POST['SPA_BASFI_1'].';'.
$_POST['SPA_BASFI_2'].';'.
$_POST['SPA_BASFI_3'].';'.
$_POST['SPA_BASDAI_1'].';'.
$_POST['SPA_BASDAI_2'].';'.
$_POST['SPA_BASDAI_3'].';'.
$_POST['SPA_avant_date'].';'.
$_POST['SPA_Avant_Att_Axial'].';'.
$_POST['SPA_Avant_Art_per'].';'.
$_POST['SPA_Avant_psor_cut'].';'.
$_POST['SPA_Avant_att_occ'].';'.
$_POST['SPA_Avant_att_int'].';'.
$_POST['SPA_Avant_Nb_Articulaires_douleureuse_1'].';'.
$_POST['SPA_Avant_Nb_Articulaires_douleureuse_2'].';'.
$_POST['SPA_Avant_Nb_Articulaires_gonf_1'].';'.
$_POST['SPA_Avant_Nb_Articulaires_gonf_2'].';'.
$_POST['SPA_Avant_VS1stH_1'].';'.
$_POST['SPA_Avant_VS1stH_2'].';'.
$_POST['SPA_Avant_CRP_1'].';'.
$_POST['SPA_Avant_CRP_2'].';'.
$_POST['SPA_Avant_CRP_3'].';'.
$_POST['SPA_Avant_BASFI_1'].';'.
$_POST['SPA_Avant_BASFI_2'].';'.
$_POST['SPA_Avant_BASFI_3'].';'.
$_POST['SPA_Avant_BASDAI_1'].';'.
$_POST['SPA_Avant_BASDAI_2'].';'.
$_POST['SPA_Avant_BASDAI_3'].';'.
$_POST['Biothérapie_1'].';'.
$_POST['Date_Biothérapie_1'].';'.
$_POST['Datearrêt_Biothérapie_1'].';'.
$_POST['Biothérapie_1'].';'.
$_POST['DMARDS_1'].';'.
$_POST['DMARDS_Nom_1'].';'.
$_POST['DMARDS_date_debut_1'].';'.
$_POST['DMARDS_date_arret_1'].';'.
$_POST['DMARDS_Motif_Arret_1'].';'.
$_POST['DMARDS_Nom_2'].';'.
$_POST['DMARDS_date_debut_2'].';'.
$_POST['DMARDS_date_arret_2'].';'.
$_POST['DMARDS_Motif_Arret_2'].';'.
$_POST['DMARDS_Nom_3'].';'.
$_POST['DMARDS_date_debut_3'].';'.
$_POST['DMARDS_date_arret_3'].';'.
$_POST['DMARDS_Motif_Arret_3'].';'.
$_POST['DMARDS_Nom_4'].';'.
$_POST['DMARDS_date_debut_4'].';'.
$_POST['DMARDS_date_arret_4'].';'.
$_POST['DMARDS_Motif_Arret_4'].';'.
$_POST['DMARDS_Nom_5'].';'.
$_POST['DMARDS_date_debut_5'].';'.
$_POST['DMARDS_date_arret_5'].';'.
$_POST['ComorbiditésDiabète'].';'.
$_POST['ComorbiditésDiabèteTTT'].';'.
$_POST['ComorbiditésHTA'].';'.
$_POST['ComorbiditésHTATTT'].';'.
$_POST['ComorbiditésHypercholestérolémie'].';'.
$_POST['ComorbiditésHypercholestérolémieTTT'].';'.
$_POST['ComorbiditésHypertriglycéridémie'].';'.
$_POST['ComorbiditésHypertriglycéridémieTTT'].';'.
$_POST['ComorbiditésAutre'].';'.
$_POST['ComorbiditésAutreTTT'].';'.
$_POST['Date_DC_Tuberculose'].';'.
$_POST['Datte_guérison_Tuberculose'].';'.
$_POST['Remarque_Hépatite_B'].';'.
$_POST['Date_DC_Hépatite_B'].';'.
$_POST['Datte_guérison_Hépatite_B'].';'.
$_POST['Remarque_Hépatite_C'].';'.
$_POST['Date_DC_Hépatite_C'].';'.
$_POST['Datte_guérison_Hépatite_C'].';'.
$_POST['Remarque_Réaction_cutanée'].';'.
$_POST['Date_DC_Réaction_cutanée'].';'.
$_POST['Datte_guérison_Réaction_cutanée'].';'.
$_POST['Remarque_Réaction_à_la_perfusion'].';'.
$_POST['Date_DC_Réaction_à_la_perfusion'].';'.
$_POST['Datte_guérison_Réaction_à_la_perfusion'].';'.
$_POST['Remarque_Réaction_après_la_perfusion'].';'.
$_POST['Date_DC_Réaction_après_la_perfusion'].';'.
$_POST['Datte_guérison_Réaction_après_la_perfusion'].';'.
$_POST['Remarque_Démyélinisation_cérébrale'].';'.
$_POST['Date_DC_Démyélinisation_cérébrale'].';'.
$_POST['Datte_guérison_Démyélinisation_cérébrale'].';'.
$_POST['Remarque_Autres'].';'.
$_POST['Date_DC_Autres'].';'.
$_POST['Datte_guérison_Autres'].';'.
$_POST['AutresDépression'].';'.
$_POST['AutresDépressionDate'].';'.
$_POST['AutresOstéoporose'].';'.
$_POST['AutresOstéoporoseDate'].';'.
$_POST['AutresFibromyalgie'].';'.
$_POST['AutresFibromyalgieDate'].';'.
$_POST['AutresAutres'].';'.
$_POST['AutresAutresDate'].';'.
$_POST['remarque_form'].';';
        $Answer->setAnswer($line);
        $Answer->setIsActive(1);

        $em->persist($Answer);
        $em->flush();

        return new Response(
            '<html><body>'.$line .'</body></html>'
        );


    }




    /**
     * @Route("/survey/pharmacoepide", name="pharmacoepide")
     */
    public function Survey_pharmacoepide()
    {
        $em = $this->getDoctrine()->getManager();
        $Patients = $this->getDoctrine()
            ->getRepository(Patient::class)
            ->findAll();
        return $this->render('survey/Questions/pharmacoepide.html.twig', [
            'patients' => $Patients,
        ]);
    }



    /**
     * @Route("/survey/coutindirect/calcul", name="coutindirectCalcul")
     */
    public function Survey_coutindirect_Calcul()
    {
        $Answer = new Answer();

        $Patient = new Patient();
        $em = $this->getDoctrine()->getManager();
        $Patient = $this->getDoctrine()
            ->getRepository(Patient::class)
            ->find($_POST["patient_id"]);
        $Answer->setPatient($Patient);


        $Survey = new Survey();
        $em = $this->getDoctrine()->getManager();
        $Survey = $this->getDoctrine()
            ->getRepository(Survey::class)
            ->find(2);
        $Answer->setSurvey($Survey);

        $Answer->setDate(date("D M d, Y G:i"));

        $line = $Patient->getNom().';'.$Patient->getNumerotel().';'.$Patient->getNatureMaladie().';'.$Patient->getNaissanceJour().';'.$Patient->getNaissanceMois().';'.$Patient->getNaissanceAnnee().';'.$Patient->getBiotherapieActuelle().';'.$Patient->getNumEntre().';'.$Patient->getNumInclu().';'.$Patient->getSexe().';'.$Patient->getDateInclusion().';'.$Patient->getPoids().';'.$Patient->getTaille().';'.$Patient->getNivEtude().';'.$Patient->getSituationMat().';'.$Patient->getNbEnf().';'.$Patient->getType().';'.$Patient->getProfession().';'.$Patient->getVilee().';'.$Patient->getRuralUrbain().';'.$Patient->getSalarie().';'.$Patient->getRevenueDesMenages().';'.
$_POST["salarie_sa"].';'.
$_POST["salarieProffesion"].';'.
$_POST["salarie_mensuel_en_dh_1"].';'.
$_POST["salarie_mensuel_en_dh_2"].';'.
$_POST["salarie_salariale"].';'.
$_POST["salarie_nb_abs_1"].';'.
$_POST["salarie_nb_abs_2"].';'.
$_POST["salarie_moy_abs_1"].';'.
$_POST["salarie_moy_abs_2"].';'.
$_POST["salarie_est_perte_abs_1"].';'.
$_POST["salarie_est_perte_abs_2"].';'.
$_POST["non_salarie_sa"].';'.
$_POST["NonsalarieProffesion"].';'.
$_POST["non_salarie_sa"].';'.
$_POST["Nonsalarie_rev_men"].';'.
$_POST["Nonsalarie_rev_men_act"].';'.
            (string)((int)$_POST["Nonsalarie_rev_men"]-(int)$_POST["Nonsalarie_rev_men_act"]).';'.
$_POST["non_salarie_tache_capable_tache_us"].';'.
$_POST["non_salarie_tache_exrc"].';'.
$_POST["Lequel"].';'.
$_POST["non_salarie_arret_maladie"].';'.
$_POST["non_salarie_cout_estme_maladie"].';'.
$_POST["tierce_necesiitee"].';'.
$_POST["benevole"].';'.
$_POST["rémunérée"].';'.
$_POST["a_Combien"].';'.
$_POST["lien_parenté"].';'.
$_POST["quittéactivité"].';'.
$_POST["quittéactivitéLaquelle"].';'.
$_POST["Coûtestimédépenséetiercepersonne"].';'.
$_POST["commentaires"].';';

        $Answer->setAnswer($line);
        $Answer->setIsActive(1);

        $em->persist($Answer);
        $em->flush();

        return new Response(
            '<html><body>'.$line .'</body></html>'
        );


    }





    /**
     * @Route("/survey/arabic", name="arabic")
     */
    public function Survey_arabic()
    {
        $em = $this->getDoctrine()->getManager();
        $Patients = $this->getDoctrine()
            ->getRepository(Patient::class)
            ->findAll();
        return $this->render('survey/Questions/arabic.html.twig', [
            'patients' => $Patients,
        ]);
    }


    /**
     * @Route("/survey/arabic/calcul", name="arabicCalcul")
     */
    public function Survey_arabic_Calcul()
    {
        $Answer = new Answer();

        $Patient = new Patient();
        $em = $this->getDoctrine()->getManager();
        $Patient = $this->getDoctrine()
            ->getRepository(Patient::class)
            ->find($_POST["patient_id"]);
        $Answer->setPatient($Patient);


        $Survey = new Survey();
        $em = $this->getDoctrine()->getManager();
        $Survey = $this->getDoctrine()
            ->getRepository(Survey::class)
            ->find(4);
        $Answer->setSurvey($Survey);

        $Answer->setDate(date("D M d, Y G:i"));

        $line = $Patient->getNom().';'.$Patient->getNumerotel().';'.$Patient->getNatureMaladie().';'.$Patient->getNaissanceJour().';'.$Patient->getNaissanceMois().';'.$Patient->getNaissanceAnnee().';'.$Patient->getBiotherapieActuelle().';'.$Patient->getNumEntre().';'.$Patient->getNumInclu().';'.$Patient->getSexe().';'.$Patient->getDateInclusion().';'.$Patient->getPoids().';'.$Patient->getTaille().';'.$Patient->getNivEtude().';'.$Patient->getSituationMat().';'.$Patient->getNbEnf().';'.$Patient->getType().';'.$Patient->getProfession().';'.$Patient->getVilee().';'.$Patient->getRuralUrbain().';'.$Patient->getSalarie().';'.$Patient->getRevenueDesMenages().';'.
            $_POST["1"].';'.
            $_POST["2"].';'.
            $_POST["3"].';'.
            $_POST["4"].';'.
            $_POST["5"].';';

        $Answer->setAnswer($line);
        $Answer->setIsActive(1);

        $em->persist($Answer);
        $em->flush();

        return new Response(
            '<html><body>'.$line .'</body></html>'
        );


    }
}
