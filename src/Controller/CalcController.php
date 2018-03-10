<?php

namespace App\Controller;

use App\Util\Calculator;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CalcController extends Controller
{
    /**
     * @Route("/calc", name="calc_home")
     */
    public function indexAction()
    {
        return $this->render('calc/index.html.twig', []);
    }

    /**
     * @Route("/calc/process", name="calc_process")
     */
    public function processAction(Request $request)
    {
        // extract name values from POST data
        $n1 = $request->request->get('num1');
        $n2 = $request->request->get('num2');
        $operator = $request->request->get('operator');

        $calc = new Calculator();
        $answer = $calc->process($n1, $n2, $operator);

//        $answer = '(not yet implemented)';

        return $this->render(
            'calc/result.html.twig',
            [
                'n1' => $n1,
                'n2' => $n2,
                'operator' => $operator,
                'answer' => $answer
            ]
        );
    }
}
