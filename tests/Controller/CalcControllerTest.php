<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class CalcControllerTest extends WebTestCase
{
    public function testHomepageResponseCodeOkay()
    {
        // Arrange
        $url = '/calc';
        $httpMethod = 'GET';
        $client = static::createClient();
        $expectedResult = Response::HTTP_OK;

        // Assert
        $client->request($httpMethod, $url);
        $statusCode = $client->getResponse()->getStatusCode();

        // Assert
        $this->assertSame($expectedResult, $statusCode);
    }

    public function testHomepageContentContainsCalculatorHome()
    {
        // Arrange
        $url = '/calc';
        $httpMethod = 'GET';
        $client = static::createClient();
        $searchText = 'Calculator home';

        // Act
        $client->request($httpMethod, $url);
        $content = $client->getResponse()->getContent();

        // to lower case
        $searchTextLowerCase = strtolower($searchText);
        $contentLowerCase = strtolower($content);

        // Assert
        $this->assertContains($searchTextLowerCase, $contentLowerCase);
    }


    public function testFormReferenceNotNull()
    {
        // Arrange
        $url = '/calc';
        $httpMethod = 'GET';
        $client = static::createClient();
        $crawler = $client->request($httpMethod, $url);
        $buttonName = 'calc_submit';

        // Act
        $buttonCrawlerNode = $crawler->selectButton($buttonName);
        $form = $buttonCrawlerNode->form();

        // Assert
        $this->assertNotNull($form);
    }


    public function testCanSubmitAndSeeResultText()
    {
        // Arrange
        $url = '/calc';
        $httpMethod = 'GET';
        $client = static::createClient();
        $crawler = $client->request($httpMethod, $url);
        $expectedContentAfterSubmission = 'Calc RESULT';
        $expectedContentLowerCase = strtolower($expectedContentAfterSubmission);
//        $expectedStatusCode = Response::HTTP_OK;
        $buttonName = 'calc_submit';


        // Act
        $buttonCrawlerNode = $crawler->selectButton($buttonName);
        $form = $buttonCrawlerNode->form();

        // submit the form
        $client->submit($form);
//        $statusCode =  $client->getResponse()->getStatusCode();

        // get content from next Response & make lower case
        $content = $client->getResponse()->getContent();
        $contentLowerCase = strtolower($content);

        // Assert
//        $this->assertSame($expectedStatusCode, $statusCode);

        $this->assertContains($expectedContentLowerCase, $contentLowerCase);
    }

    public function testSubmitOneAndTwoAndValuesConfirmed()
    {
        // Arrange
        $url = '/calc';
        $httpMethod = 'GET';
        $client = static::createClient();
        $crawler = $client->request($httpMethod, $url);
        $buttonName = 'calc_submit';


        // Act
        $buttonCrawlerNode = $crawler->selectButton($buttonName);

//        $this->assertNotNull($buttonCrawlerNode);


        $form = $buttonCrawlerNode->form();

//        $this->assertNotNull($form);

        $form['num1'] = 1;
        $form['num2'] = 2;
        $form['operator'] = 'add';

        // submit the form & get content
        $crawler = $client->submit($form);
        $content = $client->getResponse()->getContent();
        $contentLowerCase = strtolower($content);

        // Assert
        $this->assertContains(
            '1',
            $contentLowerCase
        );
        $this->assertContains(
            '2',
            $contentLowerCase
        );
        $this->assertContains(
            'add',
            $contentLowerCase
        );
    }


    public function testSubmitOneAndTwoAndResultCorrect()
    {
        // Arrange
        $url = '/calc';
        $httpMethod = 'GET';
        $client = static::createClient();

        $num1 = 1;
        $num2 = 2;
        $operator = 'add';
        $expectedResult = 3 . '';
        // must be string for string search
        $expectedResultString = $expectedResult . '';
        $buttonName = 'calc_submit';



        // Act

        // (1) get form page
        $crawler = $client->request($httpMethod, $url);

        // (2) get reference to the form
        $buttonCrawlerNode = $crawler->selectButton($buttonName);

//        $this->assertNotNull($buttonCrawlerNode);
        $form = $buttonCrawlerNode->form();

//        $this->assertNotNull($form);
//        return;

        // (3) insert form data
        $form['num1'] = $num1;
        $form['num2'] = $num2;
        $form['operator'] = $operator;


        // (4) submit the form
        $crawler = $client->submit($form);
        $content = $client->getResponse()->getContent();

        // Assert
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        // Assert
        $this->assertContains($expectedResultString, $content);
    }


//        $form = $buttonCrawlerNode->form(array(
//            'name'              => 'Fabien',
//            'my_form[subject]'  => 'Symfony rocks!',
//        ));


//        $client->submit($form, array(
//            'name'              => 'Fabien',
//            'my_form[subject]'  => 'Symfony rocks!',
//        ));



    public function testSetValuesAndSubmitFormInOneGo()
    {
        // Arrange
        $url = '/calc';
        $httpMethod = 'GET';
        $client = static::createClient();
        $num1 = 1;
        $num2 = 2;
        $operator = 'add';
        $expectedResult = 3 . '';
        // must be string for string search
        $expectedResultString = $expectedResult . '';
        $buttonName = 'calc_submit';

        // Act
        $crawler = $client->request($httpMethod, $url);
        $buttonCrawlerNode = $crawler->selectButton($buttonName);
        $form = $buttonCrawlerNode->form();

        // submit the form with data
        $client->submit($form, [
            'num1'  => $num1,
            'num2'  => $num2,
            'operator'  => $operator,
        ]);

        $content = $client->getResponse()->getContent();

        // Assert
        $this->assertContains($expectedResultString, $content);
    }

    public function testSelectSetValuesSubmitInOneGo()
    {
        // Arrange
        $url = '/calc';
        $httpMethod = 'GET';
        $client = static::createClient();
        $num1 = 1;
        $num2 = 2;
        $operator = 'add';
        $expectedResult = 3 . '';
        // must be string for string search
        $expectedResultString = $expectedResult . '';
        $buttonName = 'calc_submit';

        // Act
        $client->submit($client->request($httpMethod, $url)->selectButton($buttonName)->form([
                'num1'  => $num1,
                'num2'  => $num2,
                'operator'  => $operator,
        ]));
        $content = $client->getResponse()->getContent();

        // Assert
        $this->assertContains($expectedResultString, $content);
    }

}