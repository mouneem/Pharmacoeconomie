<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PatientRepository")
 */
class Patient
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $numerotel;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Answer", mappedBy="patient")
     */
    private $answers;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $naissance_jour;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $naissance_mois;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $naissance_annee;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $biotherapie_actuelle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Num_inclu;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sexe;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $date_inclusion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $poids;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $taille;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Niv_etude;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Situation_mat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nb_enf;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $profession;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $vilee;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Rural_urbain;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Salarie;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Revenue_des_menages;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $num_entre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Nature_Maladie;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getNumerotel(): ?string
    {
        return $this->numerotel;
    }

    public function setNumerotel(?string $numerotel): self
    {
        $this->numerotel = $numerotel;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(?string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Answer[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setPatient($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->contains($answer)) {
            $this->answers->removeElement($answer);
            // set the owning side to null (unless already changed)
            if ($answer->getPatient() === $this) {
                $answer->setPatient(null);
            }
        }

        return $this;
    }

    public function getNaissanceJour(): ?string
    {
        return $this->naissance_jour;
    }

    public function setNaissanceJour(?string $naissance_jour): self
    {
        $this->naissance_jour = $naissance_jour;

        return $this;
    }

    public function getNaissanceMois(): ?string
    {
        return $this->naissance_mois;
    }

    public function setNaissanceMois(?string $naissance_mois): self
    {
        $this->naissance_mois = $naissance_mois;

        return $this;
    }

    public function getNaissanceAnnee(): ?string
    {
        return $this->naissance_annee;
    }

    public function setNaissanceAnnee(?string $naissance_annee): self
    {
        $this->naissance_annee = $naissance_annee;

        return $this;
    }

    public function getBiotherapieActuelle(): ?string
    {
        return $this->biotherapie_actuelle;
    }

    public function setBiotherapieActuelle(?string $biotherapie_actuelle): self
    {
        $this->biotherapie_actuelle = $biotherapie_actuelle;

        return $this;
    }

    public function getNumInclu(): ?string
    {
        return $this->Num_inclu;
    }

    public function setNumInclu(?string $Num_inclu): self
    {
        $this->Num_inclu = $Num_inclu;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(?string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getDateInclusion(): ?string
    {
        return $this->date_inclusion;
    }

    public function setDateInclusion(?string $date_inclusion): self
    {
        $this->date_inclusion = $date_inclusion;

        return $this;
    }

    public function getPoids(): ?string
    {
        return $this->poids;
    }

    public function setPoids(?string $poids): self
    {
        $this->poids = $poids;

        return $this;
    }

    public function getTaille(): ?string
    {
        return $this->taille;
    }

    public function setTaille(?string $taille): self
    {
        $this->taille = $taille;

        return $this;
    }

    public function getNivEtude(): ?string
    {
        return $this->Niv_etude;
    }

    public function setNivEtude(?string $Niv_etude): self
    {
        $this->Niv_etude = $Niv_etude;

        return $this;
    }

    public function getSituationMat(): ?string
    {
        return $this->Situation_mat;
    }

    public function setSituationMat(?string $Situation_mat): self
    {
        $this->Situation_mat = $Situation_mat;

        return $this;
    }

    public function getNbEnf(): ?string
    {
        return $this->nb_enf;
    }

    public function setNbEnf(?string $nb_enf): self
    {
        $this->nb_enf = $nb_enf;

        return $this;
    }

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(?string $profession): self
    {
        $this->profession = $profession;

        return $this;
    }

    public function getVilee(): ?string
    {
        return $this->vilee;
    }

    public function setVilee(?string $vilee): self
    {
        $this->vilee = $vilee;

        return $this;
    }

    public function getRuralUrbain(): ?string
    {
        return $this->Rural_urbain;
    }

    public function setRuralUrbain(?string $Rural_urbain): self
    {
        $this->Rural_urbain = $Rural_urbain;

        return $this;
    }

    public function getSalarie(): ?string
    {
        return $this->Salarie;
    }

    public function setSalarie(?string $Salarie): self
    {
        $this->Salarie = $Salarie;

        return $this;
    }

    public function getRevenueDesMenages(): ?string
    {
        return $this->Revenue_des_menages;
    }

    public function setRevenueDesMenages(?string $Revenue_des_menages): self
    {
        $this->Revenue_des_menages = $Revenue_des_menages;

        return $this;
    }

    public function getNumEntre(): ?string
    {
        return $this->num_entre;
    }

    public function setNumEntre(?string $num_entre): self
    {
        $this->num_entre = $num_entre;

        return $this;
    }

    public function getNatureMaladie(): ?string
    {
        return $this->Nature_Maladie;
    }

    public function setNatureMaladie(?string $Nature_Maladie): self
    {
        $this->Nature_Maladie = $Nature_Maladie;

        return $this;
    }



}
