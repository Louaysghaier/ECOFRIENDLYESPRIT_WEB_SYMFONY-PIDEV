<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\Service;
use App\Entity\User2;
use App\Form\Orders1Type;
use App\Repository\OrdersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CurrentUserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Service\EmailService;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Twilio\Rest\Client;
use Symfony\Component\HttpFoundation\RequestStack;

#[Route('/orders')]
class OrdersController extends AbstractController
{       //private $currentUserService;

    private $requestStack;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder,RequestStack $requestStack)
    {
     //   $this->passwordEncoder = $passwordEncoder;
        $this->requestStack = $requestStack;
    }
    
    #[Route('/pannier', name: 'app_orders_index', methods: ['GET','POST'])]
    public function index(OrdersRepository $ordersRepository, Request $request,EntityManagerInterface $entityManager): Response
    {$session = $this->requestStack->getSession();
        $user = $session->get('User2') ;

        // Handle the form submission logic if the request is POST

            $numOrder = $request->request->get('numOrder');
            $secretCode = $request->request->get('secretCode');

            // Add your logic to process the order tracking data
        $order = $entityManager->getRepository(Orders::class)->findOneBy(['numOrder' => $numOrder]);
            if ($order) {
                if ($secretCode == 'TN1122') {
                    if ($order->getStatus() == 'payed' ) {
                        $info1 = 'Service is paid & call us +216 55125290 ';
                    } else {
                        $info1 = 'You have not paid yet ';
                    }
                } else {
                    $info1 = 'Error: Check your secret code from your phone';
                }
            }
            else {
                $info1 = 'You Need To Check Your order characteristics  !';

            }



        // Calculate the sum of paid orders
        $sumOfPaidOrders = $ordersRepository->calculateSumOfPaidOrders($user->getIduser());
        $couponPrice = 0;
        $orderCount = $ordersRepository->countOrdersForUser($user->getIduser());
    
        // Add any additional fixed value (e.g., 2 in your case)
        $sumOfPaidOrders += 2;

        // Render the template
        return $this->render('orders/index.html.twig', [
            'orders' => $ordersRepository->findOrdersByUserAndStatus($user->getIduser(), 'wanted'),
            'orders1' => $ordersRepository->findOrdersByUser($user->getIduser()),
            'sumOfPaidOrders' => $sumOfPaidOrders,
            'couponPrice' => $couponPrice,
            'orderCount'=>  $orderCount,
            'a'=>$a ?? null,
            'info1'=>$info1,
        ]);
    }
    #[Route('/update-order-summary', name: 'app_update_order_summary', methods: ['GET'])]
    public function updateOrderSummary(OrdersRepository $ordersRepository, Request $request): Response
    {   $session = $this->requestStack->getSession();
        $user = $session->get('User2') ;
        // Calculate the sum of paid orders
        $sumOfPaidOrders = $ordersRepository->calculateSumOfPaidOrders($user->getIduser());
        $orderCount = $ordersRepository->countOrdersForUser($user->getIduser());
        $previousSumOfPaidOrders = $ordersRepository->calculateSumOfPaidOrders($user->getIduser());
        // Get the coupon code from the request
        $couponCode = $request->get('couponCode');
        $couponPrice = 0;
        // Check if the coupon code is "ecofriendlyesprit" and apply a discount
        if ($couponCode === 'ecoTN11') {
            $sumOfPaidOrders -= 10;
            $couponPrice = 10;
        }
    
        // Add any additional fixed value (e.g., 2 in your case)
        $sumOfPaidOrders += 2;
        $previousSumOfPaidOrders+=2;
        $updated = $sumOfPaidOrders !== $previousSumOfPaidOrders;
        $session = $this->requestStack->getSession();
        $user = $session->get('User2') ;
        // Render the template
        $orderSummaryHtml = $this->renderView('orders/index_order_summary.html.twig', [
            'orders' => $ordersRepository->findOrdersByUser($user->getIduser()),
            'previousSumOfPaidOrders' =>$previousSumOfPaidOrders,
            'sumOfPaidOrders' => $sumOfPaidOrders,
            'couponPrice' => $couponPrice,
            'orderCount'=>  $orderCount,
        ]);
        
        // Return a JSON response with the updated HTML
        return new JsonResponse(['html' => $orderSummaryHtml, 'updated' => $updated]);
    }
   
  
    /*#[Route('/checkout', name: 'app_orders_checkout', methods: ['GET'])]
public function checkout(OrdersRepository $ordersRepository, Request $request): Response
{   
    $orderCount = $ordersRepository->countOrdersForUser(1);
    $sumOfPaidOrders = $ordersRepository->calculateSumOfPaidOrders() + 2;

    $updateResponse = $this->updateOrderSummary($ordersRepository, $request);
    $responseData = json_decode($updateResponse->getContent(), true);

    if (isset($responseData['updated']) && $responseData['updated']) {
        $sumOfPaidOrders -= 10;
        $couponPrice = 10;
    } else {
        $couponPrice = 0;
    }

    return $this->render('orders/checkout.html.twig', [
        'orders' => $ordersRepository->findOrdersByUser(1),
        'sumOfPaidOrders' => $sumOfPaidOrders,
        'couponPrice' => $couponPrice,
        'orderCount' => $orderCount,
        'updated' => $responseData['updated'],
    ]);
}*/
#[Route('/checkout/{updated}', name: 'app_orders_checkout_updated', methods: ['GET', 'POST'], requirements: ['updated' => 'true|false'])]
    public function checkoutUpdated($updated, OrdersRepository $ordersRepository): Response
    {
        $session = $this->requestStack->getSession();
        $user = $session->get('User2') ;
        $orderCount = $ordersRepository->countOrdersForUser($user->getIduser());
        $sumOfPaidOrders = $ordersRepository->calculateSumOfPaidOrders($user->getIduser()) + 2;

    

        $sumOfPaidOrders -= 10;
        $couponPrice = 10;
   

    return $this->render('orders/checkout.html.twig', [
        'orders' => $ordersRepository->findOrdersByUser($user->getIduser()),
        'sumOfPaidOrders' => $sumOfPaidOrders,
        'couponPrice' => $couponPrice,
        'orderCount' => $orderCount,
    ]);
}
    #[Route('/checkout', name: 'app_orders_checkout_normal', methods: ['GET', 'POST'])]
    public function checkoutNormal(OrdersRepository $ordersRepository): Response
    {  $session = $this->requestStack->getSession();
        $user = $session->get('User2') ;
        $orderCount = $ordersRepository->countOrdersForUser($user->getIduser());
        $sumOfPaidOrders = $ordersRepository->calculateSumOfPaidOrders($user->getIduser()) + 2;

   
        $couponPrice = 0;
    

    return $this->render('orders/checkout.html.twig', [
        'orders' => $ordersRepository->findOrdersByUser($user->getIduser()),
        'sumOfPaidOrders' => $sumOfPaidOrders,
        'couponPrice' => $couponPrice,
        'orderCount' => $orderCount,
    ]);
}
    #[Route('/payment/success', name: 'payment_success')]
    public function paymentSuccess(Request $request): Response
    {
        // Retrieve payment details from the request
        $paymentId = $request->query->get('paymentId');
        $payerId = $request->query->get('PayerID');

        // Perform actions to validate payment and update order status
        // For simplicity, assume you have an Order entity with a status field

        // Fetch the order based on your application logic
        $order = $this->getDoctrine()->getRepository(Order::class)->findOneBy(['paymentId' => $paymentId]);

        if (!$order) {
            // Handle the case where the order is not found
            // You might want to log an error, redirect the user, or display an error message
            return $this->redirectToRoute('payment_failure');
        }

        // Update the order status to 'paid'
        $order->setStatus('paid');

        // Persist the changes to the database
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($order);
        $entityManager->flush();

        // Perform any additional actions based on your application's needs

        return $this->render('payment/success.html.twig', [
            'order' => $order,
            // Add any other variables you want to pass to the success template
        ]);
    }
    #[Route('/{orderid}/edit', name: 'app_orders_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Orders $order, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Orders1Type::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_orders_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('orders/edit.html.twig', [
            'order' => $order,
            'form' => $form,
        ]);

    }

    #[Route('/{orderid}', name: 'app_orders_delete', methods: ['POST'])]
    public function delete(Request $request, Orders $order, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$order->getOrderid(), $request->request->get('_token'))) {
            $entityManager->remove($order);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_orders_index', [], Response::HTTP_SEE_OTHER);
    }


/**
     * Generate a random number with 4 digits and 2 characters.
     *
     * @return string
     */
    private function generateRandomNumOrder(): string
    {
        $numbers = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $characters = chr(rand(65, 90)) . chr(rand(65, 90));

        return $numbers . $characters;
    }
     #[Route("/{add-to-order}", name:'add_to_order', methods: ['POST'])]

    public function addToOrder(Request $request): Response
    {
        $serviceId = $request->request->get('serviceId');
        $session = $this->requestStack->getSession();
        $user = $session->get('User2') ;
        // Fetch the Service entity from the database using $serviceId
        $service = $this->getDoctrine()->getRepository(Service::class)->find($serviceId);

        if (!$service) {
            return new JsonResponse(['success' => false, 'message' => 'Service not found.']);
        }

       $entityManager = $this->getDoctrine()->getManager();



        $order = new Orders();
        if (!$entityManager->contains($user)) {
            // If $user is not managed, you might want to fetch the managed entity from the EntityManager
            $user = $entityManager->merge($user);
        }

        $order->setUserid($user);
        $order->addServiceid($service); // Associate the service with the orderuser
        $order->setServices($service);

        $order->setNumOrder($this->generateRandomNumOrder());
        $order->setPhonenumber("55125290");
        $order->setPriceorder($service->getPrice());
        //$order->setServices($service);
        $order->setStatus("wanted");
        // Persist and flush the entities
        $entityManager->persist($order);
        $entityManager->flush();
        try {

            // If we reach this point, the order was successfully created
            $orderData = [
                'orderId' => $order->getOrderid(),
                'numOrder' => $order->getNumOrder(),
                'pickupdatetime' => $order->getPickupdatetime()->format('Y-m-d H:i:s'),
                'status' => $order->getStatus(),
                'phonenumber' => $order->getPhonenumber(),
                'priceorder' => $order->getPriceorder(),
                'user' => [
                    'userId' => $order->getUserid()->getIduser(),
                    'username' => $order->getUserid()->getNomuser(),
                    // Add other user details as needed
                ],
                'services' => [
                    [
                        'serviceId' => $service->getServiceid(),
                        'serviceName' => $service->getServicename(),
                        // Add other service details as needed
                    ]
                    // You can add more services if the order involves multiple services
                ],
            ];
            //$entityManager->flush();
            $this->addFlash('success', 'Your Service is added to order Check Your basket!');
            return new JsonResponse(['success' => true, 'message' => 'Service added to order.', 'order' => $orderData]);

        }
        catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => 'Failed to create order.']);
        }
    }
    //private $ordersRepository;
    public function sendSms(): Response
    {
        // Vos identifiants Twilio
        $accountSid = 'AC3d76eb9005b49c7a2a73ce650a34c6f5';
        $authToken = '159461fe9e711b3c74b8f86e51098960';
        $twilioNumber = '+17196940243';

        // Le numéro de téléphone de destination
        $toPhoneNumber = '+21655125290';
       // $orders = $this->ordersRepository->findOrdersByUser(1);
        // Le corps du message SMS
        $messageBody = 'YOUR SECRET CODE IS TN1122 FOR CHECKING YOUR ORDER !';

        // Créer le client Twilio
        $twilio = new Client($accountSid, $authToken);

        // Envoyer le SMS
        $message = $twilio->messages
            ->create($toPhoneNumber, [
                'from' => $twilioNumber,
                'body' => $messageBody,
            ]);

        // Gérer la réponse ou faire d'autres actions nécessaires

        return new Response('SMS envoyé avec succès!');
    }

    #[Route("/update_orders_status", name: 'update_orders_status_route', methods: ['POST','GET'])]
   // #[ParamConverter("ordersToUpdate", class: "App\Entity\Orders")]
    public function updateOrdersStatus(Request $request, EntityManagerInterface $entityManager,EmailService $emailService): Response
    {
        $session = $this->requestStack->getSession();
        $user = $session->get('User2') ;
        # Fetch orders with the current status
        $ordersToUpdate = $entityManager->getRepository(Orders::class)->findBy(['status' => 'wanted', 'userid' => $user->getIduser()]);
        //update the orders status fields
        foreach ($ordersToUpdate as $order) {
            # Update the status of each order
            $order->setStatus('payed');
            $entityManager->persist($order);
        }

        $entityManager->flush();
        //$this->sendSms();
        $this->addFlash('success', ' Thank You For Trusting Us!! a secret code has been sent to your phone !');

        //add a mail logic to send mail to that user
      //  $User = $entityManager->getRepository(User2::class)->findBy([ 'iduser' => $user->getIduser()]);
      //  $Usermaile=this->$Use->getMailuser();
       // $emailService->sendSimpleEmail('louay.sghaier@esprit.tn', 'Test Subject', 'Hello, this is the email body.');

        return $this->redirectToRoute('app_service_shop');
    }

   /* #[Route("/handle_order_tracking", name: 'handle_order_tracking', methods: ['POST'])]
    public function handleOrderTracking(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Handle the form submission logic here
        $numOrder = $request->request->get('numOrder');
        $secretCode = $request->request->get('secretCode');

        // Add your logic to process the order tracking data
        $order = $entityManager->getRepository(Orders::class)->findOneBy(['numOrder' => $numOrder]);

        if ($order) {
            if ($secretCode == 'TN1122') {
                if ($order->getStatus() == 'payed') {
                    $info = 'Service is paid & call us +216 55125290 ';
                } else {
                    $info = 'You have not paid yet ';
                }
            } else {
                $info = 'Error: Check your secret code from your email';
            }
        } else {
            $info = 'Order not found';
        }

        // You can render response or redirect to another route after processing
        return $this->render('orders/index.html.twig', [
            'numOrder' => $numOrder,
            'secretCode' => $secretCode,
            'info' => $info,
            // Add other variables if needed
        ]);
    }
*/

}
