<?php
/**
 * Created by PhpStorm.
 * User: davi
 * Date: 26.11.16
 * Time: 15:29
 */

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\StockOption;

class LoadStockData implements FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $msft = new StockOption();
        $msft->setCompany('Microsoft Corporation');
        $msft->setTickerSymbol('MSFT');
        $msft->setQuantity(100);
        $msft->setValue('30.00');

        $manager->persist($msft);

        $aapl = new StockOption();
        $aapl->setCompany('Apple Inc.');
        $aapl->setTickerSymbol('AAPL');
        $aapl->setQuantity(100);
        $aapl->setValue('30.00');

        $manager->persist($aapl);

        $ibm = new StockOption();
        $ibm->setCompany('International Business Machines Corporation');
        $ibm->setTickerSymbol('IBM');
        $ibm->setQuantity(100);
        $ibm->setValue('30.00');

        $manager->persist($ibm);

        $amzn = new StockOption();
        $amzn->setCompany('Amazon.com, Inc.');
        $amzn->setTickerSymbol('AMZN');
        $amzn->setQuantity(100);
        $amzn->setValue('30.00');

        $manager->persist($amzn);

        $intc = new StockOption();
        $intc->setCompany('Intel Corporation');
        $intc->setTickerSymbol('INTC');
        $intc->setQuantity(100);
        $intc->setValue('30.00');

        $manager->persist($intc);

        $manager->flush();
    }
}