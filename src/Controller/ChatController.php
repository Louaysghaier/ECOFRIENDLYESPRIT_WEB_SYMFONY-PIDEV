<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{

    private const API_URL = 'https://api.openai.com/v1/engines/text-davinci-003/completions';
    private const API_KEY = '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';

    #[Route('/chat', name: 'app_chat_index')]
    public function index(Request $request): Response
    {
        $question = $request->query->get('question');

        if ($question) {
            if (preg_match('/amena|colis|livraison|transport/i', $question)) {
                try {
                    $answer = $this->getAnswerFromAPI($question);
                    return $this->render('service/chat.html.twig', [
                        'answer' => $answer,
                    ]);
                } catch (\Exception $e) {
                    return new Response('Error making API request: ' . $e->getMessage());
                }
            } else {
                // La question n'est pas pertinente
                return $this->render('service/chat.html.twig', [
                    'answer' => 'Je suis désolé, je ne peux répondre qu\'aux questions liées à Amena, les colis, les livraisons, le transport et la livraison.',
                ]);
            }
        }

        return $this->render('service/chat.html.twig');
    }

    private function getAnswerFromAPI(string $question): string
    {
        $amenaAnswer = 'ECOFRIENDLYESPRIT est un site qui joue le role intermédiaire entre le client qui veut livrer ses colis en toute sécurité, moins argent et de manière plus efficace et avec des livreurs particuliers.';
        $termsAndConditions = 'En utilisant l\'application Ecofriendly Esprit , vous acceptez les présentes conditions d\'utilisation. Veuillez les lire attentivement avant d\'utiliser l\'application.
        
        Utilisation de l\'application
        ECOFRIENDLYESPRIT est une application de logistique qui fournit des services de livraison et de location de véhicules. L\'utilisation de l\'application est réservée aux personnes âgées de 18 ans ou plus.
    
        Inscription et compte utilisateur
        Pour utiliser certains services de l\'application, vous devez créer un compte utilisateur en fournissant des informations personnelles précises et à jour. Vous êtes entièrement responsable de la protection et de la confidentialité de votre compte utilisateur. Vous ne devez pas partager votre compte avec d\'autres personnes et vous êtes entièrement responsable de toutes les activités effectuées sous votre compte.
    
        Conditions de paiement
        L\'utilisation de certains services de l\'application peut entraîner des frais. Vous êtes entièrement responsable du paiement de tous les frais liés à l\'utilisation de l\'application. Les modes de paiement acceptés sont ceux spécifiés dans l\'application.
    
        Propriété intellectuelle
        Tous les droits de propriété intellectuelle associés à l\'application et à son contenu, y compris mais sans s\'y limiter, les marques, les logos, les textes, les images, les graphiques, les sons et les vidéos, sont la propriété d\'Amena ou de ses fournisseurs de contenu. Vous ne devez pas copier, reproduire, distribuer, transmettre, afficher, vendre, concéder sous licence ou exploiter de toute autre manière tout contenu de l\'application sans l\'autorisation écrite préalable d\'Amena.
    
        Limitation de responsabilité
        ECOFRIENDLYESPRIT ne peut garantir la qualité, la fiabilité, l\'exactitude ou l\'exhaustivité de tout contenu de l\'application. L\'utilisation de l\'application est à vos risques et périls. Amena ne peut être tenu responsable de tout dommage résultant de l\'utilisation ou de l\'incapacité à utiliser l\'application, y compris les dommages directs, indirects, accessoires, spéciaux ou consécutifs.

        Modification des conditions d\'utilisation
        ECOFRIENDLYESPRIT se réserve le droit de modifier les présentes conditions d\'utilisation à tout moment sans préavis. Il est de votre responsabilité de vérifier régulièrement les conditions d\'utilisation pour être informé des modifications éventuelles.
        
        En utilisant l\'application ECOFRIENDLYESPRIT, vous acceptez les présentes conditions d\'utilisation. Si vous ne les acceptez pas, veuillez ne pas utiliser l\'application.
        Limitation de responsabilité
        ECOFRIENDLYESPRIT ne sera pas responsable des dommages directs, indirects, spéciaux, consécutifs ou accessoires découlant de l\'utilisation ou de l\'impossibilité d\'utiliser la plateforme ou de son contenu.
        
        Droit applicable
        Ces conditions sont régies par les lois en vigueur en Tunisie. Tout litige relatif à ces conditions sera soumis aux tribunaux compétents de France.
        
        Contact
        Si vous avez des questions concernant ces conditions, vous pouvez contacter ECOFRIENDLYESPRIT à l\'adresse suivante : contact@ECOFRIENDLYESPRIT.com.';

        // Check if the question is related to ECOFRIENDLYESPRIT's terms and conditions
        if (preg_match('/conditions|termes|utilisation|paiement|propriété/i', $question)) {
            return $termsAndConditions;
        } elseif (preg_match('/amena/i', $question)) {
            return $amenaAnswer;
        } else {
            $postData = [
                'prompt' => $question,
                'temperature' => 0.7,
                'max_tokens' => 4000,
                'top_p' => 1,
                'frequency_penalty' => 0.5,
                'presence_penalty' => 0,
            ];

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => self::API_URL,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($postData),
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . self::API_KEY,
                ],
            ]);

            $response = curl_exec($curl);
            curl_close($curl);

            $responseArray = json_decode($response, true);
            $answer = $responseArray['choices'][0]['text'];
            $regex = '/(?<=contact@amena.com).*$/im';

            preg_match($regex, $answer, $matches);
            if (!empty($matches)) {
                return trim($matches[0]);
            }
            return $answer;
        }
    }

}