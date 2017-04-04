<?php
/*
 * This file is part of Flarum.
 *
 * (c) Toby Zerner <toby.zerner@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Flarum\Auth\IBM;

use Flarum\Core\User;
use Flarum\Core\Repository\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Stratigility\MiddlewareInterface;

class SSOFromCookie implements MiddlewareInterface
{
    public function __construct() {
        $this->sso = new SSO();
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        // if user_id is already set in the session, do nothing
        // otherwise, extract user information from the encrypted cookie
        $session = $request->getAttribute('session');
        if (!$session->get('user_id') and isset($_COOKIE['dWSSO'])) {

            $cookie = $this->sso->decrypt_cookie();
            list($eid, $first, $last, $email, $alias, $ibmid) = explode('|', $cookie);

            if ($email) {

                // we were able to extract the user's email from the cookie
                // this tells us that the user has already logged in
                $user = (new UserRepository)->findByEmail($email);

                // if user does not exist, create new user
                // otherwise, store user_id into session
                if (!$user) {

                  // note that we can pass in anything for password
                  $username = $first.' '.$last;
                  $user = User::register($username, $email, $ibmid);
                  $user->activate();
                  $user->save();
                }

                // populate session with user_id and pass it down
                // AuthenticateWithSession will handle the rest
                $session->set('user_id', $user->id);
            }
        }

        return $out ? $out($request, $response) : $response;
    }
}

class SSO {

  // Simplified class borrowed from IBM sso
  // to decrypt information stored in cookie

	public function __construct() {
		$configFile = parse_ini_file( dirname( __FILE__ ) . "/sso.ini");
		$this->key = $configFile['key'];
		$this->salt = $configFile['salt'];
	}

	public function decrypt_cookie() {
			return $this->decrypt($_COOKIE['dWSSO'], $this->key, $this->salt);
	}

	function decrypt($encrypted, $password, $salt) {
		$key = hash('SHA256', $salt . $password, true);
		$iv = base64_decode(substr($encrypted, 0, 22) . '==');
		$encrypted = substr($encrypted, 22);
		$decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($encrypted), MCRYPT_MODE_CBC, $iv), "\0\4");
		$hash = substr($decrypted, -32);
		$decrypted = substr($decrypted, 0, -32);
		if (md5($decrypted) != $hash) return false;
		return $decrypted;
	}

}
