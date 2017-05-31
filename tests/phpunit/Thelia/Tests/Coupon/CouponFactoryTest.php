<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace Thelia\Coupon;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Thelia\Condition\ConditionCollection;
use Thelia\Condition\ConditionEvaluator;
use Thelia\Condition\ConditionFactory;
use Thelia\Condition\Implementation\MatchForTotalAmount;
use Thelia\Condition\Operators;
use Thelia\Core\Translation\Translator;
use Thelia\Coupon\Type\RemoveXAmount;
use Thelia\Model\Coupon;
use Thelia\Model\CurrencyQuery;
use Thelia\Model\Customer;

/**
 * Unit Test CouponFactory Class
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-11-17 at 18:59:24.
 *
 * @package Coupon
 * @author  Guillaume MOREL <gmorel@openstudio.fr>
 *
 */
class CouponFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
    }

    /**
     * Generate adapter stub
     *
     * @param int    $cartTotalPrice   Cart total price
     * @param string $checkoutCurrency Checkout currency
     * @param string $i18nOutput       Output from each translation
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function generateFacadeStub($cartTotalPrice = 400, $checkoutCurrency = 'EUR', $i18nOutput = '')
    {
        $stubFacade = $this->getMockBuilder('\Thelia\Coupon\BaseFacade')
            ->disableOriginalConstructor()
            ->getMock();

        $currencies = CurrencyQuery::create();
        $currencies = $currencies->find();
        $stubFacade->expects($this->any())
            ->method('getAvailableCurrencies')
            ->will($this->returnValue($currencies));

        $stubFacade->expects($this->any())
            ->method('getCartTotalPrice')
            ->will($this->returnValue($cartTotalPrice));

        $stubFacade->expects($this->any())
            ->method('getCheckoutCurrency')
            ->will($this->returnValue($checkoutCurrency));

        $stubFacade->expects($this->any())
            ->method('getConditionEvaluator')
            ->will($this->returnValue(new ConditionEvaluator()));

        $customer = new Customer();
        $customer->setId(1);

        $stubFacade->expects($this->any())
            ->method('getCustomer')
            ->will($this->returnValue($customer));

        $stubTranslator = $this->getMockBuilder('\Thelia\Core\Translation\Translator')
            ->disableOriginalConstructor()
            ->getMock();
        $stubTranslator->expects($this->any())
            ->method('trans')
            ->will($this->returnValue($i18nOutput));

        $stubFacade->expects($this->any())
            ->method('getTranslator')
            ->will($this->returnValue($stubTranslator));

        return $stubFacade;
    }

    /**
     * Generate a valid Coupon model
     *
     * @param $facade
     * @param ConditionFactory $conditionFactory
     * @return Coupon
     */
    public function generateCouponModel($facade, ConditionFactory $conditionFactory)
    {
        // Coupons
        $coupon1 = new Coupon();
        $coupon1->setCode('XMAS');
        $coupon1->setType('thelia.coupon.type.remove_x_amount');
        $coupon1->setTitle('Christmas coupon');
        $coupon1->setShortDescription('Coupon for Christmas removing 10€ if your total checkout is more than 40€');
        $coupon1->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras at luctus tellus. Integer turpis mauris, aliquet vitae risus tristique, pellentesque vestibulum urna. Vestibulum sodales laoreet lectus dictum suscipit. Praesent vulputate, sem id varius condimentum, quam magna tempor elit, quis venenatis ligula nulla eget libero. Cras egestas euismod tellus, id pharetra leo suscipit quis. Donec lacinia ac lacus et ultricies. Nunc in porttitor neque. Proin at quam congue, consectetur orci sed, congue nulla. Nulla eleifend nunc ligula, nec pharetra elit tempus quis. Vivamus vel mauris sed est dictum blandit. Maecenas blandit dapibus velit ut sollicitudin. In in euismod mauris, consequat viverra magna. Cras velit velit, sollicitudin commodo tortor gravida, tempus varius nulla.

Donec rhoncus leo mauris, id porttitor ante luctus tempus. Curabitur quis augue feugiat, ullamcorper mauris ac, interdum mi. Quisque aliquam lorem vitae felis lobortis, id interdum turpis mattis. Vestibulum diam massa, ornare congue blandit quis, facilisis at nisl. In tortor metus, venenatis non arcu nec, sollicitudin ornare nisl. Nunc erat risus, varius nec urna at, iaculis lacinia elit. Aenean ut felis tempus, tincidunt odio non, sagittis nisl. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec vitae hendrerit elit. Nunc sit amet gravida risus, euismod lobortis massa. Nam a erat mauris. Nam a malesuada lorem. Nulla id accumsan dolor, sed rhoncus tellus. Quisque dictum felis sed leo auctor, at volutpat lectus viverra. Morbi rutrum, est ac aliquam imperdiet, nibh sem sagittis justo, ac mattis magna lacus eu nulla.

Duis interdum lectus nulla, nec pellentesque sapien condimentum at. Suspendisse potenti. Sed eu purus tellus. Nunc quis rhoncus metus. Fusce vitae tellus enim. Interdum et malesuada fames ac ante ipsum primis in faucibus. Etiam tempor porttitor erat vitae iaculis. Sed est elit, consequat non ornare vitae, vehicula eget lectus. Etiam consequat sapien mauris, eget consectetur magna imperdiet eget. Nunc sollicitudin luctus velit, in commodo nulla adipiscing fermentum. Fusce nisi sapien, posuere vitae metus sit amet, facilisis sollicitudin dui. Fusce ultricies auctor enim sit amet iaculis. Morbi at vestibulum enim, eget adipiscing eros.

Praesent ligula lorem, faucibus ut metus quis, fermentum iaculis erat. Pellentesque elit erat, lacinia sed semper ac, sagittis vel elit. Nam eu convallis est. Curabitur rhoncus odio vitae consectetur pellentesque. Nam vitae arcu nec ante scelerisque dignissim vel nec neque. Suspendisse augue nulla, mollis eget dui et, tempor facilisis erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ac diam ipsum. Donec convallis dui ultricies velit auctor, non lobortis nulla ultrices. Morbi vitae dignissim ante, sit amet lobortis tortor. Nunc dapibus condimentum augue, in molestie neque congue non.

Sed facilisis pellentesque nisl, eu tincidunt erat scelerisque a. Nullam malesuada tortor vel erat volutpat tincidunt. In vehicula diam est, a convallis eros scelerisque ut. Donec aliquet venenatis iaculis. Ut a arcu gravida, placerat dui eu, iaculis nisl. Quisque adipiscing orci sit amet dui dignissim lacinia. Sed vulputate lorem non dolor adipiscing ornare. Morbi ornare id nisl id aliquam. Ut fringilla elit ante, nec lacinia enim fermentum sit amet. Aenean rutrum lorem eu convallis pharetra. Cras malesuada varius metus, vitae gravida velit. Nam a varius ipsum, ac commodo dolor. Phasellus nec elementum elit. Etiam vel adipiscing leo.');
        $coupon1->setAmount(10.00);
        $coupon1->setIsUsed(true);
        $coupon1->setIsEnabled(true);
        $date = new \DateTime();
        $coupon1->setExpirationDate($date->setTimestamp(strtotime("today + 3 months")));

        $condition1 = new MatchForTotalAmount($facade);
        $operators = array(
            MatchForTotalAmount::CART_TOTAL => Operators::SUPERIOR,
            MatchForTotalAmount::CART_CURRENCY => Operators::EQUAL
        );
        $values = array(
            MatchForTotalAmount::CART_TOTAL => 40.00,
            MatchForTotalAmount::CART_CURRENCY => 'EUR'
        );
        $condition1->setValidatorsFromForm($operators, $values);

        $condition2 = new MatchForTotalAmount($facade);
        $operators = array(
            MatchForTotalAmount::CART_TOTAL => Operators::INFERIOR,
            MatchForTotalAmount::CART_CURRENCY => Operators::EQUAL
        );
        $values = array(
            MatchForTotalAmount::CART_TOTAL => 400.00,
            MatchForTotalAmount::CART_CURRENCY => 'EUR'
        );
        $condition2->setValidatorsFromForm($operators, $values);

        $conditions = new ConditionCollection();
        $conditions[] = $condition1;
        $conditions[] = $condition2;

        $serializedConditions = $conditionFactory->serializeConditionCollection($conditions);
        $coupon1->setSerializedConditions($serializedConditions);

        $coupon1->setMaxUsage(40);
        $coupon1->setIsCumulative(true);
        $coupon1->setIsRemovingPostage(false);
        $coupon1->setIsAvailableOnSpecialOffers(true);

        return $coupon1;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Thelia\Coupon\CouponFactory::buildCouponFromCode
     */
    public function testBuildCouponFromCode()
    {
        /** @var FacadeInterface|\PHPUnit_Framework_MockObject_MockObject $stubFacade */
        $stubFacade = $this->generateFacadeStub();

        $stubContainer = $this->getMockContainer();

        $conditionFactory = new ConditionFactory($stubContainer);
        $couponModel = $this->generateCouponModel($stubFacade, $conditionFactory);
        $stubFacade->expects($this->any())
            ->method('findOneCouponByCode')
            ->will($this->returnValue($couponModel));

        $couponManager = new RemoveXAmount($stubFacade);

        $condition1 = new MatchForTotalAmount($stubFacade);
        $operators = array(
            MatchForTotalAmount::CART_TOTAL => Operators::SUPERIOR,
            MatchForTotalAmount::CART_CURRENCY => Operators::EQUAL
        );
        $values = array(
            MatchForTotalAmount::CART_TOTAL => 40.00,
            MatchForTotalAmount::CART_CURRENCY => 'EUR'
        );
        $condition1->setValidatorsFromForm($operators, $values);

        $condition2 = new MatchForTotalAmount($stubFacade);
        $operators = array(
            MatchForTotalAmount::CART_TOTAL => Operators::INFERIOR,
            MatchForTotalAmount::CART_CURRENCY => Operators::EQUAL
        );
        $values = array(
            MatchForTotalAmount::CART_TOTAL => 400.00,
            MatchForTotalAmount::CART_CURRENCY => 'EUR'
        );
        $condition2->setValidatorsFromForm($operators, $values);

        $conditions = new ConditionCollection();
        $conditions[] = $condition1;
        $conditions[] = $condition2;
        $stubConditionFactory = $this->getMockBuilder('\Thelia\Condition\ConditionFactory')
            ->disableOriginalConstructor()
            ->getMock();
        $stubConditionFactory->expects($this->any())
            ->method('unserializeConditionCollection')
            ->will($this->returnValue($conditions));

        $stubContainer->expects($this->any())
            ->method('get')
            ->will($this->onConsecutiveCalls($stubFacade, $couponManager, $stubConditionFactory));

        $stubContainer->expects($this->any())
            ->method('has')
            ->will($this->returnValue(true));

        $factory = new CouponFactory($stubContainer);
        $expected = $couponManager;
        $actual = $factory->buildCouponFromCode('XMAS');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Thelia\Coupon\CouponFactory::buildCouponFromCode
     * @expectedException \Thelia\Exception\CouponNoUsageLeftException
     */
    public function testBuildCouponFromCodeUsageLimitCoupon()
    {
        /** @var FacadeInterface|\PHPUnit_Framework_MockObject_MockObject $stubFacade */
        $stubFacade = $this->generateFacadeStub();

        $stubContainer = $this->getMockContainer();

        $conditionFactory = new ConditionFactory($stubContainer);
        $couponModel = $this->generateCouponModel($stubFacade, $conditionFactory);
        $date = new \DateTime();
        $couponModel->setExpirationDate($date->setTimestamp(strtotime("today + 3 months")));
        $couponModel->setMaxUsage(0);
        $couponModel->setPerCustomerUsageCount(false);

        $stubFacade->expects($this->any())
            ->method('findOneCouponByCode')
            ->will($this->returnValue($couponModel));

        $couponManager = new RemoveXAmount($stubFacade);

        $condition1 = new MatchForTotalAmount($stubFacade);
        $operators = array(
            MatchForTotalAmount::CART_TOTAL => Operators::SUPERIOR,
            MatchForTotalAmount::CART_CURRENCY => Operators::EQUAL
        );
        $values = array(
            MatchForTotalAmount::CART_TOTAL => 40.00,
            MatchForTotalAmount::CART_CURRENCY => 'EUR'
        );
        $condition1->setValidatorsFromForm($operators, $values);

        $condition2 = new MatchForTotalAmount($stubFacade);
        $operators = array(
            MatchForTotalAmount::CART_TOTAL => Operators::INFERIOR,
            MatchForTotalAmount::CART_CURRENCY => Operators::EQUAL
        );
        $values = array(
            MatchForTotalAmount::CART_TOTAL => 400.00,
            MatchForTotalAmount::CART_CURRENCY => 'EUR'
        );
        $condition2->setValidatorsFromForm($operators, $values);

        $conditions = new ConditionCollection();
        $conditions[] = $condition1;
        $conditions[] = $condition2;
        $stubConditionFactory = $this->getMockBuilder('\Thelia\Condition\ConditionFactory')
            ->disableOriginalConstructor()
            ->getMock();
        $stubConditionFactory->expects($this->any())
            ->method('unserializeConditionCollection')
            ->will($this->returnValue($conditions));

        $stubContainer->expects($this->any())
            ->method('get')
            ->will($this->onConsecutiveCalls($stubFacade, $couponManager, $stubConditionFactory));

        $stubContainer->expects($this->any())
            ->method('has')
            ->with('request_stack')
            ->will($this->returnValue(false));

        $dummy = new Translator($stubContainer);

        $stubContainer->expects($this->any())
            ->method('has')
            ->will($this->returnValue(true));

        $factory = new CouponFactory($stubContainer);
        $factory->buildCouponFromCode('XMAS');
    }

    /**
     * @covers Thelia\Coupon\CouponFactory::buildCouponFromCode
     */
    public function testBuildCouponFromCodeUnknownCode()
    {
        /** @var FacadeInterface|\PHPUnit_Framework_MockObject_MockObject $stubFacade */
        $stubFacade = $this->generateFacadeStub();

        $stubContainer = $this->getMockContainer();

        $stubFacade->expects($this->any())
            ->method('findOneCouponByCode')
            ->will($this->returnValue(null));

        $couponManager = new RemoveXAmount($stubFacade);

        $stubContainer->expects($this->any())
            ->method('get')
            ->will($this->onConsecutiveCalls($stubFacade, $couponManager));

        $stubContainer->expects($this->any())
            ->method('has')
            ->will($this->returnValue(true));

        $factory = new CouponFactory($stubContainer);
        $actual = $factory->buildCouponFromCode('XMAS');
        $expected = false;

        $this->assertEquals($expected, $actual, 'CouponFactory->buildCouponFromCode should return false if the given code is unknown');
    }

    /**
     * @covers Thelia\Coupon\CouponFactory::buildCouponFromCode
     * @expectedException \Thelia\Exception\CouponExpiredException
     */
    public function testBuildCouponFromCodeExpiredCoupon()
    {
        /** @var FacadeInterface|\PHPUnit_Framework_MockObject_MockObject $stubFacade */
        $stubFacade = $this->generateFacadeStub();

        $stubContainer = $this->getMockContainer();

        $conditionFactory = new ConditionFactory($stubContainer);
        $couponModel = $this->generateCouponModel($stubFacade, $conditionFactory);
        $date = new \DateTime();
        $couponModel->setExpirationDate($date->setTimestamp(strtotime("today - 3 months")));
        $stubFacade->expects($this->any())
            ->method('findOneCouponByCode')
            ->will($this->returnValue($couponModel));

        $couponManager = new RemoveXAmount($stubFacade);

        $condition1 = new MatchForTotalAmount($stubFacade);
        $operators = array(
            MatchForTotalAmount::CART_TOTAL => Operators::SUPERIOR,
            MatchForTotalAmount::CART_CURRENCY => Operators::EQUAL
        );
        $values = array(
            MatchForTotalAmount::CART_TOTAL => 40.00,
            MatchForTotalAmount::CART_CURRENCY => 'EUR'
        );
        $condition1->setValidatorsFromForm($operators, $values);

        $condition2 = new MatchForTotalAmount($stubFacade);
        $operators = array(
            MatchForTotalAmount::CART_TOTAL => Operators::INFERIOR,
            MatchForTotalAmount::CART_CURRENCY => Operators::EQUAL
        );
        $values = array(
            MatchForTotalAmount::CART_TOTAL => 400.00,
            MatchForTotalAmount::CART_CURRENCY => 'EUR'
        );
        $condition2->setValidatorsFromForm($operators, $values);

        $conditions = new ConditionCollection();
        $conditions[] = $condition1;
        $conditions[] = $condition2;
        $stubConditionFactory = $this->getMockBuilder('\Thelia\Condition\ConditionFactory')
            ->disableOriginalConstructor()
            ->getMock();
        $stubConditionFactory->expects($this->any())
            ->method('unserializeConditionCollection')
            ->will($this->returnValue($conditions));

        $stubContainer->expects($this->any())
            ->method('get')
            ->will($this->onConsecutiveCalls($stubFacade, $couponManager, $stubConditionFactory));

        $stubContainer->expects($this->any())
            ->method('has')
            ->with('request_stack')
            ->will($this->returnValue(false));

        $dummy = new Translator($stubContainer);

        $stubContainer->expects($this->any())
            ->method('has')
            ->will($this->returnValue(true));

        $factory = new CouponFactory($stubContainer);
        $factory->buildCouponFromCode('XMAS');
    }

    /**
     * @covers Thelia\Coupon\CouponFactory::buildCouponFromCode
     * @expectedException \Thelia\Exception\InvalidConditionException
     */
    public function testBuildCouponFromCodeNoConditionCoupon()
    {
        /** @var FacadeInterface|\PHPUnit_Framework_MockObject_MockObject $stubFacade */
        $stubFacade = $this->generateFacadeStub();

        $stubContainer = $this->getMockContainer();

        $conditionFactory = new ConditionFactory($stubContainer);
        $couponModel = $this->generateCouponModel($stubFacade, $conditionFactory);
        $stubFacade->expects($this->any())
            ->method('findOneCouponByCode')
            ->will($this->returnValue($couponModel));

        $couponManager = new RemoveXAmount($stubFacade);

        $condition1 = new MatchForTotalAmount($stubFacade);
        $operators = array(
            MatchForTotalAmount::CART_TOTAL => Operators::SUPERIOR,
            MatchForTotalAmount::CART_CURRENCY => Operators::EQUAL
        );
        $values = array(
            MatchForTotalAmount::CART_TOTAL => 40.00,
            MatchForTotalAmount::CART_CURRENCY => 'EUR'
        );
        $condition1->setValidatorsFromForm($operators, $values);

        $condition2 = new MatchForTotalAmount($stubFacade);
        $operators = array(
            MatchForTotalAmount::CART_TOTAL => Operators::INFERIOR,
            MatchForTotalAmount::CART_CURRENCY => Operators::EQUAL
        );
        $values = array(
            MatchForTotalAmount::CART_TOTAL => 400.00,
            MatchForTotalAmount::CART_CURRENCY => 'EUR'
        );
        $condition2->setValidatorsFromForm($operators, $values);

        $conditions = new ConditionCollection();
        $stubConditionFactory = $this->getMockBuilder('\Thelia\Condition\ConditionFactory')
            ->disableOriginalConstructor()
            ->getMock();
        $stubConditionFactory->expects($this->any())
            ->method('unserializeConditionCollection')
            ->will($this->returnValue($conditions));

        $stubContainer->expects($this->any())
            ->method('get')
            ->will($this->onConsecutiveCalls($stubFacade, $couponManager, $stubConditionFactory));

        $stubContainer->expects($this->any())
            ->method('has')
            ->will($this->returnValue(true));

        $factory = new CouponFactory($stubContainer);
        $factory->buildCouponFromCode('XMAS');
    }

    /**
     * @covers Thelia\Coupon\CouponFactory::buildCouponFromModel
     */
    public function testBuildCouponFromModel()
    {
        /** @var FacadeInterface|\PHPUnit_Framework_MockObject_MockObject $stubFacade */
        $stubFacade = $this->generateFacadeStub();

        $stubContainer = $this->getMockContainer();

        $conditionFactory = new ConditionFactory($stubContainer);
        $couponModel = $this->generateCouponModel($stubFacade, $conditionFactory);
        $stubFacade->expects($this->any())
            ->method('findOneCouponByCode')
            ->will($this->returnValue($couponModel));

        $couponManager = new RemoveXAmount($stubFacade);

        $condition1 = new MatchForTotalAmount($stubFacade);
        $operators = array(
            MatchForTotalAmount::CART_TOTAL => Operators::SUPERIOR,
            MatchForTotalAmount::CART_CURRENCY => Operators::EQUAL
        );
        $values = array(
            MatchForTotalAmount::CART_TOTAL => 40.00,
            MatchForTotalAmount::CART_CURRENCY => 'EUR'
        );
        $condition1->setValidatorsFromForm($operators, $values);

        $condition2 = new MatchForTotalAmount($stubFacade);
        $operators = array(
            MatchForTotalAmount::CART_TOTAL => Operators::INFERIOR,
            MatchForTotalAmount::CART_CURRENCY => Operators::EQUAL
        );
        $values = array(
            MatchForTotalAmount::CART_TOTAL => 400.00,
            MatchForTotalAmount::CART_CURRENCY => 'EUR'
        );
        $condition2->setValidatorsFromForm($operators, $values);

        $conditions = new ConditionCollection();
        $conditions[] = $condition1;
        $conditions[] = $condition2;
        $stubConditionFactory = $this->getMockBuilder('\Thelia\Condition\ConditionFactory')
            ->disableOriginalConstructor()
            ->getMock();
        $stubConditionFactory->expects($this->any())
            ->method('unserializeConditionCollection')
            ->will($this->returnValue($conditions));

        $stubContainer->expects($this->any())
            ->method('get')
            ->will($this->onConsecutiveCalls($stubFacade, $couponManager, $stubConditionFactory));

        $stubContainer->expects($this->any())
            ->method('has')
            ->will($this->returnValue(true));

        $factory = new CouponFactory($stubContainer);
        $expected = $couponManager;
        $actual = $factory->buildCouponFromModel($couponModel);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ContainerInterface
     */
    protected function getMockContainer()
    {
        $stubContainer = $this->getMock(ContainerInterface::class);

        return $stubContainer;
    }
}
