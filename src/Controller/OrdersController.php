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

#[Route('/orders')]
class OrdersController extends AbstractController
{
    #[Route('/pannier', name: 'app_orders_index', methods: ['GET','POST'])]
    public function index(OrdersRepository $ordersRepository, Request $request,EntityManagerInterface $entityManager): Response
    {           $info = '';

        // Handle the form submission logic if the request is POST

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


        // Calculate the sum of paid orders
        $sumOfPaidOrders = $ordersRepository->calculateSumOfPaidOrders();
        $couponPrice = 0;
        $orderCount = $ordersRepository->countOrdersForUser(1);
    
        // Add any additional fixed value (e.g., 2 in your case)
        $sumOfPaidOrders += 2;
    
        // Render the template
        return $this->render('orders/index.html.twig', [
            'orders' => $ordersRepository->findOrdersByUserAndStatus(1, 'wanted'),
            'sumOfPaidOrders' => $sumOfPaidOrders,
            'couponPrice' => $couponPrice,
            'orderCount'=>  $orderCount,
            'info'=>$info,
        ]);
    }
    #[Route('/update-order-summary', name: 'app_update_order_summary', methods: ['GET'])]
    public function updateOrderSummary(OrdersRepository $ordersRepository, Request $request): Response
    {   
        // Calculate the sum of paid orders
        $sumOfPaidOrders = $ordersRepository->calculateSumOfPaidOrders();
        $orderCount = $ordersRepository->countOrdersForUser(1);
        $previousSumOfPaidOrders = $ordersRepository->calculateSumOfPaidOrders();
        // Get the coupon code from the request
        $couponCode = $request->get('couponCode');
        $couponPrice = 0;
        // Check if the coupon code is "ecofriendlyesprit" and apply a discount
        if ($couponCode === 'ecofriendlyesprit') {
            $sumOfPaidOrders -= 10;
            $couponPrice = 10;
        }
    
        // Add any additional fixed value (e.g., 2 in your case)
        $sumOfPaidOrders += 2;
        $previousSumOfPaidOrders+=2;
        $updated = $sumOfPaidOrders !== $previousSumOfPaidOrders;
        // Render the template
        $orderSummaryHtml = $this->renderView('orders/index_order_summary.html.twig', [
            'orders' => $ordersRepository->findOrdersByUser(1),
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
   
    $orderCount = $ordersRepository->countOrdersForUser(1);
    $sumOfPaidOrders = $ordersRepository->calculateSumOfPaidOrders() + 2;

    

        $sumOfPaidOrders -= 10;
        $couponPrice = 10;
   

    return $this->render('orders/checkout.html.twig', [
        'orders' => $ordersRepository->findOrdersByUser(1),
        'sumOfPaidOrders' => $sumOfPaidOrders,
        'couponPrice' => $couponPrice,
        'orderCount' => $orderCount,
    ]);
}
    #[Route('/checkout', name: 'app_orders_checkout_normal', methods: ['GET', 'POST'])]
    public function checkoutNormal(OrdersRepository $ordersRepository): Response
    { 
    $orderCount = $ordersRepository->countOrdersForUser(1);
    $sumOfPaidOrders = $ordersRepository->calculateSumOfPaidOrders() + 2;

   
        $couponPrice = 0;
    

    return $this->render('orders/checkout.html.twig', [
        'orders' => $ordersRepository->findOrdersByUser(1),
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
    private $currentUserService;

    public function __construct(CurrentUserService $currentUserService)
    {
        $this->currentUserService = $currentUserService;
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

        // Fetch the Service entity from the database using $serviceId
        $service = $this->getDoctrine()->getRepository(Service::class)->find($serviceId);

        if (!$service) {
            return new JsonResponse(['success' => false, 'message' => 'Service not found.']);
        }
        // Get the current user from the CurrentUserService
       // $user = $this->currentUserService->getCurrentUser();
       $entityManager = $this->getDoctrine()->getManager();

        $user = $entityManager->getRepository(User2::class)->findOneBy([]);

        if (!$user instanceof User2) {
            return new JsonResponse(['success' => false, 'message' => 'User not found.']);
        }
        $order = new Orders();
        $order->setUserid($user);
        $order->addServiceid($service); // Associate the service with the order
        $order->setServices($service);

        $order->setNumOrder($this->generateRandomNumOrder());
        $order->setPhonenumber("55125290");
        $order->setPriceorder($service->getPrice());
        $order->setServices($service);
        $order->setStatus("wanted");
        // Persist and flush the entities
        $entityManager->persist($order);
        
        try {
            $entityManager->flush();
            
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
        
            return new JsonResponse(['success' => true, 'message' => 'Service added to order.', 'order' => $orderData]);
        } catch (\Exception $e) {
            // Handle exceptions, log them, or return an error response
            return new JsonResponse(['success' => false, 'message' => 'Failed to create order.']);
        }
    }


    #[Route("/update_orders_status", name: 'update_orders_status_route', methods: ['POST','GET'])]
   // #[ParamConverter("ordersToUpdate", class: "App\Entity\Orders")]
    public function updateOrdersStatus(Request $request, EntityManagerInterface $entityManager): Response
    {

        # Fetch orders with the current status
        $ordersToUpdate = $entityManager->getRepository(Orders::class)->findBy(['status' => 'wanted', 'userid' => 1]);
        //update the orders status fields
        foreach ($ordersToUpdate as $order) {
            # Update the status of each order
            $order->setStatus('payed');
            $entityManager->persist($order);
        }

        # Persist the changes to the database
        $entityManager->flush();
            //add a mail logic to send mail to that user
      //  $User = $entityManager->getRepository(User2::class)->findBy([ 'iduser' => 1]);
      //  $Usermaile
        return $this->redirectToRoute('app_orders_index');
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
