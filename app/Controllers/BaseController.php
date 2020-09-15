<?php
namespace App\Controllers;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @package CodeIgniter
 */

use App\Models\GroupsModel;
use App\Models\OrdersModel;
use App\Models\OrdersViewModel;
use App\Models\UsersModel;
use CodeIgniter\Controller;

class BaseController extends Controller
{

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = [];
    /**
     * @var \CodeIgniter\Session\Session
     */
    protected $session;
    /**
     * @var UsersModel
     */
    protected $userModel;
    /**
     * @var GroupsModel
     */
    protected $groupModel;
    /**
     * @var OrdersModel
     */
    protected $orderModel;
    /**
     * @var OrdersViewModel
     */
    protected $ordersViewModel;
    /**
     * @var \CodeIgniter\Database\Forge
     */
    private $dbforge;

    /**
	 * Constructor.
	 */
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.:
		// $this->session = \Config\Services::session();
        $this->session = \Config\Services::session();

        $this->dbforge = \Config\Database::forge();
        $this->createDB();

        $this->userModel = new UsersModel();
        $this->groupModel = new GroupsModel();
        $this->orderModel = new OrdersModel();
        $this->ordersViewModel = new OrdersViewModel();
	}

	public function createDB()
    {
        $this->dbforge->addField([
            'blog_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'blog_title' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'blog_description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
        $this->dbforge->addKey('blog_id', true);
        $this->dbforge->createTable('blog');
    }
}
