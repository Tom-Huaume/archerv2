<?php

namespace App\Service;

use App\Entity\Membre;
use App\Repository\MembreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdateManager
{
    protected $membreRepository;
    protected $entityManager;
    protected $passwordHasher;

    public function __construct(
        MembreRepository $membreRepository,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    )
    {
        $this->membreRepository = $membreRepository;
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @throws Exception
     */
    public function updateMembresParTableur(
        String $fileName,
        String $uploads_directory
    )
    {


        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $fichier = $uploads_directory.DIRECTORY_SEPARATOR.$fileName;
        //$fichier = 'import'. DIRECTORY_SEPARATOR .'test.xlsx';


        $reader = new Csv();
        $spreadsheet = $reader->load($fichier);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        $lisCatAge = array(
            "S1" => "Sénior 1",
            "S2" => "Sénior 2",
            "S3" => "Sénior 3",
            "B" => "Benjamin",
            "C" => "Cadet",
            "J" => "Junior",
            "M" => "Minime",
            "P" => "Poussin"
        );


        for($i=1; $i<count($sheetData); $i++) {

            //Récupérer valeurs
            $statutLicence = $sheetData[$i][1];
            ($statutLicence == "Actif") ? $statutLicence=True : $statutLicence=False;
            $numLicence = $sheetData[$i][3];

            //Check si ce membre existe déjà dans la base
            $membreClub = $this->membreRepository->findOneBy(array('numLicence' => $numLicence));

            //Si déjà dans la base on MAJ le statut de licence
            if($membreClub != null){
                $membreClub->setStatutLicence($statutLicence);
                $this->entityManager->persist($membreClub);
                $this->entityManager->flush();
            }

            if($membreClub == null){
                //Si pas dans la base on récupére les autres champes et on l'ajoute
                $nom = $sheetData[$i][5];
                $prenom = $sheetData[$i][6];
                $dateNaissance = $sheetData[$i][7];
                $sexe = $sheetData[$i][9];
                $typeLicence = $sheetData[$i][28];
                $catAge = $sheetData[$i][45];
                if($catAge == false){
                    $catAge = $lisCatAge[$sheetData[$i][44]];
                }
                $email = $sheetData[$i][64];
                $tel = $sheetData[$i][66];

                //Créer nouveau membre
                $membre = new Membre();
                $membre->setNumLicence($numLicence);
                $membre->setNom($nom);
                $membre->setSexe($sexe);
                if($statutLicence == "Actif"){
                    $membre->setStatutLicence(1);
                }else{
                    $membre->setStatutLicence(0);
                }
                $dateFormat = \DateTime::createFromFormat('m/d/Y', $dateNaissance);
                $membre->setDateNaissance($dateFormat);
                $membre->setTypeLicence($typeLicence);
                $membre->setCategorieAge($catAge);
                $membre->setPrenom($prenom);
                $membre->setTelMobile(str_replace(' ', '', $tel));
                $membre->setEmail($email);
                $membre->setRoles(array('ROLE_USER'));
                $membre->setPassword($this->passwordHasher->hashPassword($membre, random_bytes(200)));
                $this->entityManager->persist($membre);
                $this->entityManager->flush();
            }

        }


    }
}