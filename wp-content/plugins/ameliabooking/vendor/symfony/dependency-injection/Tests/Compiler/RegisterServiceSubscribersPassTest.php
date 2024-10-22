<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Tests\Compiler;

use PHPUnit\Framework\TestCase;
use AmeliaPsr\Container\ContainerInterface as PsrContainerInterface;
use Symfony\Component\DependencyInjection\Argument\ServiceClosureArgument;
use Symfony\Component\DependencyInjection\Compiler\RegisterServiceSubscribersPass;
use Symfony\Component\DependencyInjection\Compiler\ResolveServiceSubscribersPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\DependencyInjection\Tests\Fixtures\CustomDefinition;
use Symfony\Component\DependencyInjection\Tests\Fixtures\TestServiceSubscriber;
use Symfony\Component\DependencyInjection\TypedReference;

require_once __DIR__.'/../Fixtures/includes/classes.php';

class RegisterServiceSubscribersPassTest extends TestCase
{
    public function testInvalidClass()
    {
        $this->expectException('Symfony\Component\DependencyInjection\Exception\InvalidArgumentException');
        $this->expectExceptionMessage('Service "foo" must implement interface "Symfony\Component\DependencyInjection\ServiceSubscriberInterface".');
        $container = new ContainerBuilder();

        $container->register('foo', CustomDefinition::class)
            ->addTag('container.service_subscriber')
        ;

        (new RegisterServiceSubscribersPass())->process($container);
        (new ResolveServiceSubscribersPass())->process($container);
    }

    public function testInvalidAttributes()
    {
        $this->expectException('Symfony\Component\DependencyInjection\Exception\InvalidArgumentException');
        $this->expectExceptionMessage('The "container.service_subscriber" tag accepts only the "key" and "id" attributes, "bar" given for service "foo".');
        $container = new ContainerBuilder();

        $container->register('foo', TestServiceSubscriber::class)
            ->addTag('container.service_subscriber', ['bar' => '123'])
        ;

        (new RegisterServiceSubscribersPass())->process($container);
        (new ResolveServiceSubscribersPass())->process($container);
    }

    public function testNoAttributes()
    {
        $container = new ContainerBuilder();

        $container->register('foo', TestServiceSubscriber::class)
            ->addArgument(new Reference(PsrContainerInterface::class))
            ->addTag('container.service_subscriber')
        ;

        (new RegisterServiceSubscribersPass())->process($container);
        (new ResolveServiceSubscribersPass())->process($container);

        $foo = $container->getDefinition('foo');
        $locator = $container->getDefinition((string) $foo->getArgument(0));

        $this->assertFalse($locator->isPublic());
        $this->assertSame(ServiceLocator::class, $locator->getClass());

        $expected = [
            TestServiceSubscriber::class => new ServiceClosureArgument(new TypedReference(TestServiceSubscriber::class, TestServiceSubscriber::class, TestServiceSubscriber::class)),
            CustomDefinition::class => new ServiceClosureArgument(new TypedReference(CustomDefinition::class, CustomDefinition::class, TestServiceSubscriber::class, ContainerInterface::IGNORE_ON_INVALID_REFERENCE)),
            'bar' => new ServiceClosureArgument(new TypedReference(CustomDefinition::class, CustomDefinition::class, TestServiceSubscriber::class)),
            'baz' => new ServiceClosureArgument(new TypedReference(CustomDefinition::class, CustomDefinition::class, TestServiceSubscriber::class, ContainerInterface::IGNORE_ON_INVALID_REFERENCE)),
        ];

        $this->assertEquals($expected, $container->getDefinition((string) $locator->getFactory()[0])->getArgument(0));
    }

    public function testWithAttributes()
    {
        $container = new ContainerBuilder();

        $container->register('foo', TestServiceSubscriber::class)
            ->setAutowired(true)
            ->addArgument(new Reference(PsrContainerInterface::class))
            ->addTag('container.service_subscriber', ['key' => 'bar', 'id' => 'bar'])
            ->addTag('container.service_subscriber', ['key' => 'bar', 'id' => 'baz']) // should be ignored: the first wins
        ;

        (new RegisterServiceSubscribersPass())->process($container);
        (new ResolveServiceSubscribersPass())->process($container);

        $foo = $container->getDefinition('foo');
        $locator = $container->getDefinition((string) $foo->getArgument(0));

        $this->assertFalse($locator->isPublic());
        $this->assertSame(ServiceLocator::class, $locator->getClass());

        $expected = [
            TestServiceSubscriber::class => new ServiceClosureArgument(new TypedReference(TestServiceSubscriber::class, TestServiceSubscriber::class, TestServiceSubscriber::class)),
            CustomDefinition::class => new ServiceClosureArgument(new TypedReference(CustomDefinition::class, CustomDefinition::class, TestServiceSubscriber::class, ContainerInterface::IGNORE_ON_INVALID_REFERENCE)),
            'bar' => new ServiceClosureArgument(new TypedReference('bar', CustomDefinition::class, TestServiceSubscriber::class)),
            'baz' => new ServiceClosureArgument(new TypedReference(CustomDefinition::class, CustomDefinition::class, TestServiceSubscriber::class, ContainerInterface::IGNORE_ON_INVALID_REFERENCE)),
        ];

        $this->assertEquals($expected, $container->getDefinition((string) $locator->getFactory()[0])->getArgument(0));
    }

    public function testExtraServiceSubscriber()
    {
        $this->expectException('Symfony\Component\DependencyInjection\Exception\InvalidArgumentException');
        $this->expectExceptionMessage('Service key "test" does not exist in the map returned by "Symfony\Component\DependencyInjection\Tests\Fixtures\TestServiceSubscriber::getSubscribedServices()" for service "foo_service".');
        $container = new ContainerBuilder();
        $container->register('foo_service', TestServiceSubscriber::class)
            ->setAutowired(true)
            ->addArgument(new Reference(PsrContainerInterface::class))
            ->addTag('container.service_subscriber', [
                'key' => 'test',
                'id' => TestServiceSubscriber::class,
            ])
        ;
        $container->register(TestServiceSubscriber::class, TestServiceSubscriber::class);
        $container->compile();
    }
}
