<?php
/**
 * Magearya_Sales
 *
 * PHP version 7.4
 *
 * @category  PHP
 * @author    Birjitsinh Zala <birjitsinh@gmail.com>
 */
namespace Magearya\Sales\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Sales\Model\Order\StatusFactory;
use Magento\Sales\Model\ResourceModel\Order\StatusFactory as StatusResourceFactory;

/**
 * Description of PaymentReceivedStatusAdd
 *
 * @author brijitsinghzala
 */
class PaymentReceivedStatusAdd implements DataPatchInterface
{
    const PAYMENT_RECEIVED_STATUS_CODE = 'received';
    const PAYMENT_RECEIVED_STATUS_LABEL = 'Payment Received';
    const NEW_STATE_CODE = 'new';

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var StatusFactory
     */
    protected $statusFactory;

    /**
     * @var StatusResourceFactory
     */
    protected $statusResourceFactory;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param StatusFactory $statusFactory
     * @param StatusResourceFactory $statusResourceFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        StatusFactory $statusFactory,
        StatusResourceFactory $statusResourceFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->statusFactory = $statusFactory;
        $this->statusResourceFactory = $statusResourceFactory;
    }

    /**
     * @inheritdoc
     */
    public function apply()
    {
        $status = $this->statusFactory->create();

        $status->setData([
            'status' => self::PAYMENT_RECEIVED_STATUS_CODE,
            'label' => self::PAYMENT_RECEIVED_STATUS_LABEL,
        ]);

        /**
         * Save the new status
         */
        $statusResource = $this->statusResourceFactory->create();
        $statusResource->save($status);

        /**
         * Assign status to state
         */
        $status->assignState(self::NEW_STATE_CODE, true, true);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }

}
