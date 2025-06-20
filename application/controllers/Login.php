<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/third_party/hybridauth/autoload.php';

//Import Hybridauth's namespace
use Hybridauth\Hybridauth;

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        //Do your magic here
        $this->load->model(array('M_login'));
        is_not_login();
    }

    // Fungsi untuk mengecek jaringan lokal
    private function _check_local_network()
    {
        // Mendapatkan nama host dari IP address pengunjung
        $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        // Misalnya, menggunakan substring atau pengecekan DNS lokal
        if (strpos($hostname, 'local') === false) {
            show_error('Akses hanya diperbolehkan di jaringan lokal!', 403);
        }
    }
    
    public function index()
    {


        //Form validation
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data = array(
                'judul' => 'Login',
            );
            $this->load->view('template/login', $data);
        } else {
            $this->_login();
        }
    }

    function auth($provider = NULL)
    {
        $service = NULL;

        try {
            //Instantiate Hybridauth's classes
            $hybrid = new Hybridauth($this->getHybridConfig());

            //Check if given provider is enabled
            if ((isset($provider)) && in_array($provider, $hybrid->getProviders())) {
                $this->session->set_userdata('provider', $provider);
            }

            //Update variable with the valid provider
            $provider = $this->session->userdata('provider');

            if ($provider) {
                $service = $hybrid->authenticate($provider);
                if ($service->isConnected()) {
                    //Get user profile
                    $profile = $service->getUserProfile();

                    //Get user contacts
                    $contacts = $service->getUserContacts();
                    $service->disconnect();

                    $this->session->unset_userdata('provider');
                    $data = array(
                        'identifier' => $profile->identifier,
                        'name' => $profile->displayName,
                        'email' => $profile->email,
                        'photo' => $profile->profileURL
                    );
                    print_r($data);
                    die;
                    $id = $this->M_user->insert($data);
                    $sess = array(
                        'id' => $id,
                        'name' => $profile->displayName,
                        'photo' => $profile->photoURL
                    );

                    $this->session->set_userdata($sess);

                    redirect('Home', 'refresh');
                } else {
                    $this->session->set_flashdata('showmsg', array('msg' => 'Sorry! We couldn\'t authenticate your identity.'));
                }
            }
        } catch (Exception $e) {
            if (isset($service) && $service->isConnected())
                $service->disconnect();

            $error = 'Sorry! We couldn\'t authenticate you.';
            $this->session->set_flashdata('showmsg', array('msg' => $error));
            $error .= '\nError Code: ' . $e->getCode();
            $error .= '\nError Message: ' . $e->getMessage();

            log_message('error', $error);
        }

        //redirect();
    }

    //Hybridauth configuration
    private function getHybridConfig()
    {
        $config = array(

            'callback' => site_url('login/auth/'),

            'providers' => array(
                'Google' => array(
                    'enabled' => false,
                    'keys' => array(
                        'id' => '76578426838-ta6h0tplf0q4c302kov4dttv59v4dskn.apps.googleusercontent.com',
                        'secret' => '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'
                    ),
                    // 'scope' => 'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile'
                ),

                'Facebook' => array(
                    'enabled' => false,
                    'keys' => array(
                        'id' => (ENVIRONMENT == 'development') ? '337301198261561' : '337301198261561',
                        'secret' => (ENVIRONMENT == 'development') ? '82fce8546879a3a6fcfc603e4627356e' : '82fce8546879a3a6fcfc603e4627356e'
                    ),
                    'scope' => 'email, public_profile'
                ),

                'Twitter' => array(
                    'enabled' => false,
                    'keys' => array(
                        'key' => 'APP_KEY',
                        'secret' => 'APP_SECRET'
                    )
                )
            ),

            'hybrid_debug' => array(
                'debug_mode' => 'info', /* none, debug, info, error */
                'debug_file' => APPPATH . '/logs/log-' . date('Y-m-d') . '.php'
            )
        );

        return $config;
    }

    private function _login()
    {
        $username = htmlspecialchars($this->input->post('username'));
        $password = $this->input->post('password');
        // echo $username;
        // die;
        $user = $this->M_login->getUser($username);

        // Jika ada
        if ($user) {
            // cek password
            if (password_verify($password, $user['password'])) {
                $data = [
                    'APP_NAME' => $this->config->item('_APP'),
                    'id_user' => $user['id_user'],
                    'level' => $user['level'],
                    'nama' => $user['nama'],
                ];
                $this->session->set_userdata($data);

                $this->M_login->session_login($this->input->ip_address());
                redirect('dashboard');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Username atau Password salah!</div>');
                redirect('login');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Username atau Password salah!</div>');
            redirect('login');
        }
    }
}

/* End of file Login.php */
