<?php

namespace App\ExcelService;

use App\Entity\Membre;
use App\Repository\MembreRepository;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class XlsManager
{
    protected $membreRepository;

    public function __construct(
        MembreRepository $membreRepository
    )
    {
        $this->membreRepository = $membreRepository;
    }

    public function uploadExcelData()
    {

        $fichier = 'import'. DIRECTORY_SEPARATOR .'test.xlsx';

        $reader = new Xlsx();
        $spreadsheet = $reader->load($fichier);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();




        for($i=1; $i<count($sheetData); $i++) {

            //Récupérer valeurs
            $numLicence = $sheetData[$i][0];
            $nom = $sheetData[$i][1];
            $sexe = $sheetData[$i][2];
            $statutLicence = $sheetData[$i][4];
            $dateNaissance = $sheetData[$i][5];
            $typeLicence = $sheetData[$i][7];
            $catAge = $sheetData[$i][8];

            //Check si existe déjà
            $membreClub = $this->membreRepository->findOneBy(array('numLicence' => $numLicence));


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
            dd($membre);

        }


        dd($membre);
    }

}