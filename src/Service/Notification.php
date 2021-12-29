<?php

namespace App\Service;

use App\Entity\Etape;
use App\Entity\Evenement;
use App\Entity\Membre;
use App\Entity\Trajet;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class Notification
{

    protected MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendAcceptToSubscriber(Membre $membre, Etape $etape)
    {

        $email = (new TemplatedEmail())
            ->from('thomas.huaume@hotmail.fr')
            ->to($membre->getEmail())
            ->subject($etape->getEvenement()->getNom()." : confirmation de votre demande d'inscription")
            ->htmlTemplate('emails/acceptationEtape.html.twig')
            ->context([
                'etape' => $etape,
                'membre' => $membre
            ])
        ;

        $this->mailer->send($email);
    }

    public function sendRefuseToSubscriber(Membre $membre, Etape $etape)
    {

        $email = (new TemplatedEmail())
            ->from('thomas.huaume@hotmail.fr')
            ->to($membre->getEmail())
            ->subject($etape->getEvenement()->getNom()." : refus de votre demande d'inscription")
            ->htmlTemplate('emails/refusEtape.html.twig')
            ->context([
                'etape' => $etape,
                'membre' => $membre

            ])
        ;

        $this->mailer->send($email);
    }

    public function sendAcceptToCarpooler(Membre $membre, Trajet $trajet)
    {

        $email = (new TemplatedEmail())
            ->from('thomas.huaume@hotmail.fr')
            ->to($membre->getEmail())
            ->subject($trajet->getTitre()." : confirmation de votre demande de covoiturage")
            ->htmlTemplate('emails/acceptationTrajet.html.twig')
            ->context([
                'trajet' => $trajet,
                'membre' => $membre
            ])
        ;

        $this->mailer->send($email);
    }

    public function sendRefuseToCarpooler(Membre $membre, Trajet $trajet)
    {

        $email = (new TemplatedEmail())
            ->from('thomas.huaume@hotmail.fr')
            ->to($membre->getEmail())
            ->subject($trajet->getTitre()." : refus de votre demande de covoiturage")
            ->htmlTemplate('emails/refusTrajet.html.twig')
            ->context([
                'trajet' => $trajet,
                'membre' => $membre
            ])
        ;

        $this->mailer->send($email);
    }

}