<?php
/**
 * @copyright © TMS-Plugins. All rights reserved.
 * @licence   See LICENCE.md for license details.
 */

namespace AmeliaBooking\Application\Controller\Coupon;

use AmeliaBooking\Application\Commands\Coupon\UpdateCouponCommand;
use AmeliaBooking\Application\Commands\CommandResult;
use AmeliaBooking\Application\Controller\Controller;
use AmeliaBooking\Domain\Events\DomainEventBus;
use Slim\Http\Request;

/**
 * Class UpdateCouponController
 *
 * @package AmeliaBooking\Application\Controller\Coupon
 */
class UpdateCouponController extends Controller
{
    /**
     * Fields for coupon that can be received from front-end
     *
     * @var array
     */
    protected $allowedFields = [
        'id',
        'code',
        'discount',
        'deduction',
        'limit',
        'customerLimit',
        'status',
        'notificationInterval',
        'notificationRecurring',
        'services',
        'events',
        'packages',
        'expirationDate',
    ];

    /**
     * Instantiates the Update Coupon command to hand it over to the Command Handler
     *
     * @param Request $request
     * @param         $args
     *
     * @return mixed
     * @throws \RuntimeException
     */
    protected function instantiateCommand(Request $request, $args)
    {
        $command = new UpdateCouponCommand($args);
        $requestBody = $request->getParsedBody();
        $this->setCommandFields($command, $requestBody);

        return $command;
    }

    /**
     * @param DomainEventBus $eventBus
     * @param CommandResult  $result
     *
     * @return void
     */
    protected function emitSuccessEvent(DomainEventBus $eventBus, CommandResult $result)
    {
        $eventBus->emit('coupon.updated', $result);
    }
}
