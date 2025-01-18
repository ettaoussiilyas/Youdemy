<?php

    require_once __DIR__.'/../includes/autoloaderControllers.php';

    class AuthController extends BaseController {

        private $courseModel;
        private $userModel;

        public function __construct(){
            $this->courseModel = new Course();
            $this->userModel = new User();
        }

        public function showHome(){
            if($_SERVER['REQUEST_URI'] !== '/home'){ 
                header('Location: /home');
                exit;
            }
            $this->render('Home');
        }

        //shoing login page
        public function showLogin(){
            // $this->render('auth/login', ['error' => 'Invalid password']);
            $this->render('auth/login');
        }

        public function showSignup(){
            $this->render('auth/signup');
        }




        public function loginChecker(){
            if (!$_SERVER['REQUEST_METHOD'] === 'POST' || !isset($_POST['login'])) {
                return $this->render('auth/login');
            }

            $email = $_POST['email'];
            $password = $_POST['password'];



            if (empty($email) || empty($password)) {
                return $this->render('auth/login', ['errors' => 'Please fill all fields']);
            }

            $user = $this->userModel->getUserByEmail($email);
            
            if (!$user) {
                return $this->render('auth/login', ['errors' => 'User not found']);
            }                 
            
            if (!password_verify($password, $user['password'])) {
                return $this->render('auth/login', ['errors' => 'Invalid password']);
            }

            if ($user['status'] === 'blocked') {
                return $this->render('auth/login', ['errors' => 'Your account is blocked']);
            }

            // Handle teacher-specific statuses
            if ($user['role'] === 'teacher') {
                if ($user['status'] === 'review') {
                    return $this->render('auth/login', ['errors' => 'Your account is Still on Review']);
                }
                if ($user['status'] === 'blocked') {
                    return $this->render('auth/login', ['errors' => 'Your account is blocked']);
                }
            }

            // Only proceed if user is active
            if ($user['status'] === 'active') {
                // Set common session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_profile_image'] = $user['profile_image'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_status'] = $user['status'];
                $_SESSION['user_role'] = $user['role'];

                // Redirect based on role
                switch ($user['role']) {
                    case 'admin':
                        header('Location: /admin/dashboard');
                        break;
                    case 'student':
                        header('Location: /student/dashboard');
                        break;
                    case 'teacher':
                        header('Location: /teacher/dashboard');
                        break;
                }
                exit;
            }

            // Fallback for any unexpected status
            return $this->render('auth/login', ['errors' => 'Invalid account status']);
        }

        public function signupChecker(){

            if (!$_SERVER['REQUEST_METHOD'] === 'POST' || !isset($_POST['signup'])) {
                return $this->render('auth/signup');
            }


            $name = $_POST['full_name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $password_confirmed = $_POST['password_confirmed'];
            $role = $_POST['role'];


            if(empty($name) || empty($email) || empty($password) || empty($password_confirmed) || empty($role)){
                return $this->render('auth/signup', ['errors' => 'Please fill all fields']);
            }
            if($password !== $password_confirmed){
                return $this->render('auth/signup', ['errors' => 'Passwords do not match']);
            }
            if($role != 'student' && $role != 'teacher'){
                return $this->render('auth/signup', ['errors' => 'Invalid role']);
            }
            if($this->userModel->getUserByEmail($email)){
                return $this->render('auth/signup', ['errors' => 'Email already exists']);
            }
            if(strlen($password) < 4){
                return $this->render('auth/signup', ['errors' => 'Password must be at least 4 characters long']);
            }
            if(strlen($name) < 8){
                return $this->render('auth/signup', ['errors' => 'Name must be at least 8 characters long']);
            }
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                return $this->render('auth/signup',['errors' => 'This Email Form is not valid']);
            }
            if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
                return $this->render('auth/signup', ['errors' => 'Name can only contain letters and spaces']);
            }


            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $userId = $this->userModel->createUser($name, $email, $password_hash, $role);

            if($userId) {
                // Create notification with the actual user ID
                try {
                    $notification = new SignupNotification($userId, $name); // Pass the actual user ID
                    $notification->send();
                } catch (Exception $e) {
                    error_log("Failed to send signup notification: " . $e->getMessage());
                }
                return $this->render('auth/login', ['success' => 'Account created successfully']);
            }else{
                return $this->render('auth/signup', ['errors' => 'Failed to create account']);
            }
        }

        public function logout(){
            session_unset();
           
            session_destroy();
            if (isset($_COOKIE[session_name()])) {
                setcookie(session_name(), '', time()-3600, '/');
            }
            header('Location: /home');
            exit;
        }

    }



?>